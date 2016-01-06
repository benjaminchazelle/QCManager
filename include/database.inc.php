<?php

if($_SERVER["SERVER_NAME"] == "webfront.olympe.in")
	$_MYSQLI = new mysqli("sql2.olympe.in", "msiyjoc5", "kenya777", "msiyjoc5");
else if($_SERVER["SERVER_NAME"] == "benjichaz.125mb.com" || $_SERVER["SERVER_NAME"] == "www.qcmanager.tk" || $_SERVER["SERVER_NAME"] == "qcmanager.tk")
	$_MYSQLI = new mysqli("fdb12.125mb.com", "2027548_iut", "kenya777", "2027548_iut");
else
	$_MYSQLI = new mysqli("localhost", "root", "root", "iut_qcmanager");

if ($_MYSQLI->connect_errno) {
	printf("Échec de la connexion : %s\n", $_MYSQLI->connect_error);
	exit();
}

?>