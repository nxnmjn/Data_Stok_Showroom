<!-- koneksi ke db -->

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "showroom_db";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn){
    die("Gagal Mengoneksi: ". mysqli_conncet_error());
}

?>