<?php
require_once "hidden/partials/constants.php";
session_start();
use at\eisisoft\partywithme\Helper;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use function at\eisisoft\partywithme\api\getFacebookAPI;
use function at\eisisoft\partywithme\api\isFbLogin;
use function at\eisisoft\partywithme\api\setFbLoginData;
use function at\eisisoft\partywithme\api\updateEvents;
use function at\eisisoft\partywithme\api\updatePersonalData;

$u = Helper::getVariable("url", "");
if ($u === '/')
    $u = '';
$url = PARTYWITHME_UI_URL . $u;
$fb = getFacebookAPI();
$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
    if (isset($accessToken)) {
        $oAuth2Client = $fb->getOAuth2Client();
        $accessToken = (string)$oAuth2Client->getLongLivedAccessToken($accessToken);
        $uid = updatePersonalData($accessToken);
        setFbLoginData($accessToken, $uid);
        updateEvents($accessToken, $uid);
    }
} catch (FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
} catch (FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
} catch (PDOException $e) {
    echo "PDO Exception: " . $e;
}
header("refresh:0;url=$url");
?>
