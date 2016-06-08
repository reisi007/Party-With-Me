<?php
namespace at\eisisoft\partywithme\api;

/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 11.03.2016
 * Time: 08:56
 */

define('FB_APP_ID', '12345678910'); //Change
define('FB_APP_SECRET', 'exampleSecret'); //Change
define('FB_GRAPH_VERSION', 'v2.5');
// TODO PHP7 define(...)
function getFBPermissions()
{
    return ['user_birthday', 'user_events', 'user_relationship_details'];
}

?>