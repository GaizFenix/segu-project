<?php
    session_start();
    
    // Check if user is logged in
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: /includes/error.php');
        exit();
    }
    
    include 'includes/dbConnect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $izena = trim($_POST['izena']);
        $marka = trim($_POST['marka']);
        $modeloa = trim($_POST['modeloa']);
        $serieZenbakia = trim($_POST['serieZenbakia']);
        $kokalekua = trim($_POST['kokalekua']);

        // Server-side validation for each field
        if (strlen($izena) == 0 || strlen($izena) > 250) {
            $message = "Izena beharrezkoa da eta ezin du 250 karaktere baino gehiago izan.";
            exit();
        }
        
        if (strlen($marka) == 0 || strlen($marka) > 250) {
            $message = "Marka beharrezkoa da eta ezin du 250 karaktere baino gehiago izan.";
            exit();
        }
        
        if (strlen($modeloa) == 0 || strlen($modeloa) > 250) {
            $message = "Modeloa beharrezkoa da eta ezin du 250 karaktere baino gehiago izan.";
            exit();
        }

        if (strlen($serieZenbakia) == 0 || strlen($serieZenbakia) > 250) {
            $message = "Serie Zenbakia beharrezkoa da eta ezin du 250 karaktere baino gehiago izan.";
            exit();
        }

        // Insert data into the database using prepared statements
        $stmt = $conn->prepare("INSERT INTO INBENTARIOA (izena, marka, modeloa, serieZenbakia, kokalekua) VALUES (?, ?, ?, ?, ?)");

        if ($stmt === false) {
            $message = "Prepare failed: " . $conn->error;
        }

        $stmt->bind_param("sssss", $izena, $marka, $modeloa, $serieZenbakia, $kokalekua);

        if ($stmt->execute()) {
            $message = "Elementua ondo gorde da!";
        } else {
            $message = "Error: " . $stmt->error;
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
    <style>
        /* Centering the body */
        body {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Container to hold the centered content */
        .container {
            text-align: center;
            max-width: 300px; /* Optional max width for styling */
            width: 100%;
        }

        /* Style each form row */
        .form-row {
            margin-bottom: 15px;
            text-align: left;
        }

        /* Style the button container */
        .button-container {
            display: flex;
            justify-content: center; /* Center the button horizontally */
            width: 100%;
        }

        /* Style the form elements */
        form label, form input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        /* Style the submit button */
        .button-container input[type="submit"] {
            width: auto; /* Allow the button to be smaller */
            padding: 5px 10px; /* Adjust padding for a smaller button */
            font-size: 14px; /* Adjust font size for a smaller button */
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Item</h2>
        <form action="add_item.php" method="post">
            <label for="izena">Izena:</label>
            <input type="text" id="izena" name="izena" required>

            <label for="marka">Marka:</label>
            <input type="text" id="marka" name="marka" required>

            <label for="modeloa">Modeloa:</label>
            <input type="text" id="modeloa" name="modeloa" required>

            <label for="serieZenbakia">Serie Zenbakia:</label>
            <input type="text" id="serieZenbakia" name="serieZenbakia" required>

            <label for="kokalekua">Kokalekua:</label>
            <input type="text" id="kokalekua" name="kokalekua">

            <div class="button-container">
                <input type="submit" value="Gehitu">
            </div>
        </form>
        <div style="margin-top: 20px;">
            <?php echo $message; ?>
        </div>
    </div>
</body>

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
        let timeout=null;
        let interval=null;

        document.addEventListener('mousemove', () => {
            if(timeout!==null){
                clearTimeout(timeout);
            }
            if(interval!==null){
                clearInterval(interval);
            }

            timeout= setTimeout(function() {
                let timer=300;

                interval=setInterval(function() {
                    timer--;
                    if(timer===-1){
                        clearInterval(interval);
                        window.location.href='logout.php';
                    }
                }, 1000);
                
            }, 100);
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