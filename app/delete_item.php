<?php

include 'includes/dbConnect.php';

// Get the item from the URL
$item = isset($_GET['item']) ? $_GET['item'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        // Perform the deletion from the database
        $itemToDelete = isset($_POST['item']) ? $_POST['item'] : '';
        if ($itemToDelete) {
            $stmt = $conn->prepare("DELETE FROM INBENTARIOA WHERE serieZenbakia = ?");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("s", $itemToDelete);
            $stmt->execute();
            $stmt->close();
        }
    }
    // Redirect to items.php after handling the form submission
    header("Location: items.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Item</title>
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
        .message {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .buttons button {
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            Ziur zaude <?php echo htmlspecialchars($item); ?> serie zenbakidun elementua ezabatu nahi duzula?
        </div>
        <div class="buttons">
            <form action="delete_item.php?item=<?php echo urlencode($item); ?>" method="post">
                <input type="hidden" name="item" value="<?php echo htmlspecialchars($item); ?>">
                <button type="submit" name="confirm" value="yes">BAI</button>
            </form>
            <form action="items.php" method="get">
                <button type="submit">EZ</button>
            </form>
        </div>
    </div>
</body>
</html>