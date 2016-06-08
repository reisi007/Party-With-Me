<?php
namespace at\eisisoft\partywithme\api;

use at\eisisoft\partywithme as pwm;
use at\eisisoft\partywithme\MySQLHelper;
use function at\eisisoft\partywithme\api\fetchEvents;
use function at\eisisoft\partywithme\api\toJSON;

require_once "../hidden/partials/jsonHeader.php";
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 07.03.2016
 * Time: 17:02
 */
$sql = file_get_contents(resolveFileRelativeToHidden("resources/sql/api/getevent.sql"));
$id = pwm\Helper::getPostVariable("eventId", "-1");
$fbLogin = isFbLogin();
if (fetchEvents($fbLogin)) {
    try {
        $connection = MySQLHelper::getConnection();
        $pstmt = $connection->prepare($sql);
        $pstmt->bindValue(':eventId', $id, \PDO::PARAM_STR);
        $pstmt->bindValue(":userId", $fbLogin[FB_USER_ID_KEY], \PDO::PARAM_STR);
        toJSON($pstmt);;
    } catch (\PDOException $e) {
        echo "[]";
    }
}
?>