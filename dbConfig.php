<?php
session_start(); 
// config.php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "uofu_parking_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>