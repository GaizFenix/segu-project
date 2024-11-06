<?php
    date_default_timezone_set('Europe/Madrid');
    $config = include('config.php');

    $hostname = $config['hostname'];
    $username = $config['username'];
    $password = $config['password'];
    $database = $config['database'];

    // Create connection
    $conn = mysqli_connect($hostname, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>