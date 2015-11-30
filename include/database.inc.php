<?php

if($_SERVER["SERVER_NAME"] == "other")
	$_MYSQLI = new mysqli("host", "username", "passwd", "dbname");
else
	$_MYSQLI = new mysqli("localhost", "root", "root", "iut_qcmanager");

if ($_MYSQLI->connect_errno) {
	printf("Échec de la connexion : %s\n", $_MYSQLI->connect_error);
	exit();
}

?>