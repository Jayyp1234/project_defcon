<?php
date_default_timezone_set("Africa/Lagos");
//Database Connection to CardifyNG
$server= 'localhost';
$username= 'cardifyc_blog';
$password= 'AS+4~bZEYLJ6';
$dbname= 'cardifyc_blog';


// $username= 'root';
// // $password = '';
// $password= '';
// $dbname= 'cardifyblog';

$connect= mysqli_connect($server,$username,$password,$dbname);
?>