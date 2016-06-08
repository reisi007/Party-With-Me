<?php
namespace at\eisisoft\partywithme\ui;
require_once "hidden/partials/constants.php";
use at\eisisoft\partywithme\Helper;
use function at\eisisoft\partywithme\api\fetchEvents;

/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 07.03.2016
 * Time: 16:37
 */
$eid = Helper::getVariable("id", "-1");
$force = Helper::getPostVariable("force");
$ogUrl = "event.php?id=$eid";
$jsConst["EVENT_ID"] = $eid;
global $jsConst;
require_once "hidden/partials/htmluiheader.php";
?>
    <?php
if (is_null($FB_LOGIN_VALID)) {
    require "hidden/partials/login.php";
} else {
    if ($force !== null) {
        if (isset($_SESSION[EVENT_LAST_FORCE_KEY])) {
            $lastForce = $_SESSION[EVENT_LAST_FORCE_KEY];
        } else {
            $lastForce = 0;
        }
        if ($lastForce < $force) {
            $now = new \DateTime($force);
            $_SESSION[NEXT_FETCH_DATA] = $now->format(\DateTime::COOKIE);
            fetchEvents($FB_LOGIN_VALID);
        }
    }
    require "hidden/partials/ui/event.php";
}
?>

<?php require_once "hidden/partials/htmlfooter.php"; ?>