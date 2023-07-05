<?php

include './connectdb.php';
//config.php
include_once './vendor/autoload.php';
//call all credentials

$credentials= [];
$query = mysqli_query($connect,"SELECT * FROM googleapidetails");
if($query){
    $credentials = mysqli_fetch_assoc($query);
}

//Include Google Client Library for PHP autoload filere

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId($credentials['client_id']);

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret($credentials['client_secret']);

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri($credentials['redirectUrl']);

//
$google_client->addScope('email');

$google_client->addScope('profile');
