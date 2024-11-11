<?php
    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: /includes/error.php');
        exit();
    }

include 'includes/dbConnect.php';

// Function to check if serial number is unique
function isSerieZenbakiaUnique($serieZenbakia, $originalSerieZenbakia) {
    global $conn;
    if ($serieZenbakia === $originalSerieZenbakia) {
        // If the serial number has not changed, no need to check
        return true;
    }
    $stmt = $conn->prepare("SELECT serieZenbakia FROM INBENTARIOA WHERE serieZenbakia = ?");
    $stmt->bind_param("s", $serieZenbakia);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows === 0; // Return true if unique
}

$originalSerieZenbakia = isset($_GET['item']) ? $_GET['item'] : '';

// Fetch the current item data from the database
if($originalSerieZenbakia) {
    $stmt = $conn->prepare("SELECT izena, marka, modeloa, serieZenbakia, kokalekua FROM INBENTARIOA WHERE serieZenbakia = ?");
    $stmt->bind_param("s", $originalSerieZenbakia);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $itemData = $result->fetch_assoc();
    } else {
        echo "Elementua ez da aurkitu.";
        exit;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Ez da elementurik adierazi.";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_modify_submit'])) {      
    $izena = trim($_POST['izena']) ?: $itemData['izena']; // Use existing value if empty
    $marka = trim($_POST['marka']) ?: $itemData['marka'];
    $modeloa = trim($_POST['modeloa']) ?: $itemData['modeloa'];
    $serieZenbakia = trim($_POST['serieZenbakia']) ?: $itemData['serieZenbakia'];
    $kokalekua = trim($_POST['kokalekua']) ?: $itemData['kokalekua'];

    // Server-side validation for each field
    if (strlen($izena) == 0 || strlen($izena) > 250) {
        echo "Izena beharrezkoa da eta ezin du 250 karaktere baino gehiago izan.";
        exit();
    }
    
    if (strlen($marka) == 0 || strlen($marka) > 250) {
        echo "Marka beharrezkoa da eta ezin du 250 karaktere baino gehiago izan.";
        exit();
    }
    
    if (strlen($modeloa) == 0 || strlen($modeloa) > 250) {
        echo "Modeloa beharrezkoa da eta ezin du 250 karaktere baino gehiago izan.";
        exit();
    }

    if (strlen($serieZenbakia) == 0 || strlen($serieZenbakia) > 250) {
        echo "Serie Zenbakia beharrezkoa da eta ezin du 250 karaktere baino gehiago izan.";
        exit();
    }
        
    // Check if the serial number is unique (if changed)
    if (!isSerieZenbakiaUnique($serieZenbakia, $originalSerieZenbakia)) {
        echo "Serie zenbakia ez da unikoa.";
        exit();
    } 
    
    $stmt = $conn->prepare("
        UPDATE INBENTARIOA 
        SET izena = ?, marka = ?, modeloa = ?, serieZenbakia = ?, kokalekua = ? 
        WHERE serieZenbakia = ?
    ");

    if ($stmt === false) {
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt->bind_param("ssssss", $izena, $marka, $modeloa, $serieZenbakia, $kokalekua, $originalSerieZenbakia);

    if ($stmt->execute()) {
        echo "Elementua ondo aldatu da!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify item</title>
</head>
<body>
<h2>Modify item</h2>
<form id="item_modify_form" action="modify_item.php?item=<?php echo urlencode($originalSerieZenbakia); ?>" method="post">            
    <label for="izena">Izena:</label>
    <input id="izena" type="text" name="izena" value="<?php echo htmlspecialchars($itemData["izena"]); ?>" ><br/>

    <label for="marka">Marka:</label>
    <input id="marka" type="text" name="marka" value="<?php echo htmlspecialchars($itemData["marka"]); ?>"><br/>

    <label for="modeloa">Modeloa:</label>
    <input id="modeloa" type="text" name="modeloa" value="<?php echo htmlspecialchars($itemData["modeloa"]); ?>"><br/>

    <label for="serieZenbakia">Serie Zenbakia:</label>
    <input id="serieZenbakia" type="text" name="serieZenbakia" value="<?php echo htmlspecialchars($itemData["serieZenbakia"]); ?>"><br/>

    <label for="kokalekua">Kokalekua:</label>
    <input id="kokalekua" type="text" name="kokalekua" value="<?php echo htmlspecialchars($itemData["kokalekua"]); ?>"><br/>
    
    <br>
    <div class="button-container">
        <input id="item_modify_submit" type="submit" name="item_modify_submit" value="Gorde">
        <input id="atzera_button" type="button" value="Atzera" onclick="location.href='items.php'">
    </div>
</form>

<style>
    .button-container {
        display: flex;
        align-items: center;
    }
    #atzera_button {
        margin-left: 2cm; /* Adjust the value as needed */
    }
</style>

<script>
    // Izena field
    document.getElementById('izena').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });
</script>

<script>
    // Marka field
    document.getElementById('marka').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });
</script>

<script>
    // Modeloa field
    document.getElementById('modeloa').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });
</script>

<script>
    // Serie Zenbakia field | 
    document.getElementById('serieZenbakia').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });
</script>

<script>
    // Kokalekua field | EZ BEHARREZKOA DB-an
    document.getElementById('kokalekua').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });
</body>
</html>