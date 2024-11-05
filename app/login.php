<?php
    session_start();
    include 'includes/dbConnect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $erabiltzailea = trim($_POST['erabiltzailea']);
        $pasahitza = trim($_POST['pasahitza']);
        $ip_address = $_SERVER['REMOTE_ADDR'];

        // Check if the user has exceeded the maximum number of failed attempts
        $stmt = $conn->prepare("SELECT failed_attempts, lockout_until FROM FAILED_LOGINS WHERE ip_address = ?");
        $stmt->bind_param("s", $ip_address);

        if ($stmt === false) {
            echo "Prepare failed: " . $conn->error;
        }
        
        $stmt->execute();
        $stmt->bind_result($failed_attempts, $lockout_until);
        $stmt->fetch();
        $stmt->close();

        // If the IP address is not in the database, insert a new row with failed_attempts set to 0
        if ($failed_attempts === null) {
            $failed_attempts = 0;
            $stmt = $conn->prepare("INSERT INTO FAILED_LOGINS (ip_address, failed_attempts) VALUES (?, ?)");
            $stmt->bind_param("si", $ip_address, $failed_attempts);
            $stmt->execute();
            $stmt->close();
        }

        if ($lockout_until && strtotime($lockout_until) > time()) {
            echo "Too many failed login attempts. Please try again after 5 minutes.";
            exit();
        }

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

                // Reset failed attempts on successful login
                $stmt = $conn->prepare("DELETE FROM FAILED_LOGINS WHERE ip_address = ?");
                $stmt->bind_param("s", $ip_address);
                $stmt->execute();
                $stmt->close();

                // Redirect to the home page
                header('Location: home.php');
                exit();
            } else {
                echo "Pasahitz okerra.";
                // Increment failed attempts
                if ($failed_attempts >= 2) {
                    // Lockout the IP address for 5 minutes
                    $lockout_until = date("Y-m-d H:i:s", strtotime("+5 minutes"));
                    $stmt = $conn->prepare("INSERT INTO FAILED_LOGINS (ip_address, failed_attempts, lockout_until) VALUES (?, 3, ?) ON DUPLICATE KEY UPDATE failed_attempts = failed_attempts + 1, lockout_until = ?");
                    $stmt->bind_param("ss", $lockout_until, $ip_address);
                } else {
                    $stmt = $conn->prepare("INSERT INTO FAILED_LOGINS (ip_address, failed_attempts) VALUES (?, 1) ON DUPLICATE KEY UPDATE failed_attempts = failed_attempts + 1, last_attempt = CURRENT_TIMESTAMP");
                    $stmt->bind_param("s", $ip_address);
                }
                $stmt->execute();
                $stmt->close();
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