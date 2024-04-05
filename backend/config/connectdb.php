<?php
date_default_timezone_set("Africa/Lagos");
//Database Connection to CardifyNG
$server= 'localhost';
$username= 'root';
$password= '';
$dbname= 'stellar';

$connect= mysqli_connect($server,$username,$password,$dbname);
?>