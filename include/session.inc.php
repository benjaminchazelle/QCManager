<?php

if($_SERVER["SERVER_NAME"] == "qcmanager.tk") {
	header('Status: 301 Moved Permanently', false, 301);   
	header("Location: http://www.qcmanager.tk".$_SERVER["REQUEST_URI"]);
	exit;
}

session_start();

?>