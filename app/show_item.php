<?php

include 'includes/dbConnect.php';

// Get the item from the URL
$item = isset($_GET['item']) ? $_GET['item'] : '';

if ($item) {
    // Fetch user data from the database
    $stmt = $conn->prepare(
        "SELECT izena, marka, modeloa, serieZenbakia, kokalekua
        FROM INBENTARIOA
        WHERE serieZenbakia = ?");
    $stmt->bind_param("s", $item);

    // Check if the statement is valid
    if ($stmt === false) {
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $itemData = $result->fetch_assoc();
    } else {
        echo "Item not found.";
        exit;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No item specified.";
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
        <h2>Elementuaren datuak</h2>
        <table>
            <tr>
                <th>Izena</th>
                <td><?php echo htmlspecialchars($itemData['izena']); ?></td>
            </tr>
            <tr>
                <th>Marka</th>
                <td><?php echo htmlspecialchars($itemData['marka']); ?></td>
            </tr>
            <tr>
                <th>Modeloa</th>
                <td><?php echo htmlspecialchars($itemData['modeloa']); ?></td>
            </tr>
            <tr>
                <th>Serie Zenbakia</th>
                <td><?php echo htmlspecialchars($itemData['serieZenbakia']); ?></td>
            </tr>
            <tr>
                <th>Kokalekua</th>
                <td><?php echo htmlspecialchars($itemData['kokalekua']); ?></td>
            </tr>
        </table>
        <br>
        <input id="atzera_button" type="button" value="Atzera" onclick="location.href='items.php'">
    </div>
</body>
</html>