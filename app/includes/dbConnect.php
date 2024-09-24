<?php
$hostname = "localhost"; // or your database host
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Create connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>