<?php
// file: pages/customers.php (versi baru yang lebih canggih)

// print_r($_GET);
// exit;
// Panggil Class Customer
require_once 'classes/Customer.php';
$customer_manager = new Customer($pdo);

// Tangkap 'action' dari URL, default-nya adalah 'list'
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$active_menu = "customers"; // Menu sidebar akan selalu aktif untuk semua action customer

switch ($action) {
        case 'list':
        $title = "Customers List";
        require_once 'classes/Pagination.php';

        // 1. TANGKAP KATA KUNCI PENCARIAN DARI URL
        $search_term = isset($_GET['q']) ? $_GET['q'] : null;

        // 2. Tentukan halaman & data per halaman
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $records_per_page = 10;

        // 3. Dapatkan total data DENGAN MEMPERTIMBANGKAN PENCARIAN
        $total_customers = $customer_manager->getTotalCount($search_term);

        // 4. Buat URL dasar untuk link pagination, SERTAKAN KATA KUNCI PENCARIAN JIKA ADA
        $base_url = "index.php?page=customers&action=list";
        if ($search_term) {
            // Gunakan urlencode untuk memastikan karakter aneh di URL aman
            $base_url .= "&q=" . urlencode($search_term);
        }

        // 5. Buat object Pagination
        $pagination = new Pagination($total_customers, $current_page, $records_per_page, $base_url);
        
        // 6. Ambil data yang sudah dipaginasi DAN DIFILTER PENCARIAN
        $list_customer = $customer_manager->getPaginated($pagination->getLimit(), $pagination->getOffset(), $search_term);

        // 7. Tentukan file view
        $content = 'view/customers/index.php';
        break;

    case 'create':
        $title = "Add New Customer";
        $content = 'view/customers/create.php';
        break;

    case 'store': // CASE BARU: Untuk memproses data dari form
        // Cek jika request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Siapkan data dalam array yang kuncinya sama dengan kolom di DB
            $data = [
                'CustomerID' => $_POST['CustomerID'],
                'CompanyName' => $_POST['CompanyName'],
                'ContactName' => $_POST['ContactName'],
                'ContactTitle' => $_POST['ContactTitle'],
                'Address' => $_POST['Address'],
                'City' => $_POST['City'],
                'Region' => $_POST['Region'],
                'PostalCode' => $_POST['PostalCode'],
                'Country' => $_POST['Country'],
                'Phone' => $_POST['Phone'],
                'Fax' => $_POST['Fax']
            ];

            // Panggil method create dari class Customer
            $result = $customer_manager->create($data);

            // Redirect kembali ke halaman daftar setelah menyimpan
            // (Nanti bisa ditambahkan notifikasi sukses/gagal)
            if ($result === true) {
            // Jika hasilnya adalah boolean 'true', berarti sukses
            set_flash_message('Data customer berhasil ditambahkan!', 'success');
        } else {
            // Jika bukan, berarti hasilnya adalah string pesan error
            set_flash_message($result, 'danger');
        }
        
        header('Location: index.php?page=customers&action=list');
            exit;
        }
        // Jika diakses langsung tanpa POST, tidak terjadi apa-apa
        break;
   case 'edit': // CASE BARU: Untuk menampilkan form edit
        $title = "Edit Customer Data";
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        // Jika ID tidak ada, redirect kembali ke halaman daftar
        if (!$id) {
            header('Location: index.php?page=customers&action=list');
            exit;
        }

        // Ambil data customer yang spesifik berdasarkan ID
        $data_customer = $customer_manager->getById($id);
        
        // Tentukan file view yang akan digunakan
        $content = 'view/customers/edit.php';
        break;

    case 'update': // CASE BARU: Untuk memproses data dari form edit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil ID dan data dari form
            $id = $_POST['CustomerID'];
            $data = $_POST;

            // Panggil method update
            $customer_manager->update($id, $data);
            
            // Redirect kembali ke halaman daftar
            header('Location: index.php?page=customers&action=list');
            exit;
        }
        break;
         case 'delete': // <-- CASE BARU UNTUK HAPUS DATA
        // Ambil ID dari URL
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        // Jika ID ada, panggil method delete
        if ($id) {
            $customer_manager->delete($id);
        }

        // Redirect kembali ke halaman daftar setelah menghapus
        header('Location: index.php?page=customers&action=list');
        exit;
        break;

         case 'detail': // <-- CASE BARU UNTUK MENAMPILKAN DETAIL
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            set_flash_message('ID Customer tidak valid.', 'danger');
            header('Location: index.php?page=customers&action=list');
            exit;
        }

        $data_customer = $customer_manager->getById($id);

        if (!$data_customer) {
            set_flash_message("Data customer dengan ID {$id} tidak ditemukan.", 'warning');
            header('Location: index.php?page=customers&action=list');
            exit;
        }

        $title = "Customer Detail : " . htmlspecialchars($data_customer['CompanyName']);
        $content = 'view/customers/detail.php'; // Arahkan ke file view baru
        break;

    // Tambahkan case 'update' dan 'delete' di sini nanti
    
    default:
        $title = "Customers List";
        $list_customer = $customer_manager->getAll();
        $content = 'view/customers/index.php';
        break;
}