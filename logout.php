<?php
use function at\eisisoft\partywithme\api\setFbLoginData;

require_once "hidden/partials/constants.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
setFbLoginData(null, null);
header('refresh:0;url=' . PARTYWITHME_UI_URL);
?>