<?php
namespace at\eisisoft\partywithme\api;

use at\eisisoft\partywithme as pwm;
use function at\eisisoft\partywithme\toJSON;

require_once "../../hidden/partials/jsonHeader.php";
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 05.03.2016
 * Time: 10:21
 */
$sql = file_get_contents(resolveFileRelativeToHidden("resources/sql/api/partywithme_delete.sql"));
$eventID = pwm\Helper::getPostVariable("eventId", "");
$fbLoginValid = isFbLogin();
if (is_null($fbLoginValid)) {
    $result = -1;
} else
    try {
        $connection = pwm\MySQLHelper::getConnection();
        $pstmt = $connection->prepare($sql);
        $pstmt->bindValue(':eventId', $eventID, \PDO::PARAM_STR);
        $pstmt->bindValue(":userId", $fbLoginValid[FB_USER_ID_KEY], \PDO::PARAM_STR);
        $pstmt->execute();
        $result = 0;
    } catch (\PDOException $e) {
        $result = 1;
    }
echo '{"result":' . $result . '}';
?>