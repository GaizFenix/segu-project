<?php

    include 'includes/dbConnect.php'; // The include must be with the database connection

    function validateNAN($nan) {
        $numbers = substr($nan, 0, 8);
        $letter = substr($nan, -1);
        $validLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
        $calculatedLetter = $validLetters[$numbers % 23];
        return $calculatedLetter === $letter;
    }

    // Function to check if NAN is unique
    function isNANUnique($nan) {
        global $conn;
        $stmt = $conn->prepare("SELECT NAN FROM PERTSONAK WHERE NAN = ?");
        $stmt->bind_param("s", $nan);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows === 0;
    }

    // Function to check if username is unique
    function isErabiltzaileaUnique($erabiltzailea) {
        global $conn;
        $stmt = $conn->prepare("SELECT erabiltzailea FROM ERABILTZAILEAK WHERE erabiltzailea = ?");
        $stmt->bind_param("s", $erabiltzailea);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows === 0;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data and apply trim() method
        $izenAbizenak = trim($_POST['izenAbizenak']);
        $NAN = trim($_POST['NAN']);
        $telefonoa = trim($_POST['telefonoa']);
        $jaiotzeData = trim($_POST['jaiotzeData']);
        $email = trim($_POST['email']);
        $erabiltzailea = trim($_POST['erabiltzailea']);
        $pasahitza = trim($_POST['pasahitza']);

        // Apply a hash function to the password
        $hashed_password = password_hash($pasahitza, PASSWORD_BCRYPT);

        // Server-side validation
        // Validate izenAbizenak (only letters and spaces, max 250 characters)
        if (strlen($izenAbizenak) == 0 || strlen($izenAbizenak) > 250 || !preg_match("/^[a-zA-Z\s]+$/", $izenAbizenak)) {
            echo "Izen-abizenak beharrezkoa da, 250 karaktere baino gutxiago, eta hizkiak bakarrik onartzen dira.";
            exit();
        }

        // Validate NAN
        if (!validateNAN($NAN)) {
            echo "NAN okerra.";
            exit();
        }

        // Check if NAN is unique
        if (!isNANUnique($NAN)) {
            echo "NAN errepikatua.";
            exit();
        }

        // Validate telefonoa (must be exactly 9 digits)
        if (!preg_match("/^\d{9}$/", $telefonoa)) {
            echo "Telefono zenbakiak 9 digitu izan behar ditu.";
            exit();
        }

        // Validate jaiotzeData (must be in the format yyyy-mm-dd)
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $jaiotzeData)) {
            echo "Jaiotze dataren formatua uuuu-hh-ee izan behar du.";
            exit();
        }

        // Validate email using PHP's built-in email filter
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 250) {
            echo "Email formatu desegokia edo gehiegi luzea.";
            exit();
        }

        // Check if erabiltzailea (username) is unique
        if (!isErabiltzaileaUnique($erabiltzailea)) {
            echo "Erabiltzailea errepikatua.";
            exit();
        }

        // Prepare and bind for the first insert
        $stmt = $conn->prepare("INSERT INTO PERTSONAK (izenAbizenak, NAN, telefonoa, jaiotzeData, email) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            echo "Prapare failed: " . $conn->error;
        }
        $stmt->bind_param("sssss", $izenAbizenak, $NAN, $telefonoa, $jaiotzeData, $email);
        
        // Execute the first statement
        if ($stmt->execute()) {
            echo "Datuak gorde dira!";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        // Close the first statement
        $stmt->close();
        
        // Prepare and bind for the second insert
        $stmt = $conn->prepare("INSERT INTO ERABILTZAILEAK (erabiltzailea, pasahitza, NAN) VALUES (?, ?, ?)");
        if ($stmt === false) {
            echo "Prepare failed: " . $conn->error;
        }
        $stmt->bind_param("sss", $erabiltzailea, $hashed_password, $NAN);
        
        // Execute the second statement
        if ($stmt->execute()) {
            echo " Erabiltzaile eta pasahitza gorde dira!";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        // Close the second statement
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Space between form elements */
        }

        /* Button container styling */
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        /* Add space after email input */
        #email {
            margin-bottom: 50px;
        }
    </style>
    <script>
        // Define the Basque locale for flatpickr
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
    <h2>Register</h2>
    <form id="register_form" action="register.php" method="post">
        <label for="izenAbizenak">Izen-abizenak:</label>
        <input type="text" id="izenAbizenak" name="izenAbizenak" placeholder="adib.: Nikola Tesla" required>

        <label for="NAN">NAN-a:</label>
        <input type="text" id="NAN" name="NAN" placeholder="adib.: 12345678-Z" required>

        <label for="telefonoa">Telefonoa:</label>
        <input type="tel" id="telefonoa" name="telefonoa" placeholder="adib.: 123456789" required>

        <label for="jaiotzeData">Jaiotze data (uuuu-hh-mm):</label>
        <input type="text" id="jaiotzeData" name="jaiotzeData" placeholder="adib.: 2000-01-01" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="adib.: adibidea@eib.eus" required>

        <label for="erabiltzailea">Erabiltzailea:</label>
        <input type="text" id="erabiltzailea" name="erabiltzailea" required>

        <label for="pasahitza">Pasahitza:</label>
        <input type="password" id="pasahitza" oninput="updatePasswordStrengthIndicator()" name="pasahitza" required>
        <div id="pasahitza-indarra" style="font-weight: bold; color: gray;"></div>

        <div class="button-container">
            <div style="display: flex; justify-content: center; width: 100%;">
                <input id="register_submit" type="submit" value="Erregistratu">
            </div>
        </div>
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
            input.setCustomValidity('NAN okerra, sartutako letra ez dator bat zenbakiekin.');
        } else {
            input.setCustomValidity('');
        }
    } else {
        input.setCustomValidity('Formato desegokia. 11111111-Z erabili');
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
        input.setCustomValidity('Telefono zenbakiak 9 digitu izan behar ditu.');
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
        input.setCustomValidity('Jaiotze dataren formatua uuuu-hh-ee izan behar du.');
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
        input.setCustomValidity('Email formatu desegokia.');
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

<!-- CHECK PASSWORD STRENGTH -->
<script>
        function evaluatePasswordStrength(password) {
            let strength = 0;
            if (password.match(/[a-z]+/)) {
                strength += 1;
            }
            if (password.match(/[A-Z]+/)) {
                strength += 1;
            }
            if (password.match(/[0-9]+/)) {
                strength += 1;
            }
            if (password.match(/[!@"#$%&'()*+,-./:;<=>?@[\]^_{|}~`]+/)) {
                strength += 1;
            }
            return strength;
        }

        function updatePasswordStrengthIndicator() {
            const password = document.getElementById('pasahitza').value;
            const strengthIndicator = document.getElementById('pasahitza-indarra');
            const strength = evaluatePasswordStrength(password);

            switch (strength) {
                // case 0:
                //    strengthIndicator.style.color = 'gray'; // Optional: for empty password
                //    strengthIndicator.textContent = 'Sartu pasahitza';
                //    break;
                case 1:
                    strengthIndicator.style.color = 'maroon';
                    strengthIndicator.textContent = 'Oso insegurua';
                    break;
                case 2:
                    strengthIndicator.style.color = 'red';
                    strengthIndicator.textContent = 'Insegurua';
                    break;
                case 3:
                    strengthIndicator.style.color = 'gold';
                    strengthIndicator.textContent = 'Segurtasun ertainekoa';
                    break;
                case 4:
                    strengthIndicator.style.color = 'green';
                    strengthIndicator.textContent = 'Oso segurua';
                    break;
                default:
                    strengthIndicator.style.color = 'gray'; // Optional: for empty password
                    strengthIndicator.textContent = 'Sartu pasahitza';
                    break;
            }
        }
    </script>

    <!-- DENY PASSWORDS UNDER 3 LEVEL STRENGTH -->
<script>
    document.getElementById('pasahitza').addEventListener('input', function (event) {
        var input = event.target;
        var value = input.value;

        // Update the password strength indicator
        updatePasswordStrengthIndicator();

        // Set custom validity message if password strength is below 3
        if (evaluatePasswordStrength(value) < 3) {
           input.setCustomValidity('Pasahitza oso ahula da (letra larri, xehe, zenbaki eta karaktere bereziak erabiltzea gomendatzen da).');
        } 
        else if (value.length <= 8) {
            input.setCustomValidity('Pasahitzak gutxienez 8 karaktere izan behar ditu.');
        }
        else {
            input.setCustomValidity('');
        }
    });
</script>

</body>
</html>
