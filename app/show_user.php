<?php

include 'includes/dbConnect.php';

// Get the username from the URL
$userNAN = isset($_GET['user']) ? $_GET['user'] : '';

if ($userNAN) {
    // Fetch user data from the database
    $sql = "SELECT erabiltzailea, izenAbizenak, NAN, telefonoa, jaiotzeData, email
            FROM PERTSONAK NATURAL JOIN ERABILTZAILEAK
            WHERE NAN = ?";
    $stmt = $conn->prepare($sql);

    // Check if prepare() was successful
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $userNAN);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        echo "Erabiltzailea ez da aurkitu.";
        exit;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Ez da erabiltzailerik adierazi.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
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
        table {
            border-collapse: collapse;
            width: 50%;
            margin: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Erabiltzailearen datuak</h2>
        <table>
            <tr>
                <th>Erabiltzailea</th>
                <td><?php echo htmlspecialchars($userData['erabiltzailea']); ?></td>
            </tr>
            <tr>
                <th>Izen-abizenak</th>
                <td><?php echo htmlspecialchars($userData['izenAbizenak']); ?></td>
            </tr>
            <tr>
                <th>NAN</th>
                <td><?php echo htmlspecialchars($userData['NAN']); ?></td>
            </tr>
            <tr>
                <th>Telefonoa</th>
                <td><?php echo htmlspecialchars($userData['telefonoa']); ?></td>
            </tr>
            <tr>
                <th>Jaiotze Data</th>
                <td><?php echo htmlspecialchars($userData['jaiotzeData']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($userData['email']); ?></td>
            </tr>
        </table>
        <br>
        <input id="atzera_button" type="button" value="Atzera" onclick="location.href='users.php'">
    </div>
</body>
</html>