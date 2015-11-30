<?php

require_once("include/auth.class.php");

$auth = new Auth();

var_dump($auth->isLogged());

?>

<a href="login.php">log</a>