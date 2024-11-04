<?php
    include 'includes/dbConnect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $erabiltzailea = trim($_POST['erabiltzailea']);
        $pasahitza = trim($_POST['pasahitza']);

        // Prepare and bind
        $stmt = $conn->prepare("SELECT pasahitza FROM ERABILTZAILEAK WHERE erabiltzailea = ?");
        $stmt->bind_param("s", $erabiltzailea);

        if ($stmt === false) {
            echo "Prepare failed: " . $conn->error;
        }

        // Server-side validation for username and password length
        if (strlen($erabiltzailea) > 250) {
            echo "Erabiltzaile izena ezin da 250 karaktere baino gehiagokoa izan.";
            exit(); // Stop further execution if validation fails
        } 

        if (strlen($pasahitza) > 250) {
            echo "Pasahitza ezin da 250 karaktere baino gehiagokoa izan.";
            exit(); // Stop further execution if validation fails
        }

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify password
            if (password_verify($pasahitza, $row['pasahitza'])) {
                echo "Log in egokia.";
                // Redirect or start session here
            } else {
                echo "Pasahitz okerra.";
            }
        } else {
            echo "Erabiltzailea ez da existitzen.";
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
    <style>
        /* Ensure the body takes the full viewport height */
        body {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Centering container */
        .container {
            text-align: center;
            max-width: 300px; /* Optional: limit max width for styling */
            width: 100%; /* Make responsive */
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column; /* Stack form elements vertically */
            gap: 10px; /* Space between form elements */
        }

        /* Button container styling */
        .button-container {
            display: flex;
            justify-content: space-between; /* Space buttons apart */
            margin-top: 10px;
        }

        /* Style for the registration prompt */
        .register-prompt {
            margin-top: 20px; /* Adds space above the prompt */
            font-size: 14px;
        }
        
        /* Style for the link */
        .register-link {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <form id="login_form" action="login.php" method="post">
        <label for="erabiltzailea">Erabiltzailea:</label>
        <input type="text" id="erabiltzailea" name="erabiltzailea" placeholder="adib.: pepito89" required>
        
        <label for="pasahitza">Pasahitza:</label>
        <input type="password" id="pasahitza" name="pasahitza" placeholder="Sartu zure pasahitza" required>
        
        <div class="button-container">
            <input id="atzera_button" type="button" value="Atzera" onclick="location.href='home.php'">
            <input id="login_submit" type="submit" value="Login">
        </div>
    </form>

    <!-- Registration prompt below the buttons -->
    <p class="register-prompt">
        Ez duzu akonturik? <a href="register.php" class="register-link">Erregistratu</a>
    </p>
</div>

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