<?php
namespace at\eisisoft\partywithme\api;

/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 11.03.2016
 * Time: 12:09
 */
use at\eisisoft\partywithme\MySQLHelper;
use Facebook\Facebook;
use Facebook\FacebookResponse;

/**
 * @param $actualUrl string
 * @return string
 */
function getLoginUrl($actualUrl)
{
    $fb = getFacebookAPI();
    $helper = $fb->getRedirectLoginHelper();
    return $helper->getLoginUrl(PARTYWITHME_UI_URL . 'callback.php?url=' . $actualUrl, getFBPermissions()) . '&auth_type=rerequest';
}

/**
 * @param $accessToken string The FB Access token
 * @return string|null FB user ID
 */
function updatePersonalData($accessToken)
{
    $sql = file_get_contents(resolveFileRelativeToHidden('resources/sql/ui/loginUser.sql'));
    $male = "male";
    $female = "female";
    $response = callGraphAPI("/me?fields=id,first_name,last_name,birthday,gender,interested_in", $accessToken);
    $response = json_decode($response->getBody(), true);
    if (isset($response["id"])) {
        $id = $response["id"];
    } else {
        $id = null;
    }
    $firstName = $response["first_name"];
    $lastName = $response["last_name"];
    $birthday = $response["birthday"];
    //Parse birthday
    if (strlen($birthday) === 4) {
        $birthday = "12/31/$birthday";
    }
    $gender = $response["gender"];
    if ($gender === $male) {
        $gender = "m";
    } else {
        if ($gender === $female) {
            $gender = "f";
        }
    }
    if (!isset($response["interested_in"])) {
        $interested = 'n';
    } else {
        $interestedArray = $response["interested_in"];
        if (empty($interestedArray)) {
            $interested = 'n';
        } else
            if (in_array($male, $interestedArray)) {
                //Male in Array
                if (in_array($female, $interestedArray)) {
                    //Male and female
                    $interested = "b";
                } else {
                    $interested = "m";
                }
            } else {
                $interested = "f";
            }
    }
    $connection = MySQLHelper::getConnection();
    $connection->beginTransaction();
    $pstmt = $connection->prepare($sql);
    $pstmt->bindValue(":id", $id, \PDO::PARAM_STR);
    $pstmt->bindValue(":firstName", $firstName, \PDO::PARAM_STR);
    $pstmt->bindValue(":lastName", $lastName, \PDO::PARAM_STR);
    $pstmt->bindValue(":gender", $gender, \PDO::PARAM_STR);
    $pstmt->bindValue(":interested", $interested, \PDO::PARAM_STR);
    $pstmt->bindValue(":birthday", $birthday, \PDO::PARAM_STR);
    $pstmt->execute();
    $connection->commit();
    return $id;
}

/**
 * @return Facebook
 */
function getFacebookAPI()
{
    return new Facebook([
        'app_id' => FB_APP_ID,
        'app_secret' => FB_APP_SECRET,
        'default_graph_version' => FB_GRAPH_VERSION,
    ]);
}

/**
 * @param $string string
 * @param $accessToken string
 * @param null $fb Facebook
 * @return FacebookResponse
 */
function callGraphAPI($string, $accessToken, $fb = null)
{
    try {
        if ($fb === null) {
            $fb = getFacebookAPI();
        }
        return $fb->get($string, $accessToken);;
    } catch (\Exception $e) {
        var_dump($e);
        $_SESSION[FB_ACCESS_TOKEN_KEY] = null;
        $_SESSION[FB_USER_ID_KEY] = null;
        return null;
    }
}

