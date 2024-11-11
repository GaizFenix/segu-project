<?php
    session_start();
    
    // Check if user is logged in
    if (isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == true) {
        header('Location: ../home.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            text-align: center;
            padding: 50px;
        }
        .container {
            border: 1px solid #f5c6cb;
            background-color: #f8d7da;
            padding: 20px;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Error</h1>
        <p>Saioa hasi behar duzu atal honetara sartzeko:</p>
        <a href="../login.php">Saioa hasi</a>
    </div>
</body>
</html>