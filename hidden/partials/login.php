<?php
namespace at\eisisoft\partywithme\ui;

use function at\eisisoft\partywithme\api\getFacebookAPI;
use function at\eisisoft\partywithme\api\getLoginUrl;

require_once "constants.php";
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 11.03.2016
 * Time: 09:30
 */

$actualUrl = $_SERVER['REQUEST_URI'];
$loginUrl = getLoginUrl($actualUrl);
?>
<div class="login">
    <h1>Party With Me!</h1>
    <h2>Find new friends! Never party alone again!</h2>
    <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored"
       href="<?= $loginUrl ?>" onclick="ga('send', 'event','login','<?= $actualUrl ?>')">
        <span>
        <img alt="Facebook logo" src="<?= PARTYWITHME_UI_URL ?>png/fb_logo.png"/>
            Login with Facebook!
            </span>
    </a>
</div>