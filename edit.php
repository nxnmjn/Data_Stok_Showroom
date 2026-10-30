<! Fitur Mengedit data >

<?php
session_start();
require 'connection.php';
require 'bahasa.php';

//jembatan
$timeout = 1800;
if (isset($_SESSION['last_active']) && (time() - $_SESSION['last_active'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['last_active'] = time();


$id = (int)$_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kendaraan WHERE id=$id"));

$error = '';

// proses submit form edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $merk = htmlspecialchars(trim($_POST['merk']));
    $tipe = htmlspecialchars(trim($_POST['tipe']));
    $kategori = htmlspecialchars(trim($_POST['kategori'])); // Menangkap data kategori
    $stok = filter_var($_POST['stok'], FILTER_SANITIZE_NUMBER_INT);

    if (empty($merk) || empty($tipe) || empty($kategori) || $stok === '') {
        $error = $lang['empty_error'];
    } else {
        // Query UPDATE disesuaikan dengan kolom kategori baru
        mysqli_query($conn, "UPDATE kendaraan SET merk='$merk', tipe='$tipe', kategori='$kategori', stok='$stok' WHERE id=$id");
        header("Location: CRUD.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['edit'] ?></title>
    <style>
        body {
            background-color: #F4EEFF;
            color: #424874;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

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
        .lang-switcher a:hover { color: #A6B1E1; }

        .form-card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(66, 72, 116, 0.15);
            width: 100%;
            max-width: 400px;
            border-top: 5px solid #424874;
        }
        .form-card h2 { margin-top: 0; margin-bottom: 25px; text-align: center; }

        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 14px; }
        .input-group input, .input-group select {
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            border: 2px solid #DCD6F7;
            border-radius: 8px;
            font-size: 14px;
            color: #424874;
            transition: 0.3s;
            background-color: #ffffff;
        }
        .input-group input:focus, .input-group select:focus {
            border-color: #A6B1E1;
            outline: none;
        }

        .btn-group { display: flex; gap: 8px; margin-top: 30px; }
        button, .btn-batal {
            flex: 1; padding: 10px; border: none; border-radius: 8px; font-size: 14px;
            font-weight: bold; cursor: pointer; text-align: center; text-decoration: none; transition: 0.3s;
        }
        .btn-simpan { background-color: #424874; color: #F4EEFF; }
        .btn-simpan:hover { background-color: #2b3050; }
        
        .btn-bersihkan { background-color: #A6B1E1; color: #424874; }
        .btn-bersihkan:hover { background-color: #8c98d1; }
        
        .btn-batal { background-color: #DCD6F7; color: #424874; }
        .btn-batal:hover { background-color: #c4bcdb; }

        .error-msg {
            color: #e74c3c; background-color: #fce4e4; padding: 10px;
            border-radius: 5px; margin-bottom: 20px; font-size: 14px; text-align: center;
        }
    </style>
</head>
<body>

    <div class="lang-switcher">
        <a href="?lang=id">ID</a> | <a href="?lang=en">EN</a>
    </div>

    <div class="form-card">
        <h2><?= $lang['edit'] ?></h2>
        
        <?php if($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="input-group">
                <label><?= $lang['brand'] ?></label>
                <input type="text" name="merk" value="<?= htmlspecialchars($data['merk']) ?>" required>
            </div>
            
            <div class="input-group">
                <label><?= $lang['type'] ?></label>
                <input type="text" name="tipe" value="<?= htmlspecialchars($data['tipe']) ?>" required>
            </div>

            <div class="input-group">
                <label><?= $lang['category'] ?></label>
                <select name="kategori" required>
                    <option value="Mobil" <?= $data['kategori'] == 'Mobil' ? 'selected' : '' ?>><?= $lang['car'] ?></option>
                    <option value="Motor" <?= $data['kategori'] == 'Motor' ? 'selected' : '' ?>><?= $lang['motorcycle'] ?></option>
                </select>
            </div>
            
            <div class="input-group">
                <label><?= $lang['stock'] ?></label>
                <input type="number" name="stok" value="<?= htmlspecialchars($data['stok']) ?>" required>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn-simpan"><?= $lang['save'] ?></button>
                <button type="reset" class="btn-bersihkan"><?= $lang['reset'] ?></button>
                <a href="CRUD.php" class="btn-batal">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>