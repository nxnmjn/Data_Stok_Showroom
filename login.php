<!-- Laman Login ke Admin -->

<?php
session_start();
require 'connection.php';
require 'Bahasa.php';

//Jika sudah login, lempar ke CRUD
$timeout = 1800;
if (isset($_SESSION['last_active']) && (time() - $_SESSION['last_active'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['last_active'] = time();

$error = '';
// Login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $username;
        
        // Set cookie untuk alert selamat datang (berlaku 1 jam)
        setcookie("welcome_msg", "Selamat datang, $username!", time() + 3600, "/");
        
        header("Location: CRUD.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!-- Tampilan Interfacenya -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['login'] ?> Showroom</title>
    <style>
        body {
            background-color: #F4EEFF; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
            color: #424874;
        }

        /* Memaksa elemen bahasa ke pojok kanan atas */
        .lang-switcher {
            position: absolute;
            top: 20px;
            right: 30px;
            background-color: #DCD6F7;
            padding: 8px 15px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(66, 72, 116, 0.2);
        }

        .lang-switcher a {
            text-decoration: none;
            color: #424874;
            font-weight: bold;
            font-size: 14px;
            margin: 0 5px;
            transition: 0.2s;
        }

        .lang-switcher a:hover {
            color: #A6B1E1;
        }

        /* Elemen kotak dasar login */
        .login-card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(66, 72, 116, 0.15);
            width: 100%;
            max-width: 320px;
            text-align: center;
            border-top: 5px solid #424874;
        }

        .login-card h2 {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 24px;
        }

        /* Form Input Username dan password */
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            border: 2px solid #DCD6F7;
            border-radius: 8px;
            font-size: 14px;
            color: #424874;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #A6B1E1;
            outline: none;
        }

        /* Tombol */
        button {
            width: 100%;
            background-color: #424874;
            color: #F4EEFF;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        button:hover {
            background-color: #A6B1E1;
            color: #424874;
        }
        
        button:active {
            transform: scale(0.98);
        }

        .error-msg {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 15px;
            background-color: #fce4e4;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- Tombol Bahasa di Kanan Atas -->
    <div class="lang-switcher">
        <a href="?lang=id">ID</a> | <a href="?lang=en">EN</a>
    </div>

    <!-- Kotak Form Login -->
    <div class="login-card">
        <h2><?= $lang['login'] ?> Showroom</h2>
        
        <?php if($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit"><?= $lang['login'] ?></button>
        </form>
    </div>

</body>
</html>