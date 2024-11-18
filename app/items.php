<?php
    session_start();
    
    // Check if user is logged in
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: /includes/error.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Items</title>
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
        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            width: 80px;
            height: 80px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .logout-button img {
            width: 32px;
            height: 32px;
            margin-bottom: 5px;
            filter: invert(100%);
        }
        .logout-button span {
            font-weight: bold;
        }
        .logout-button:hover {
            background-color: #ff1a1a;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
            text-align: center;
        }
        .modal-content button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <button class="logout-button" onclick="showModal()">
        <img src="includes/user-icon.png" alt="User Icon">
        <span>Saioa itxi</span>
    </button>

    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <p>Oraintxe bertan <?php echo htmlspecialchars($username); ?> bezala identifikatuta zaude. Irten nahi duzu?</p>
            <form method="post" action="">
                <button type="submit" name="logout" value="yes">Bai</button>
                <button type="button" onclick="hideModal()">Ez</button>
            </form>
        </div>
    </div>

    <table>
        <tr>
            <th>Item</th>
        </tr>
        <?php

        include 'includes/dbConnect.php';

        // Fetch users from the database
        $sql = "SELECT izena, marka, modeloa, serieZenbakia FROM INBENTARIOA";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $izena = $row['izena'];
                $marka = $row['marka'];
                $modeloa = $row['modeloa'];
                $serieZenbakia = $row['serieZenbakia'];
                echo "<tr>";
                echo "<td>" . htmlspecialchars($izena) . " | " . htmlspecialchars($marka) . " " . htmlspecialchars($modeloa) . "<br>" . htmlspecialchars($serieZenbakia) . "</td>";
                echo "<td><a href='show_item.php?item=" . urlencode($serieZenbakia) . "'><button>Show</button></a></td>";
                echo "<td><a href='modify_item.php?item=" . urlencode($serieZenbakia) . "'><button>Edit</button></a></td>";
                echo "<td><a href='delete_item.php?item=" . urlencode($serieZenbakia) . "'><button>Delete</button></a></td>";
                echo "</tr>";
            }
            echo "<tr><td></td><td></td><td></td>";
            echo "<td><a href='home.php" . "'><button>Atzera</button></a></td>";
            echo "</tr>";
        } else {
            echo "<tr><td colspan='3'>No items found</td></tr>";
            echo "<tr>";
            echo "<td><a href='home.php" . "'><button>Atzera</button></a></td>";
            echo "</tr>";
        }
        ?>
        
    </table>

    <script>
        function showModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function hideModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }
    </script>
</body>
</html>