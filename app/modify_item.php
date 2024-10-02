<?php

include 'includes/dbConnect.php';

//See if the new serial number is unique
function isSerieZenbakiaUnique($serieZenbakia) {
    global $conn;
    $stmt = $conn->prepare("SELECT serieZenbakia FROM INBENTARIOA WHERE serieZenbakia = ?");
    $stmt->bind_param("s", $serieZenbakia);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows === 0;
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
        echo "Item not found.";
        exit;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No item specified.";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_modify_submit'])) {      
    $izena = $_POST['izena'];
    $marka = $_POST['marka'];
    $modeloa = $_POST['modeloa'];
    $serieZenbakia = $_POST['serieZenbakia'];
    $kokalekua = $_POST['kokalekua'];
    $originalSerieZenbakia = $_GET['item'];
    
    // PENDING TO CHANGE SO THAT THE USER HAS TO INPUT THINGS
    // Check if any of the POST values are null and replace them with the current database values
    if (empty($izena)) {
        $izena = $itemData['izena'];
    }
    if (empty($marka)) {
        $marka = $itemData['marka'];
    }
    if (empty($modeloa)) {
        $modeloa = $itemData['modeloa'];
    }
    if (empty($serieZenbakia)) {
        $serieZenbakia = $itemData['serieZenbakia'];
    }
    if (empty($kokalekua)) {
        $kokalekua = $itemData['kokalekua'];
    }
    $originalSerieZenbakia = $_GET['item'];
        
    // See if serial number is unique and change the data in the database
    if (!isSerieZenbakiaUnique($serieZenbakia) && $originalSerieZenbakia !== $serieZenbakia) {
        echo "Serie zenbakia ez da unikoa.";
        exit;
    } else {
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
            echo "Item data updated successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
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
    <label for="Izena">Izena:</label>
    <input id="Izena" type="text" name="Izena" value="Sartu izen berria" ><br/>

    <label for="marka">Marka:</label>
    <input id="marka" type="text" name="marka" value="<?php echo htmlspecialchars($itemData["marka"]); ?>"><br/>

    <label for="modeloa">Modeloa:</label>
    <input id="modeloa" type="text" name="modeloa" value="<?php echo htmlspecialchars($itemData["modeloa"]); ?>"><br/>

    <label for="serieZenbakia">Serie Zenbakia:</label>
    <input id="serieZenbakia" type="text" name="serieZenbakia" value="<?php echo htmlspecialchars($itemData["serieZenbakia"]); ?>"><br/>

    <label for="kokalekua">Kokalekua:</label>
    <input id="kokalekua" type="text" name="kokalekua" value="<?php echo htmlspecialchars($itemData["kokalekua"]); ?>"><br/>
    
    <input id="item_modify_submit" type="submit" name="item_modify_submit" value="Gorde"><br/>
</form>

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

       
        
