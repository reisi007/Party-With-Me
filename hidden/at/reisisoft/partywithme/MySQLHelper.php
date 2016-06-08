<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 04.03.2016
 * Time: 09:45
 */

namespace at\eisisoft\partywithme;

class MySQLHelper
{
    /**
     * Tries to create a connection to the defined MySQL database
     * @return \pdo
     * @throws \PDOException
     */
    public static function getConnection()
    {
        require "mysql.sec.php";
        if (!empty($mysql)) {
            if (isset($mysql_host) && isset($mysql_user) && isset($mysql_password) && isset($mysql_db) && isset($mysql_port)) {

                $connection = new \PDO('mysql:host=' . $mysql[$mysql_host] . ':' . $mysql[$mysql_port] . ';dbname=' . $mysql[$mysql_db] . ';charset=utf8mb4',
                    $mysql[$mysql_user], $mysql[$mysql_password]);
                $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return $connection;
            }
        }
    }

    /**
     * @param $connection \pdo A opened connection
     * @param $sql string A SQL query
     * @return \PDOStatement|null
     */
    public static function executeQuery($connection, $sql)
    {
        try {
            return $connection->query($sql);
        } catch (\PDOException $e) {
            return null;
        }
    }

    /** Executes an SQL query and returns false if an error occured
     * @param $sql string The SQL statement
     * @return bool If execution returned an error
     */
    public static function executeQuietly($sql)
    {
        try {
            $con = self::getConnection();
            $con->exec($sql);
        } catch (\PDOException $e) {
            var_dump($e);
            return false;
        }
        return true;
    }
}