<?php

if($_SERVER["SERVER_NAME"] == "webfront.olympe.in")
	$_MYSQLI = new mysqli("sql2.olympe.in", "msiyjoc5", "kenya777", "msiyjoc5");
else
	$_MYSQLI = new mysqli("localhost", "root", "root", "iut_qcmanager");

if ($_MYSQLI->connect_errno) {
	printf("Échec de la connexion : %s\n", $_MYSQLI->connect_error);
	exit();
}

?>