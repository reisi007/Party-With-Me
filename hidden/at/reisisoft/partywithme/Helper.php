<?php
namespace at\eisisoft\partywithme;
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 02.03.2016
 * Time: 19:08
 */


class Helper
{
    /**
     * @param $name string The name of the HTTP form variable
     * @param null $default The default value, or a String representation
     * @return string The variable content as String
     */
    public static function getVariable($name, $default = null)
    {
        $tmp = isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
        $tmp = (string)$tmp;
        return $tmp;
    }

    /**
     * @param $name string The name of the HTTP POST form variable
     * @param null $default The default value, or a String representation
     * @return string The variable content as String
     */
    public static function getPostVariable($name, $default = null)
    {
        $tmp = isset($_POST[$name]) ? $_POST[$name] : $default;
        $tmp = (string)$tmp;
        return $tmp;
    }

    /**
     * @param $name string The name of the HTTP form variable
     * @return boolean true or false
     */
    public static function getVariableAsBoolean($name)
    {
        $tmp = self::getVariable($name, "0");
        return $tmp === "1" || $tmp === "true" ? true : false;
    }

}