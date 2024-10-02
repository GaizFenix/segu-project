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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_modify_submit'])) {      
        $izena = $_POST['Izena'];
        $marka = $_POST['marka'];
        $modeloa = $_POST['modeloa'];
        $serieZenbakia = $_POST['serieZenbakia'];
        $kokalekua = $_POST['kokalekua'];
        $originalSerieZenbakia = $_POST['originalSerieZenbakia']; // Add a hidden input field in the form to hold the original serieZenbakia
        
        //See if serial number is unique and change the data in the database
        if (!isSerieZenbakiaUnique($serieZenbakia)) {
            echo "Serie zenbakia ez da unikoa.";
            exit;
        }else{
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
<form id="item_modify_form" action="modify_item.php?user=<?php echo urlencode($erabiltzailea); ?>" method="post">            
    <label for="Izena">Izen:</label>
    <input id="Izena" type="text" name="Izena" placeholder="Sartu izen berria" required>

    <label for="marka">Marka:</label>
    <input id="marka" type="text" name="marka" placeholder="Sartu marka berria" required>

    <label for="modeloa">Modeloa:</label>
    <input id="modeloa" type="text" name="modeloa" placeholder="Sartu modelo berria" required>

    <label for="serieZenbakia">SerieZenbakia:</label>
    <input id="serieZenbakia" type="text" name="serieZenbakia" placeholder="Sartu serie zenbakia berria" required>

    <label for="kokalekua">Kokalekua:</label>
    <input id="kokalekua" type="text" name="kokalekua" placeholder="Sartu kokaleku berri" required>
            
    <label  for="originalSerieZenbakia">Original SerieZenbakia:</label>
    <input id="originalSerieZenbakia" type="text" name="originalSerieZenbakia" value="<?php echo htmlspecialchars($itemData['serieZenbakia']); ?>">
    
    <input id="item_modify_submit" type="submit" name="item_modify_submit" value="save">
</form>

<script>
    // Izena field
    document.getElementById('izena').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        if (value.length > 0) {
            input.setCustomValidity('');
        } else {
            input.setCustomValidity('Izena beharrezkoa da.');
        }

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

        if (value.length > 0) {
            input.setCustomValidity('');
        } else {
            input.setCustomValidity('Marka beharrezkoa da.');
        }

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

        if (value.length > 0) {
            input.setCustomValidity('');
        } else {
            input.setCustomValidity('Modeloa beharrezkoa da.');
        }

        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });
</script>

<script>
    // Serie Zenbakia field
    document.getElementById('serieZenbakia').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        if (value.length > 0) {
            input.setCustomValidity('');
        } else {
            input.setCustomValidity('Serie Zenbakia beharrezkoa da.');
        }

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

       
        
