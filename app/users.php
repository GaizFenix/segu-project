<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Users</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 50%;
            margin: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>Username</th>
        </tr>
        <?php

        include 'includes/dbConnect.php';

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch users from the database
        $sql = "SELECT erabiltzailea, NAN FROM ERABILTZAILEAK";
        $result = $conn->query($sql);

        if ($result === false) {
            die('Query failed: ' . htmlspecialchars($conn->error));
        }

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $erabiltzailea = $row['erabiltzailea'];
                $NAN = $row['NAN'];
                echo "<tr>";
                echo "<td>" . htmlspecialchars($erabiltzailea) . "</td>";
                echo "<td><a href='show_user.php?user=" . urlencode($NAN) . "'><button>Show</button></a></td>";
                echo "<td><a href='modify_user.php?user=" . urlencode($NAN) . "'><button>Edit</button></a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No users found</td></tr>";
        }
        ?>
        
    </table>
</body>
</html>