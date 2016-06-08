<?php
use at\eisisoft\partywithme\MySQLHelper;
use function at\eisisoft\partywithme\api\getFacebookAPI;
use function at\eisisoft\partywithme\api\isFbLogin;

require_once "hidden/partials/constants.php";
session_start();
$fbLoginData = isFbLogin();
if ($fbLoginData) {
    $fbApi = getFacebookAPI();
    $sql = 'DELETE FROM users WHERE id = :id LIMIT 1';
    $con = MySQLHelper::getConnection();
    $pstmt = $con->prepare($sql);
    $pstmt->bindParam(':id', $fbLoginData[FB_USER_ID_KEY]);
    $fbApi->delete('me/permissions/', [], $fbLoginData[FB_ACCESS_TOKEN_KEY]);
    $pstmt->execute();
}
require 'logout.php';
?>