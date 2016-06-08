<?php
//Set timezone
date_default_timezone_set('Europe/Vienna');
$curdir = realpath(dirname(__FILE__)) . '/';
$fbDir = $curdir . "../fbSdk";
define('FACEBOOK_SDK_SRC_DIR', $fbDir);
define('PARTIALS_DIR', $curdir);
$fbDir .= '/autoload.php';
require_once $fbDir;
require_once $curdir . "/../at/reisisoft/partywithme/Helper.php";
require_once $curdir . "/../at/reisisoft/partywithme/MySQLHelper.php";
require_once $curdir . "/updateData.php";
set_include_path($curdir . "/../config");
require_once "app.sec.php"; // App config
require_once "fb.sec.php";

define("FB_ACCESS_TOKEN_KEY", 'facebook_access_token');
define("FB_USER_ID_KEY", 'facebook_user_id');
define("EVENT_LAST_FORCE_KEY", 'partywithme_last_force');
define("NEXT_FETCH_DATA", 'next_fetch_data');
define('NEXT_FETCH_DURATION', 'PT1M');
define('MYSQL_TIMESTAMP_FORMAT', 'Y-m-d H:i:s');
define('primary_color', '#2196f3');
define('secondary_color', '#536dfe');
/**
 * @param $filename string The filename which should be resolved
 * @return string The combined path
 */
function resolveFileRelativeToHidden($filename)
{
    return PARTIALS_DIR . '../' . $filename;
}

function resolveFileRelativeToProjectRoot($filename)
{
    return PARTIALS_DIR . '../../' . $filename;
}

?>