function updateEvents($accessToken, $uid)
{
    $fb = getFacebookAPI();
    $newEventsSQL = "INSERT INTO events(id,name,description,coverUrl,place,startTime,endTime) VALUES ";
    $updateEventsSQL = '';
    $updateVisitsSql = '';
    $getTocreateEventsSql = '';
    $newVisitsSQL = "INSERT INTO visits(userID,eventID,rsvp_status) VALUES ";
    $deleteVisitsSQL = "DELETE FROM visits WHERE ";
    $response = callGraphAPI('/me/events?fields=name,description,id,start_time,end_time,rsvp_status,place,cover&since=now', $accessToken, $fb);
    $response = $response->getGraphEdge();
    $responseData = $response->asArray();
    $fetch = 1;
    if (!empty($responseData)) {
        $data = $responseData;
    } else {
        return;
    }
    while ($fetch < 3) {
        $response = $fb->next($response);
        if ($response === null) {
            $responseData = null;
        } else {
            $responseData = $response->asArray();
        }
        if ($responseData !== null && !empty($responseData)) {
            $data = array_merge($data, $responseData);
        } else {
            $fetch = 200000; // exit while loop
        }
        $fetch++;
    }
    $con = MySQLHelper::getConnection();
    $con->beginTransaction();
    //Get all event IDs
    $pstmt = $con->prepare("SELECT id FROM events WHERE  id IN (SELECT eventID FROM visits WHERE userID = :userId) AND events.endTime >= CURRENT_TIMESTAMP;");
    $pstmt->bindParam(':userId', $uid, \PDO::PARAM_STR);
    $pstmt->execute();
    $curEvents = $pstmt->fetchAll(\PDO::FETCH_COLUMN);
    $aEvents = [];
    $aVisits = [];
    $events = [];
    foreach ($data as $event) {
        if (isset($event['place']) && isset($event['place']['location']) && isset($event['place']['name'])) {
            $location = $event['place']['location'];
            $place = $event['place']['name'] . ' [' . (isset($location['street']) ? $location['street'] : '') . ' ' . (isset($location['zip']) ? $location['zip'] : '') . ' ' . (isset($location['city']) ? $location['city'] : '') . (isset($location['country']) ? ' (' . $location['country'] . ')]' : '');
        } else {
            $place = "";
        }
        $eid = $event['id'];
        $rsvp_status = $event['rsvp_status'];
        $keyToDelete = array_search($eid, $curEvents);
        if (is_numeric($keyToDelete)) {
            //Visit found
            unset($curEvents[$keyToDelete]);
            $updateVisitsSql .= "update visits set rsvp_status='$rsvp_status' WHERE eventID= $eid AND userID = $uid;";
        } else {
            //Create new visit
            $aVisits [] = [$uid, $eid, $rsvp_status];
        }
        $events[] = $eid;
        if (isset($event['name'])) {
            $name = $event['name'];
        } else {
            $name = '';
        }
        if (isset($event['description'])) {
            $description = $event['description'];
        } else {
            $description = '';
        }
        if (isset($event['cover'])) {
            $coverUrl = $event['cover']['source'];
        } else {
            $coverUrl = PARTYWITHME_UI_URL . 'png/cover.png';
        }
        $startTime = $event['start_time'];
        $startTime = $startTime->format(MYSQL_TIMESTAMP_FORMAT);
        $endTime = isset($event['end_time']) ? $event['end_time']->format(MYSQL_TIMESTAMP_FORMAT) : $startTime;
        $aEvents [] = [$eid, $name, $description, $coverUrl, $place, $startTime, $endTime];

        if (empty($getTocreateEventsSql)) {
            $getTocreateEventsSql .= "SELECT id.id FROM ( SELECT $eid as id";
        } else {
            $getTocreateEventsSql .= " union select $eid ";
        }
    }
    $newEventsArray = [];
    if (empty($getTocreateEventsSql)) {
        $result = [];
    } else {
        $getTocreateEventsSql .= ') id left join events on id.id = events.id where isnull(events.id);';
        //check which events needs creation
        $result = $con->query($getTocreateEventsSql);
        //Array with IDs, which events need to be created
        $result = $result->fetchAll(\PDO::FETCH_COLUMN);

    }
    foreach ($aEvents as $curEvent) {
        $key = array_search($curEvent[0], $result);
        if ($key === false) {
            $updateEventsSQL .= 'UPDATE events SET name=' . $con->quote($curEvent[1]) . ', description=' . $con->quote($curEvent[2]) . ', coverUrl=' . $con->quote($curEvent[3]) . ',place =' . $con->quote($curEvent[4]) . ', startTime=' . $con->quote($curEvent[5]) .
                ', endTime=' . $con->quote($curEvent[6]) . ' WHERE id =' . $curEvent[0] . ';';
        } else {
            unset($result[$key]);
            $newEventsArray [] = '(' . $con->quote($curEvent[0]) . ',' . $con->quote($curEvent[1]) . ',' . $con->quote($curEvent[2]) . ',' . $con->quote($curEvent[3]) . ',' . $con->quote($curEvent[4]) . ',' . $con->quote($curEvent[5]) . ',' . $con->quote($curEvent[6]) . ')';
        }
    }
    if (empty($newEventsArray)) {
        $newEventsSQL = '';
    } else {
        $newEventsSQL .= implode(',', $newEventsArray);
    }
    $newVisitsArray = [];
    foreach ($aVisits as $a) {
        $newVisitsArray[] = '\'' . implode("','", $a) . '\'';
    }
    $newVisitsSQL .= '(' . implode('),(', $newVisitsArray) . ')';
    if (empty($curEvents)) {
        $deleteVisitsSQL .= ' 2+2=5';
    } else {
        $deleteVisitsSQL .= 'eventID IN (\'' . implode('\',\'', $curEvents) . '\')';

    }
    if (!empty($newEventsSQL)) {
        $con->exec($newEventsSQL);
    }
    if (!empty($updateEventsSQL)) {
        $con->exec($updateEventsSQL);
    }
    if (!empty($newVisitsArray)) {
        $con->exec($newVisitsSQL);
    }
    if (!empty($updateVisitsSql)) {
        $con->exec($updateVisitsSql);
    }
    $con->exec($deleteVisitsSQL);
    $con->commit();
}

