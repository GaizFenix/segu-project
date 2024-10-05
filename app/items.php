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
    </style>
</head>
<body>
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
</body>
</html>