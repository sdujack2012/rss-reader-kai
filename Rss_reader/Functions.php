<?php

include 'configReader.php';

/**
 * return a mysqli object for database access
 */
function getDatabaseConnection() {
    $dbconfig = new configReader("db.config");
    $mysqli = new mysqli($dbconfig->config->host, $dbconfig->config->username, $dbconfig->config->password, $dbconfig->config->dbname);
    echo mysql_errno();
    return $mysqli;
}

?>