<?php
$hostname = "db";
$username = "admin";
$password = "test";
$database = "database";

// Create connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>