<?php
    include 'includes/dbConnect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $izena = $_POST['izena'];
        $marka = $_POST['marka'];
        $modeloa = $_POST['modeloa'];
        $serieZenbakia = $_POST['serieZenbakia'];
        $kokalekua = $_POST['kokalekua'];

        // Insert data into the database using prepared statements | CHECK WITH DATABASE
        $stmt = $conn->prepare("INSERT INTO INBENTARIOA (izena, marka, modeloa, serieZenbakia, kokalekua) VALUES (?, ?, ?, ?, ?)");

        if ($stmt === false) {
            echo "Prepare failed: " . $conn->error;
        }

        $stmt->bind_param("sssss", $izena, $marka, $modeloa, $serieZenbakia, $kokalekua);

        if ($stmt->execute()) {
            echo "Registration successful!";
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
    <title>Add Item</title>
</head>
<body>

<h2>Add Item</h2>
<form id="item_add_form" action="add_item.php" method="post">
    <label for="izena">Izena (erakundearena):</label>
    <input type="text" id="izena" name="izena" placeholder="adib.: Mikrofonoa" required><br>

    <label for="marka">Marka:</label>
    <input type="text" id="marka" name="marka" placeholder="adib.: Shure" required><br>

    <label for="modeloa">Modeloa:</label>
    <input type="text" id="modeloa" name="modeloa" placeholder="adib.: SM-48" required><br>

    <label for="serieZenbakia">Serie Zenbakia:</label>
    <input type="text" id="serieZenbakia" name="serieZenbakia" placeholder="adib.: 0000ABC" required><br>

    <label for="kokalekua">Kokalekua:</label>
    <input type="text" id="kokalekua" name="kokalekua" placeholder="adib.: Kolaboragailuak 2 setup-ean" required><br>

    <input id="item_add_submit" type="submit" value="Txertatu">
</form>

<!-- THE NECESSARY FIELDS MUST BE FULL | MAX LENGTH OF 250 CHARS -->
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