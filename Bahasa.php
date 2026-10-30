<!-- Fitur pergantian Bahasa -->

<?php
//Laman masih dalam keadaan default bahasa indonesia
if (!isset($_SESSION['lang'])){
    $_SESSION['lang'] = 'id';
}

//Fitur ganti bahasa dari URL
if (isset($_GET['lang'])){
    $_SESSION['lang'] = $_GET['lang'] == 'en' ? 'en' : 'id';
}

$kata = [
    'id' => [
        'title' => 'Admin Showroom',
        'welcome' => 'Selamat Datang',
        'login' => 'Masuk',
        'logout' => 'Keluar',
        'add_vehicle' => 'Tambah Kendaraan',
        'brand' => 'Merk',
        'type' => 'Tipe',
        'stock' => 'Stok',
        'action' => 'Aksi',
        'edit' => 'Ubah',
        'delete' => 'Hapus',
        'save' => 'Simpan',
        'reset' => 'Ulang',
        'category' => 'Kategori',
        'car' => 'Mobil',
        'motorcycle' => 'Motor',
        'all_vehicle' => 'Seluruh Kendaraan',
        'empty_error' => 'Data tidak boleh kosong!',
    ],
    'en' => [
        'title' => 'Showroom Admin',
        'welcome' => 'welcome',
        'login' => 'Login',
        'logout' => 'Logout',
        'add_vehicle' => 'Add Vehicle',
        'brand' => 'Brand',
        'type' => 'Type',
        'stock' => 'Stock',
        'action' => 'Action',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'save' => 'Save',
        'reset' => 'Reset',
        'category' => 'Category',
        'car' => 'Car',
        'motorcycle' => 'Motorcycle',
        'all_vehicle' => 'All Vehicle',
        'empty_error' => 'Data cannot be empty!',
    ]
];

$lang = $kata[$_SESSION['lang']];

?>