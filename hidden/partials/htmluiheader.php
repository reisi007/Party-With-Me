<?php
require_once "constants.php";
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 05.03.2016
 * Time: 16:26
 */
$title = "Party with me! - Never party alone again";
$s = [
    "https://ajax.googleapis.com/ajax/libs/angularjs/" . ANGULAR_VERSION . "/angular.min.js",
    PARTYWITHME_UI_URL . 'res/partywithme.min.js'
];
if (isset($scripts) && is_array($scripts)) {
    $scripts = array_merge($s, $scripts);
} else {
    $scripts = $s;
}
require_once "htmlheader.php";
?>