<?php
    session_start();
    
    // Check if user is logged in
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: /includes/error.php');
        exit();
    }

    $username = isset($_SESSION['erabiltzailea']) ? $_SESSION['erabiltzailea'] : '';

    // Handle the logout
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        $_SESSION['logged_in'] = false;
        session_destroy();
        header('Location: login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            position: relative;
        }
        .container {
            text-align: center;
        }
        .container button {
            display: block;
            width: 200px;
            margin: 10px auto;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }
        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            width: 80px;
            height: 80px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .logout-button img {
            width: 32px;
            height: 32px;
            margin-bottom: 5px;
            filter: invert(100%); /* Invert the color of the icon */
        }
        .logout-button span {
            font-weight: bold;
        }
        .logout-button:hover {
            background-color: #ff1a1a;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
            text-align: center;
        }
        .modal-content button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <button onclick="location.href='users.php'">Erabiltzaile guztien ikuskaketa</button>
        <button onclick="location.href='add_item.php'">Elementuen gehikuntza</button>
        <button onclick="location.href='items.php'">Elementu guztien ikuskaketa</button>
    </div>
    <button class="logout-button" onclick="showModal()">
        <img src="includes/user-icon.png" alt="User Icon">
        <span>Saioa itxi</span>
    </button>

    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <p>Oraintxe bertan <?php echo htmlspecialchars($username); ?> bezala identifikatuta zaude. Irten nahi duzu?</p>
            <form method="post" action="">
                <button type="submit" name="logout" value="yes">Bai</button>
                <button type="button" onclick="hideModal()">Ez</button>
            </form>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function hideModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }
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
            

        

          
    </script>

</body>
</html>