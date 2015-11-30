<?php

require_once("../include/database.inc.php");

$sql = file_get_contents("query.sql");

$update = $_MYSQLI->multi_query($sql);

print_r($update);
?>