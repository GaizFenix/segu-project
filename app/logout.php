<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>  
    <div style="text-align: center;">
        <p>5 minutuak pasatu egin dira, hasi berriro saioa</p>
        <a href="/login.php">Saioa hasi</a>
    </div>
</body>
</html>
