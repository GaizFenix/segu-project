<?php
function startCountdown() {
    // Set the countdown duration (5 minutes)
    $countdown_duration = 30; // 5 minutes in seconds

    // Check if the user is logging in
    if (!isset($_SESSION['login_time'])) {
        // Set the login time
        $_SESSION['login_time'] = time();
    }

    // Calculate the remaining time
    $elapsed_time = time() - $_SESSION['login_time'];
    $remaining_time = $countdown_duration - $elapsed_time;
}

function checkCountdown() {
    // Check if the countdown has started
    if (isset($_SESSION['login_time'])) {
        // Calculate the remaining time
        $elapsed_time = time() - $_SESSION['login_time'];
        $remaining_time = 30 - $elapsed_time;

        // Check if the countdown has finished
        if ($remaining_time <= 0) {
            // Redirect to logout.php
            header("Location: /logout.php");
            exit();
        } else {
            // Return the remaining time as JSON
            echo json_encode(['remaining_time' => $remaining_time]);
        }
    }
}