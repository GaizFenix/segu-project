<?php
include 'includes/dbConnect.php'; // CURRENTLY NOT WORKING
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

<h2>Register</h2>
<form id="register_form" action="register.php" method="post">
    <label for="izenAbizenak">Izen-abizenak:</label>
    <input type="text" id="izenAbizenak" name="izenAbizenak" placeholder="adib.: Nikola Tesla" required><br>

    <label for="NAN">NAN-a:</label>
    <input type="text" id="NAN" name="NAN" placeholder="adib.: 12345678-Z" required><br>

    <label for="telefonoa">Telefonoa:</label>
    <input type="tel" id="telefonoa" name="telefonoa" placeholder="adib.: 123456789" required><br> <!-- check type -->

    <label for="jaiotzeData">Jaiotze data (uuuu-hh-mm):</label>
    <input type="text" id="jaiotzeData" name="jaiotzeData" placeholder="adib.: 2000-01-01" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="adib.: adibidea@eib.eus" required><br>

    <br>
    <label for="erabiltzailea">Erabiltzailea:</label>
    <input type="text" id="erabiltzailea" name="erabiltzailea" required><br>

    <label for="pasahitza">Pasahitza:</label>
    <input type="password" id="pasahitza" name="pasahitza" required><br>

    <input id="register_submit" type="submit" value="Erregistratu">
</form>
    
</body>
</html>

<!-- ONLY ALLOWS LETTERS AND SPACES ON IZENABIZENAK -->
<script> 
document.getElementById('izenAbizenak').addEventListener('input', function (event) {
    var input = event.target;
    var value = input.value;
    input.value = value.replace(/[^a-zA-Z\s]/g, '');
});
</script>

<!-- ENFORCE FORMAT 11111111-Z ON NAN -->
<script>
document.getElementById('NAN').addEventListener('input', function (event) {
    var input = event.target;
    var value = input.value.toUpperCase().replace(/[^0-9A-Z-]/g, ''); // Allow only numbers, letters, and hyphen

    // Split the value into parts before and after the hyphen
    var parts = value.split('-');
    var numbers = parts[0].slice(0, 8).replace(/[^0-9]/g, ''); // Allow only numbers before the hyphen
    var letter = parts[1] ? parts[1].slice(0, 1).replace(/[^A-Z]/g, '') : ''; // Allow only letters after the hyphen

    // Reconstruct the value with the hyphen
    if (numbers.length === 8) {
        value = numbers + '-' + letter;
    } else {
        value = numbers;
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
});
</script>

<!-- ALGORITHM TO VALIDATE NAN LETTER -->
<?php
function validateNAN($nan) {
    $numbers = substr($nan, 0, 8);
    $letter = substr($nan, -1);
    $validLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
    $calculatedLetter = $validLetters[$numbers % 23];
    return $calculatedLetter === $letter;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $izenAbizenak = $_POST['izenAbizenak'];
    $NAN = $_POST['NAN'];
    $telefonoa = $_POST['telefonoa'];
    $jaiotzeData = $_POST['jaiotzeData'];
    $email = $_POST['email'];
    $erabiltzailea = $_POST['erabiltzailea'];
    $pasahitza = $_POST['pasahitza'];

    // Apply a hash function to the password
    $hashed_password = password_hash($pasahitza, PASSWORD_BCRYPT);

    // Validate NAN
    if (!validateNAN($NAN)) {
        echo "Invalid NAN.";
    } else {
        // Prepare and bind for the first insert
        $stmt = $conn->prepare("INSERT INTO usuarios (izenAbizenak, NAN, telefonoa, jaiotzeData, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $izenAbizenak, $NAN, $telefonoa, $jaiotzeData, $email);
    
        // Execute the first statement
        if ($stmt->execute()) {
            echo "Data registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        // Close the first statement
        $stmt->close();
    
        // Prepare and bind for the second insert
        $stmt = $conn->prepare("INSERT INTO erabiltzaileak (erabiltzailea, pasahitza) VALUES (?, ?)");
        $stmt->bind_param("ss", $erabiltzailea, $$hashed_password);
    
        // Execute the second statement
        if ($stmt->execute()) {
            echo "User and password registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        // Close the second statement
        $stmt->close();
    }
}

?>