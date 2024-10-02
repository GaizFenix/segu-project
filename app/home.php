<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
        }
        .container button {
            display: block;
            width: 200px;
            margin: 10px auto;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <button onclick="location.href='register.php'">Erregistroa</button>
        <button onclick="location.href='login.php'">Identifikazioa</button>
        <button onclick="location.href='users.php'">Erabiltzaile guztien ikuskaketa</button>
        <button onclick="location.href='add_item.php'">Elementuen gehikuntza</button>
        <button onclick="location.href='items.php'">Elementu guztien ikuskaketa</button>
        <button onclick="location.href='modify_item.php'">Elementuen datuen aldaketa</button>
        <button onclick="location.href='delete_item.php'">Elementuen ezabaketa</button>
        <button onclick="location.href='includes/phpinfo.php'">PHP Info</button>
    </div>
</body>
</html>