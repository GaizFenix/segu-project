<?php
    session_start();
    include 'includes/dbConnect.php';

    $ip_address = $_SERVER['REMOTE_ADDR'];
    $wait_time_seconds = 10; // 5 minutes in seconds

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $erabiltzailea = trim($_POST['erabiltzailea']);
        $pasahitza = trim($_POST['pasahitza']);

        // Clear any previous error message
        unset($_SESSION['error_message']);

        // Check current failed attempts and lockout time
        $stmt = $conn->prepare("SELECT failed_attempts, lockout_until FROM FAILED_LOGINS WHERE ip_address = ?");
        $stmt->bind_param("s", $ip_address);
        $stmt->execute();
        $stmt->bind_result($failed_attempts, $lockout_until);
        $stmt->fetch();
        $stmt->close();

        // Check if lockout time has expired
        if ($lockout_until && strtotime($lockout_until) > time()) {
            // Lockout is still active, so calculate remaining time
            $remaining_time = strtotime($lockout_until) - time();
            $_SESSION['remaining_time'] = $remaining_time;
            header("Location: login.php");
            exit();
        } elseif ($lockout_until && strtotime($lockout_until) <= time()) {
            // Lockout has expired, reset failed attempts and lockout time
            $stmt = $conn->prepare("UPDATE FAILED_LOGINS SET failed_attempts = 0, lockout_until = NULL WHERE ip_address = ?");
            $stmt->bind_param("s", $ip_address);
            $stmt->execute();
            $stmt->close();

            // Clear the remaining time from session
            unset($_SESSION['remaining_time']);
        }

        // Validate the user credentials
        $stmt = $conn->prepare("SELECT pasahitza FROM ERABILTZAILEAK WHERE erabiltzailea = ?");
        $stmt->bind_param("s", $erabiltzailea);

        if (strlen($erabiltzailea) > 250 || strlen($pasahitza) > 250) {
            $_SESSION['error_message'] = "Erabiltzaile izena edo pasahitza ezin da 250 karaktere baino gehiagokoa izan.";
            header("Location: login.php");
            exit();
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pasahitza, $row['pasahitza'])) {
                // Reset failed attempts on successful login
                $stmt = $conn->prepare("INSERT INTO FAILED_LOGINS (ip_address, failed_attempts, last_attempt, lockout_until) VALUES (?, 0, CURRENT_TIMESTAMP, NULL) ON DUPLICATE KEY UPDATE failed_attempts = 0, last_attempt = CURRENT_TIMESTAMP, lockout_until = NULL");
                $stmt->bind_param("s", $ip_address);
                $stmt->execute();
                $stmt->close();

                header('Location: home.php');
                exit();
            } else {
                // Password incorrect
                $_SESSION['error_message'] = "Pasahitz okerra.";

                // Increment failed attempts and check if lockout should be applied
                $failed_attempts = $failed_attempts ? $failed_attempts + 1 : 1;
                if ($failed_attempts >= 3) {
                    $lockout_until = date("Y-m-d H:i:s", time() + $wait_time_seconds);
                    $stmt = $conn->prepare("INSERT INTO FAILED_LOGINS (ip_address, failed_attempts, last_attempt, lockout_until) VALUES (?, ?, CURRENT_TIMESTAMP, ?) ON DUPLICATE KEY UPDATE failed_attempts = ?, last_attempt = CURRENT_TIMESTAMP, lockout_until = ?");
                    $stmt->bind_param("sisss", $ip_address, $failed_attempts, $lockout_until, $failed_attempts, $lockout_until);
                } else {
                    $stmt = $conn->prepare("INSERT INTO FAILED_LOGINS (ip_address, failed_attempts, last_attempt) VALUES (?, ?, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE failed_attempts = failed_attempts + 1, last_attempt = CURRENT_TIMESTAMP");
                    $stmt->bind_param("si", $ip_address, $failed_attempts);
                }
                $stmt->execute();
                $stmt->close();
            }
        } else {
            $_SESSION['error_message'] = "Erabiltzailea ez da existitzen.";
        }
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
            max-width: 300px;
            width: 100%;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .register-prompt {
            margin-top: 20px;
            font-size: 14px;
        }

        .register-link {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        #message {
            margin-top: 10px;
            color: red;
            font-size: 14px;
            text-align: center;
        }

        .bold {
            font-weight: bold;
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

    <!-- Display message based on error or lockout timer -->
    <p id="message">
        <?php if (isset($_SESSION['remaining_time'])): ?>
            You have to wait <span id="countdown" class="bold"></span> until you can try again.
        <?php elseif (isset($_SESSION['error_message'])): ?>
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </p>

    <p class="register-prompt">
        Ez duzu akonturik? <a href="register.php" class="register-link">Erregistratu</a>
    </p>
</div>

<script>
    document.getElementById('erabiltzailea').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;
        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });

    <?php if (isset($_SESSION['remaining_time'])): ?>
        let remainingTime = <?php echo $_SESSION['remaining_time']; ?>;
        const countdownElem = document.getElementById('countdown');
        const messageElem = document.getElementById('message');

        function updateCountdown() {
            if (remainingTime > 0) {
                const minutes = Math.floor(remainingTime / 60);
                const seconds = remainingTime % 60;
                countdownElem.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                remainingTime--;
            } else {
                // Timer ends, hide the message without refreshing
                messageElem.style.display = 'none';
                clearInterval(countdownInterval); // Stop the interval to prevent any further countdowns
            }
        }
        
        // Start the countdown interval
        const countdownInterval = setInterval(updateCountdown, 1000);
    <?php endif; ?>
</script>

</body>
</html>
