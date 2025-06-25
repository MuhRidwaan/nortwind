<?php
// file: pages/products.php

// Panggil Class Product dan Class lain yang mungkin dibutuhkan
require_once 'classes/Product.php';
$product_manager = new Product($pdo);

// Tangkap 'action' dari URL, default-nya adalah 'list'
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$active_menu = "products"; // Untuk menandai menu sidebar yang aktif

switch ($action) {
    case 'list':
        $title = "Products List";
        require_once 'classes/Pagination.php';

        // 1. Tangkap kata kunci pencarian
        $search_term = isset($_GET['q']) ? $_GET['q'] : null;

        // 2. Tentukan halaman & data per halaman
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $records_per_page = 10;

        // 3. Dapatkan total data dengan/tanpa pencarian
        $total_products = $product_manager->getTotalCount($search_term);

        // 4. Buat URL dasar untuk pagination
        $base_url = "index.php?page=products&action=list";
        if ($search_term) {
            $base_url .= "&q=" . urlencode($search_term);
        }

        // 5. Buat object Pagination
        $pagination = new Pagination($total_products, $current_page, $records_per_page, $base_url);
        
        // 6. Ambil data produk yang sudah dipaginasi
        $list_product = $product_manager->getPaginated($pagination->getLimit(), $pagination->getOffset(), $search_term);

        // 7. Tentukan file view
        $content = 'view/products/index.php';
        break;

    case 'create':
        $title = "Add New Product";
        
        // AMBIL DATA UNTUK DROPDOWN
        // Mengambil semua kategori untuk ditampilkan di form
        $categories = $pdo->query("SELECT CategoryID, CategoryName FROM categories ORDER BY CategoryName ASC")->fetchAll();
        // Mengambil semua supplier untuk ditampilkan di form
        $suppliers = $pdo->query("SELECT SupplierID, CompanyName FROM suppliers ORDER BY CompanyName ASC")->fetchAll();

        $content = 'view/products/create.php';
        break;

    case 'store': // Memproses data dari form tambah
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ProductName' => $_POST['ProductName'],
                'SupplierID' => empty($_POST['SupplierID']) ? null : $_POST['SupplierID'],
                'CategoryID' => empty($_POST['CategoryID']) ? null : $_POST['CategoryID'],
                'QuantityPerUnit' => $_POST['QuantityPerUnit'],
                'UnitPrice' => empty($_POST['UnitPrice']) ? 0 : $_POST['UnitPrice'],
                'UnitsInStock' => empty($_POST['UnitsInStock']) ? 0 : $_POST['UnitsInStock'],
                'UnitsOnOrder' => empty($_POST['UnitsOnOrder']) ? 0 : $_POST['UnitsOnOrder'],
                'ReorderLevel' => empty($_POST['ReorderLevel']) ? 0 : $_POST['ReorderLevel'],
                'Discontinued' => isset($_POST['Discontinued']) ? 1 : 0
            ];

            $result = $product_manager->create($data);

            if ($result === true) {
                set_flash_message('Data produk berhasil ditambahkan!', 'success');
            } else {
                set_flash_message($result, 'danger');
            }
        
            header('Location: index.php?page=products&action=list');
            exit;
        }
        break;

     case 'edit': // Menampilkan form edit
        $title = "Edit Product Data";
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if (!$id) {
            header('Location: index.php?page=products&action=list');
            exit;
        }

        // AMBIL DATA UNTUK DROPDOWN
        $categories = $pdo->query("SELECT CategoryID, CategoryName FROM categories ORDER BY CategoryName ASC")->fetchAll();
        $suppliers = $pdo->query("SELECT SupplierID, CompanyName FROM suppliers ORDER BY CompanyName ASC")->fetchAll();
        
        $data_product = $product_manager->getById($id);
        $content = 'view/products/edit.php';
        break;
    case 'update': // Memproses data dari form edit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ProductID'];
            $data = [
                'ProductName' => $_POST['ProductName'],
                'SupplierID' => empty($_POST['SupplierID']) ? null : $_POST['SupplierID'],
                'CategoryID' => empty($_POST['CategoryID']) ? null : $_POST['CategoryID'],
                'QuantityPerUnit' => $_POST['QuantityPerUnit'],
                'UnitPrice' => empty($_POST['UnitPrice']) ? 0 : $_POST['UnitPrice'],
                'UnitsInStock' => empty($_POST['UnitsInStock']) ? 0 : $_POST['UnitsInStock'],
                'UnitsOnOrder' => empty($_POST['UnitsOnOrder']) ? 0 : $_POST['UnitsOnOrder'],
                'ReorderLevel' => empty($_POST['ReorderLevel']) ? 0 : $_POST['ReorderLevel'],
                'Discontinued' => isset($_POST['Discontinued']) ? 1 : 0
            ];

            if ($product_manager->update($id, $data)) {
                 set_flash_message('Data produk berhasil diperbarui!', 'success');
            } else {
                 set_flash_message('Gagal memperbarui data produk.', 'danger');
            }
            
            header('Location: index.php?page=products&action=list');
            exit;
        }
        break;

    case 'delete':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            if ($product_manager->delete($id)) {
                set_flash_message('Data produk berhasil dihapus!', 'success');
            } else {
                set_flash_message('Gagal menghapus data produk.', 'danger');
            }
        }

        header('Location: index.php?page=products&action=list');
        exit;
        break;

    case 'detail':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            set_flash_message('ID Produk tidak valid.', 'danger');
            header('Location: index.php?page=products&action=list');
            exit;
        }

        $data_product = $product_manager->getById($id);

        if (!$data_product) {
            set_flash_message("Data produk dengan ID {$id} tidak ditemukan.", 'warning');
            header('Location: index.php?page=products&action=list');
            exit;
        }

        $title = "Product Detail : " . htmlspecialchars($data_product['ProductName']);
        $content = 'view/products/detail.php';
        break;
    
    default:
        // Fallback jika action tidak dikenali, arahkan ke list utama
        header('Location: index.php?page=products&action=list');
        exit;
}