<?php
// file: index.php (Router Utama)

session_start();
// 1. Panggil file-file yang selalu dibutuhkan
require_once 'helpers.php';
require_once 'config.php';
// require_once 'classes/Database.php';

// 2. Tangkap halaman yang diminta dari URL (?page=...)
// Jika tidak ada, default-nya adalah 'dashboard'
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// 3. Siapkan variabel-variabel default
$title = '';
$content = '';
$active_menu = $page; // Secara default, menu aktif sama dengan nama halaman

// 4. Logika Routing Utama
switch ($page) {
 

    case 'dashboard':
        include 'pages/dashboard.php';
        break;


    case 'customers':
        include 'pages/customers.php';
        break;

    case 'categories':
        include 'pages/categories.php';
        break;

    case 'employees':
        include 'pages/employees.php';
        break;

    case 'products':
        include 'pages/products.php';
        break;

    case 'shippers':
    include 'pages/shippers.php';
    break;
    
    case 'suppliers':
        include 'pages/suppliers.php';
        break;  

    case 'orders':
        include 'pages/orders.php';
        break;  


    default:
        // Halaman default jika 'page' tidak dikenali
        $title = 'Halaman Tidak Ditemukan';
        $content = 'view/404.php'; // Buat file 404.php sederhana di folder view
        break;
}

// 5. Panggil template utama di akhir
// Semua variabel yang sudah kita atur di atas ($title, $content, $active_menu, $list_customer, dll.)
// akan bisa diakses dari file main.php dan file-file view yang di-include.
include 'view/main.php';
?>