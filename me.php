<?php
namespace at\eisisoft\partywithme\ui;
require_once "hidden/partials/constants.php";
use at\eisisoft\partywithme\Helper;

/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 07.03.2016
 * Time: 16:37
 */
$id = Helper::getVariable("id", "-1");
$pwm = Helper::getVariableAsBoolean("pwm");

$jsConst["EVENT_ID"] = $id;
global $jsConst;
require_once "hidden/partials/htmluiheader.php";
?>
    <?php
if (is_null($FB_LOGIN_VALID)) {
    require "hidden/partials/login.php";
} else {
    require "hidden/partials/ui/me.php";
}
?>

<?php require_once "hidden/partials/htmlfooter.php"; ?>