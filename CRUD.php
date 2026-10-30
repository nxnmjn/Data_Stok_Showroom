<!-- Fitur utama sekaligus mengubah, menambah, dan menghapus data -->

<?php

session_start();
require 'connection.php';
require 'Bahasa.php';

//cek session
$timeout = 1800;
if (isset($_SESSION['last_active']) && (time() - $_SESSION['last_active'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['last_active'] = time();

//cek cookie alertnya
if (isset($_COOKIE['welcome_msg'])){
    echo "<script>alert('". $_COOKIE['welcome_msg']."');</script>";
    
    //untuk menghapus cookie agar tidak muncul lagi
    setcookie("welcome_msg", "", time() - 3600,"/");
}

// Proses menghapus
if(isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kendaraan WHERE id=$id");
    header("Location: CRUD.php");
    exit;
}

// Menu 
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';

if ($filter == 'motor') {
    $kendaraan = mysqli_query($conn, "SELECT * FROM kendaraan WHERE kategori='Motor'");
} elseif ($filter == 'mobil') {
    $kendaraan = mysqli_query($conn, "SELECT * FROM kendaraan WHERE kategori='Mobil'");
} elseif ($filter == 'semua') {
    $kendaraan = mysqli_query($conn, "SELECT * FROM kendaraan");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['title'] ?></title>
    <style>
        body {
            background-color: #F4EEFF;
            color: #424874;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px 20px;
            position: relative;
            min-height: 100vh;
        }

        /* Bahasa di pojok kanan atas */
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

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(66, 72, 116, 0.1);
            border-top: 5px solid #424874;
        }

        .header-area {
            border-bottom: 2px solid #DCD6F7;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-area h1 { margin: 0; font-size: 26px; }
        .header-area p { margin: 5px 0 0 0; font-size: 14px; color: #A6B1E1; }

        /* Tombol Desain Umum */
        .btn {
            display: inline-block;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
            cursor: pointer;
            border: none;
        }

        /* Tombol Menu Filter */
        .btn-menu {
            background-color: #DCD6F7;
            color: #424874;
            margin-right: 5px;
            margin-bottom: 15px;
        }
        .btn-menu:hover { background-color: #A6B1E1; }
        .btn-menu.active { background-color: #424874; color: #F4EEFF; }

        .btn-tambah {
            background-color: #424874;
            color: #F4EEFF;
            float: right; /* Geser tombol tambah ke sebelah kanan menu */
        }
        .btn-tambah:hover { background-color: #A6B1E1; color: #424874; }

        .btn-edit { background-color: #A6B1E1; color: #424874; padding: 6px 12px; font-size: 13px; }
        .btn-edit:hover { background-color: #DCD6F7; }

        .btn-hapus { background-color: #ff6b6b; color: white; padding: 6px 12px; font-size: 13px; }
        .btn-hapus:hover { background-color: #fa5252; }

        /* Logout Pojok Kanan Bawah */
        .btn-logout-corner {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #e74c3c;
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.4);
            transition: 0.3s;
            z-index: 1000;
        }
        .btn-logout-corner:hover { background-color: #c0392b; transform: translateY(-3px); }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; clear: both; }
        th, td { padding: 12px 15px; text-align: center; border-bottom: 1px solid #DCD6F7; }
        th { background-color: #424874; color: #F4EEFF; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; }
        tr:hover { background-color: #F4EEFF; }
    </style>
</head>
<body>

    <div class="lang-switcher">
        <a href="?lang=id">ID</a> | <a href="?lang=en">EN</a>
    </div>

    <div class="container">
        <div class="header-area">
            <h1><?= $lang['title'] ?></h1>
            <p><?= $lang['welcome'] ?> <b><?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin' ?></b></p>
        </div>

        <div>
            <!-- Tombol Menu Kategori -->
            <a href="?filter=motor" class="btn btn-menu <?= $filter == 'motor' ? 'active' : '' ?>"><?= $lang['motorcycle'] ?></a> 
            <a href="?filter=mobil" class="btn btn-menu <?= $filter == 'mobil' ? 'active' : '' ?>"><?= $lang['car'] ?></a> 
            <a href="?filter=semua" class="btn btn-menu <?= $filter == 'semua' ? 'active' : '' ?>"><?= $lang['all_vehicle'] ?></a> 

            <!-- Tombol Tambah Kendaraan -->
            <a href="tambah.php" class="btn btn-tambah">+ <?= $lang['add_vehicle'] ?></a>
        </div>

        <!-- Tabel Muncul Jika Filter Diklik -->
        <?php if ($filter != null): ?>
            <table>
                <tr>
                    <th>No</th>
                    <th><?= $lang['brand'] ?></th>
                    <th><?= $lang['type'] ?></th>
                    
                    <?php if($filter == 'semua'): ?>
                        <th><?= $lang['category'] ?></th>
                    <?php endif; ?>
                    
                    <th><?= $lang['stock'] ?></th>
                    <th><?= $lang['action'] ?></th>
                </tr>
                <?php 
                $no = 1; 
                while ($row = mysqli_fetch_assoc($kendaraan)) { 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['merk']) ?></td>
                    <td><?= htmlspecialchars($row['tipe']) ?></td>
                    
                    <?php if($filter == 'semua'): ?>
                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <?php endif; ?>
                    
                    <td><?= htmlspecialchars($row['stok']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-edit"><?= $lang['edit'] ?></a>
                        <a href="CRUD.php?hapus=<?= $row['id'] ?>" class="btn btn-hapus" onclick="return confirm('Yakin hapus data ini?');"><?= $lang['delete'] ?></a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        <?php endif; ?>
    </div>

    <a href="logout.php" class="btn-logout-corner"><?= $lang['logout'] ?></a>

</body>
</html>