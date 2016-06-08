<?php
namespace at\eisisoft\partywithme\api;

/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 05.03.2016
 * Time: 09:11
 */
use at\eisisoft\partywithme as pwm;
use function at\eisisoft\partywithme\api\fetchEvents;
use function at\eisisoft\partywithme\api\toJSON;

require_once "../hidden/partials/jsonHeader.php";

$fbLogin = isFbLogin();
$sql = 'SELECT * FROM users WHERE id = :userId';
if (fetchEvents($fbLogin)) {
    try {
        $connection = pwm\MySQLHelper::getConnection();
        $pstmt = $connection->prepare($sql);
        $pstmt->bindValue(":userId", $fbLogin[FB_USER_ID_KEY], \PDO::PARAM_STR);
        toJSON($pstmt);;
    } catch (\PDOException $e) {
        echo json_encode($e);
    }
}
?>