<?php
use function at\eisisoft\partywithme\api\getFacebookAPI;
use function at\eisisoft\partywithme\api\isFbLogin;

require_once "constants.php" ?>
<?php
session_start();
if (!(isset($jsConst) && is_array($jsConst))) {
    $jsConst = [];
}
$jsConst['PWM_API_URL'] = '"' . PARTYWITHME_API_URL . '"';
$jsConst['PWM_UI_URL'] = '"' . PARTYWITHME_UI_URL . '"';
$jsConst['PWM_UI_TEMPLATE_URL'] = '"' . PARTYWITHME_TEMPLATE_URL . '"';
$jsConst ['FB_APP_ID'] = FB_APP_ID;
$jsConst['GA_UA_ID'] = '"' . GA_UA_ID . '"';

if (!(isset($css) && is_array($css))) {
    $css = [];
}
$css[] = PARTYWITHME_UI_URL . 'res/dialog-polyfill.css';
$css[] = PARTYWITHME_UI_URL . 'res/pwm.css';
//Check FB login
$fb = getFacebookAPI();
$FB_LOGIN_VALID = isFbLogin();
if (is_null($FB_LOGIN_VALID)) {
    $jsConst['PWM_USER_ID'] = 'undefined';
} else {
    $jsConst['PWM_USER_ID'] = $FB_LOGIN_VALID[FB_USER_ID_KEY];
}

$cookieName = 'pwmCookieWarningSeen';
if (!isset($_COOKIE[$cookieName])) {
    setcookie($cookieName, time(), time() + (60 * 60 * 24 * 365 * 3), '/', PARTYWITHME_DOMAIN);
    $updateCookie = true;
} else {
    $updateCookie = false;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
<head>
    <link rel="manifest" href="<?= PARTYWITHME_UI_URL ?>manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="<?= primary_color ?>">
    <link rel="icon" sizes="192x192" href="<?= PARTYWITHME_UI_URL ?>icon_192.png">
    <meta property="og:title" content="Party with me!"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?php
    if (isset($ogUrl))
        $ogUrl = PARTYWITHME_UI_URL . $ogUrl;
    else {
        $ogUrl = PARTYWITHME_UI_URL;
        $tmp = $_SERVER['REQUEST_URI'];
        if (strlen($tmp) > 1) {
            $i = 0;
            while ($tmp[$i] === '/') {
                $i++;
            }
            $ogUrl .= substr($tmp, $i);
        }
    }
    echo $ogUrl;
    ?>">
    <meta property="og:image" content="<?= PARTYWITHME_UI_URL ?>png/static/v1.png"/>
    <?php if (isset($description)) { ?>
        <meta name="description"
              content="<?= $description ?>
         ">
    <?php } ?>
    <link rel="icon" type="image/png" href="<?= PARTYWITHME_UI_URL ?>icon_96.png"/>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/<?= MDL_VERSION ?>/material.blue-indigo.min.css">
    <?php
    foreach ($css as $c)
        echo "<link rel='stylesheet' href='$c'>";
    ?>
    <script type="text/javascript">
        <?php
        foreach ($jsConst as $key => $value) {
            echo "Object.defineProperty(window, '$key', {'value': $value, 'writable': false});";
        }
        ?>
    </script>
    <!--Google Analytics-->
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o), m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
        ga('create', GA_UA_ID, 'auto');
        ga('send', 'pageview');
    </script>
    <?php
    // Add scripts
    $s = [];
    if (isset($scripts) && is_array($scripts)) {
        $scripts = array_merge($scripts, $s);

    } else {
        $scripts = $s;
    }

    foreach ($scripts as $cur)
        echo "<script src='$cur'></script>\n";
    ?>
    <script defer src="https://code.getmdl.io/<?= MDL_VERSION ?>/material.min.js"></script>

    <?php
    if (isset($title)) {
        echo "<title>$title</title>";
    }
    ?>
</head>
<body ng-app="partyWithMe">
<?php require_once "ui/header.php" ?>
<main class="mdl-layout__content">
    <div class="page-content">
        <noscript><h1>You need JavaScript enabled for this website!</h1></noscript>
        <div id="fb-root"></div>