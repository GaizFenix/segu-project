<?php

include 'includes/dbConnect.php';

// Get the username from the URL
$erabiltzailea = isset($_GET['user']) ? $_GET['user'] : '';

if ($erabiltzailea) {
    // Fetch user data from the database
    $stmt = $conn->prepare("SELECT izenAbizenak, NAN, telefonoa, jaiotzeData, email FROM PERTSONAK NATURAL JOIN ERABILTZAILEAK WHERE erabiltzailea = ?");
    $stmt->bind_param("s", $erabiltzailea);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No user specified.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save'])) {
        $izenAbizenak = $_POST['izenAbizenak'];
        $NAN = $_POST['NAN'];
        $telefonoa = $_POST['telefonoa'];
        $jaiotzeData = $_POST['jaiotzeData'];
        $email = $_POST['email'];

        // Update user data in the database
        $stmt = $conn->prepare("
            UPDATE PERTSONAK 
            SET izenAbizenak = ?, NAN = ?, telefonoa = ?, jaiotzeData = ?, email = ? 
            WHERE NAN = (SELECT NAN FROM ERABILTZAILEAK WHERE erabiltzailea = ?)
        ");        
        
        $stmt->bind_param("ssssss", $izenAbizenak, $NAN, $telefonoa, $jaiotzeData, $email, $erabiltzailea);

        if ($stmt->execute()) {
            echo "User data updated successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } elseif (isset($_POST['cancel'])) {
        // Redirect to show_all_users.php without saving changes
        header("Location: show_all_users.php");
        exit;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Define the Basque locale
        flatpickr.localize({
            weekdays: {
                shorthand: ['Al.', 'Ar.', 'Az.', 'Og.', 'Ol.', 'La.', 'Ig.'],
                longhand: ['Astelehena', 'Asteartea', 'Asteazkena', 'Osteguna', 'Ostirala', 'Larunbata', 'Igandea']
            },
            months: {
                shorthand: ['Urt.', 'Ots.', 'Mar.', 'Api.', 'Mai.', 'Eka.', 'Uzt.', 'Abu.', 'Ira.', 'Urr.', 'Aza.', 'Abe.'],
                longhand: ['Urtarrila', 'Otsaila', 'Martxoa', 'Apirila', 'Maiatza', 'Ekaina', 'Uztaila', 'Abuztua', 'Iraila', 'Urria', 'Azaroa', 'Abendua']
            },
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Modify User</h2>
        <form id="user_modify_form" action="modify_user.php?user=<?php echo urlencode($user); ?>" method="post">            
            <label for="izenAbizenak">Izen-abizenak:</label>
            <input type="text" id="izenAbizenak" name="izenAbizenak" value="<?php echo htmlspecialchars($userData['izenAbizenak']); ?>" required><br>
    
            <label for="NAN">NAN-a:</label>
            <input type="text" id="NAN" name="NAN" value="<?php echo htmlspecialchars($userData['NAN']); ?>" required><br>
    
            <label for="telefonoa">Telefonoa:</label>
            <input type="tel" id="telefonoa" name="telefonoa" value="<?php echo htmlspecialchars($userData['telefonoa']); ?>" required><br>
    
            <label for="jaiotzeData">Jaiotze data (uuuu-hh-mm):</label>
            <input type="text" id="jaiotzeData" name="jaiotzeData" value="<?php echo htmlspecialchars($userData['jaiotzeData']); ?>" placeholder="adib.: 2000-01-01" required><br>
    
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required><br>
    
            <br>
            <label for="erabiltzailea">Erabiltzailea:</label>
            <input type="text" id="erabiltzailea" name="erabiltzailea" value="<?php echo htmlspecialchars($erabiltzailea); ?>" required><br>
    
            <button type="submit" id="user_modify_submit" value="save">Gorde</button>
            <button type="submit" id="user_modify_cancel" value="cancel">Deuseztatu</button>
        </form>
    </div>

<!-- ONLY ALLOWS LETTERS AND SPACES ON IZENABIZENAK, MAX 250 CHARACTERS -->
<script> 
document.getElementById('izenAbizenak').addEventListener('input', function (event) {
    var input = event.target;
    var value = input.value;
    // Remove any character that is not a letter or space
    value = value.replace(/[^a-zA-Z\s]/g, '');
    // Truncate the value to 250 characters if it exceeds the limit
    if (value.length > 250) {
        value = value.slice(0, 250);
    }
    input.value = value;
});
</script>

<!-- ENFORCE FORMAT 11111111-Z ON NAN -->
<script>
document.getElementById('NAN').addEventListener('input', function (event) {
    var input = event.target;
    var value = input.value.toUpperCase().replace(/[^0-9A-Z-]/g, ''); // Allow only numbers, letters, and hyphen

    // Remove the hyphen if it exists
    value = value.replace('-', '');

    // Split the value into numbers and letter
    var numbers = value.slice(0, 8).replace(/[^0-9]/g, ''); // Allow only numbers before the hyphen
    var letter = value.slice(8, 9).replace(/[^A-Z]/g, ''); // Allow only letters after the hyphen

    // Reconstruct the value with the hyphen
    if (numbers.length === 8) {
        value = numbers + '-' + letter;
    } else {
        value = numbers + letter;
    }

    input.value = value;

    // Validate the letter if the format is correct
    if (value.length === 10 && /^\d{8}-[A-Z]$/.test(value)) {
        var validLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
        var calculatedLetter = validLetters[numbers % 23];
        if (calculatedLetter !== letter) {
            input.setCustomValidity('Invalid NAN. The letter does not correspond to the numbers.');
        } else {
            input.setCustomValidity('');
        }
    } else {
        input.setCustomValidity('Invalid format. Use 11111111-Z');
    }
});

document.getElementById('NAN').addEventListener('keydown', function (event) {
    var input = event.target;
    var value = input.value;

    // Allow backspace and delete keys to remove the hyphen
    if (event.key === 'Backspace' || event.key === 'Delete') {
        if (value.endsWith('-')) {
            input.value = value.slice(0, -1); // Remove the hyphen
        }
    }
});
</script>

<!-- VALIDATE TELEPHONE NUMBER LENGTH -->
<script>
document.getElementById('telefonoa').addEventListener('input', function (event) {
    var input = event.target;
    var value = input.value.replace(/[^0-9]/g, ''); // Allow only numbers

    // Enforce length of 9 digits
    if (value.length > 9) {
        value = value.slice(0, 9);
    }

    input.value = value;

    // Set custom validity message if length is not 9
    if (value.length !== 9) {
        input.setCustomValidity('Telephone number must be exactly 9 digits long.');
    } else {
        input.setCustomValidity('');
    }
});
</script>

<!-- ENFORCE FORMAT yyyy-mm-dd ON JAIOTZEDATA -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#jaiotzeData", {
        dateFormat: "Y-m-d",
        locale: "eu",
        allowInput: true,
        maxDate: "today"
    });
});
</script>

<!-- ENFORCE FORMAT yyyy-mm-dd ON JAIOTZEDATA | LIMIT TEXT INPUT -->
<script>
document.getElementById('jaiotzeData').addEventListener('input', function (event) {
    var input = event.target;
    var value = input.value.replace(/[^0-9]/g, ''); // Allow only numbers

    // Automatically insert hyphens at appropriate positions
    if (value.length > 4) {
        value = value.slice(0, 4) + '-' + value.slice(4);
    }
    if (value.length > 7) {
        value = value.slice(0, 7) + '-' + value.slice(7);
    }

    // Enforce length of 10 characters (yyyy-mm-dd)
    if (value.length > 10) {
        value = value.slice(0, 10);
    }

    input.value = value;

    // Set custom validity message if format is not yyyy-mm-dd
    if (value.length !== 10 || !/^\d{4}-\d{2}-\d{2}$/.test(value)) {
        input.setCustomValidity('Date of birth must be in the format yyyy-mm-dd.');
    } else {
        input.setCustomValidity('');
    }
});
</script>


<!-- VALIDATE EMAIL FORMAT --> <!-- SUPPOSEDLY NOT NECESSARY AS HTML5 ALREADY DOES THIS -->
<script>
document.getElementById('email').addEventListener('input', function (event) {
    var input = event.target;
    var value = input.value;

    // Simple email regex for validation
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // Set custom validity message if email format is incorrect
    if (!emailPattern.test(value)) {
        input.setCustomValidity('Invalid email format.');
    } else {
        input.setCustomValidity('');
    }

    // Only allow a maximum of 250 characters
    if (value.length > 250) {
        value = value.slice(0, 250);
    }

    input.value = value;
});
</script>

</body>
</html>