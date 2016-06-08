<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 05.03.2016
 * Time: 09:12
 */
namespace at\eisisoft\partywithme\api;

require_once "constants.php";
session_start();
header('Content-Type: application/json');
/**
 * @param $pstmt \PDOStatement A prepated statement
 */
function toJSON($pstmt)
{
    $pstmt->execute();
    echo json_encode($pstmt->fetchAll(\PDO::FETCH_NAMED));
}
?>