<?php
// connect.php 
$server = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'echorooms';

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die('Error: could not establish database connection');
} 

if (!mysqli_select_db($conn, $database)) {
    die('Error: could not select the database');
}