/**
 * @return null|array
 */
function isFbLogin()
{
    if (isset($_SESSION[FB_ACCESS_TOKEN_KEY])) {
        $accessToken = $_SESSION[FB_ACCESS_TOKEN_KEY];
    } else {
        $accessToken = null;
    }
    if (isset($_SESSION[FB_USER_ID_KEY])) {
        $userId = $_SESSION[FB_USER_ID_KEY];
    } else {
        $userId = null;
    }
    if ($accessToken == null || $userId == null) {
        return null;
    }
    return [FB_USER_ID_KEY => $userId, FB_ACCESS_TOKEN_KEY => $accessToken];
}

function setFbLoginData($accessToken, $userID)
{
    $_SESSION[FB_ACCESS_TOKEN_KEY] = $accessToken;
    $_SESSION[FB_USER_ID_KEY] = $userID;
}

/**
 * @param $fbLogin array The FB login data
 * @return bool if execution should continue
 */
function fetchEvents($fbLogin)
{
    if (is_null($fbLogin)) {
        echo "{result:-1}";
        return false;
    }
    // $_SESSION[NEXT_FETCH_DATA] = null; //Override, always renew
    if (isset($_SESSION[NEXT_FETCH_DATA])) {
        $now = new  \DateTime();
        try {
            $parsed = \DateTime::createFromFormat(\DateTime::COOKIE, $_SESSION[NEXT_FETCH_DATA]);
        } catch (\Exception $e) {
            $parsed = $now;
        }
        if ($now >= $parsed) {
            try {
                updateEvents($fbLogin[FB_ACCESS_TOKEN_KEY], $fbLogin[FB_USER_ID_KEY]);
            } catch (\Exception $e) {
                setFbLoginData(null, null);
            }
            $parsed->add(new  \DateInterval(NEXT_FETCH_DURATION));
            $_SESSION[NEXT_FETCH_DATA] = $parsed->format(\DateTime::COOKIE);
        }

    } else {
        try {
            updateEvents($fbLogin[FB_ACCESS_TOKEN_KEY], $fbLogin[FB_USER_ID_KEY]);
        } catch (\Exception $e) {
            setFbLoginData(null, null);
        }
        $next = new \DateTime();
        $next->add(new  \DateInterval(NEXT_FETCH_DURATION));
        $_SESSION[NEXT_FETCH_DATA] = $next->format(\DateTime::COOKIE);
    }
    return true;
}