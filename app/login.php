<?php
    include 'includes/dbConnect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $erabiltzailea = $_POST['erabiltzailea'];
        $pasahitza = $_POST['pasahitza'];

        // Prepare and bind
        $stmt = $conn->prepare("SELECT pasahitza FROM ERABILTZAILEAK WHERE erabiltzailea = ?");
        $stmt->bind_param("s", $erabiltzailea);

        if ($stmt === false) {
            echo "Prepare failed: " . $conn->error;
        }

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify password
            if (password_verify($pasahitza, $row['pasahitza'])) {
                echo "Login successful!";
                // Redirect or start session here
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with that username.";
        }

        // Close the statement
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>
<form id="login_form" action="login.php" method="post">
    <label for="erabiltzailea">Erabiltzailea:</label>
    <input type="text" id="erabiltzailea" name="erabiltzailea" placeholder="adib.: pepito88" required><br>
    <label for="pasahitza">Pasahitza:</label>
    <input type="password" id="pasahitza" name="pasahitza" placeholder="Sartu zure pasahitza" required><br>
    
    <br>
    <div class="button-container">
        <input id="login_submit" type="submit" value="Login">
        <input id="atzera_button" type="button" value="Atzera" onclick="location.href='home.php'">
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
    document.getElementById('erabiltzailea').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        // Allow a maximum of 250 characters
        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });
</script>

</body>
</html>