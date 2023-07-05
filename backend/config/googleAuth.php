<?php
session_start();

include 'google-auth-api.php';




$_SESSION['TYPE'] = null;


$type = $_REQUEST['type'];

$_SESSION['type'] = $type;

echo ($google_client->createAuthUrl());

?>