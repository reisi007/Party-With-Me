<?php
namespace at\eisisoft\partywithme\admin;

use at\eisisoft\partywithme\Helper;
use at\eisisoft\partywithme\MySQLHelper;

require_once "../partials/htmlheader.php"; ?>
    <?php

function runSql($sql)
{
    if (MySQLHelper::executeQuietly($sql)) {
        echo "<h3>Sucess</h3>";
    } else {
        echo "<h3>Error</h3>";
    }
}

/**
 * @param $array array
 */
function arrayOfArrayToHtml($array)
{
    if (empty($array)) return false;
    $keys = array_keys($array[0]);
    if (count($keys) === 0) {
        return false;
    }
    $html = "<table class=\"mdl-data-table mdl-js-data-table mdl-shadow--2dp\">";
    $html .= "<thead><tr>";

    foreach ($keys as $k) {
        $html .= "<th class=\"mdl-data-table__cell--non-numeric\">$k</th>";
    }
    $html .= "</tr></thead><tbody>";
    $html .= "<tr>";
    foreach ($array as $row) {
        foreach ($keys as $cur) {
            $field = $row[$cur];
            $html .= "<td class=\"mdl-data-table__cell--non-numeric\">$field</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</tbody></table>";
    return $html;
}

$create = 'create';
$dumpDatabase = 'dumpDatabase';
$vars = [];
$vars[$create] = Helper::getVariableAsBoolean($create);
$connection = MySQLHelper::getConnection();

$vars[$dumpDatabase] = Helper::getVariableAsBoolean($dumpDatabase);
if (PARTYWITHME_DOMAIN !== 'partywithme.club') {
    if ($vars[$dumpDatabase]) {
        echo "<h2>Dumping Database</h2>";
        $sql = file_get_contents("../resources/sql/admin/dropall.sql", FILE_TEXT);
        echo nl2br($sql);
        runSql($sql);
    }
    if ($vars[$create]) {
        echo "<h2>Creating DB Schema</h2>";
        $sql = file_get_contents("../resources/sql/admin/createAll.sql", FILE_TEXT);
        echo nl2br($sql);
        runSql($sql);
    }
}
?>
    <form id="form" action="index.php" method="get">
        <?php if (PARTYWITHME_DOMAIN !== 'partywithme.club') { ?>
            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="cbCreate">
                <input type="checkbox" id="cbCreate" name="create" value="1"
                       class="mdl-checkbox__input"/>
                <span class="mdl-checkbox__label">Execute SQL DDL</span>
            </label>
            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="cbDrop">
                <input type="checkbox" id="cbDrop" name="dumpDatabase" value="1" class="mdl-checkbox__input">
                <span class="mdl-checkbox__label">Dump Database before creating</span>
            </label>
        <?php } ?>
        <button autofocus type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
        >Execute query
        </button>

    </form>

    <?php
$sql = file_get_contents("../resources/sql/admin/stats.sql");

$queries = [$sql, 'SELECT id,firstName,lastName FROM users LIMIT 500', 'SELECT id, name, place, starttime FROM events LIMIT 500'];
foreach ($queries as $sql) {
    $result = MySQLHelper::executeQuery($connection, $sql);
    if ($result === null) {
        echo '<h1>Nothing to show here</h1>';
    } else {
        $res = $result->fetchAll(\PDO::FETCH_ASSOC);
        $output = arrayOfArrayToHtml($res);
        echo "<p>$output</p>";
    }
}

?>
<?php require_once "../partials/htmlfooter.php" ?>