<?php
// file: pages/employees.php

// Panggil Class Employee
require_once 'classes/Employee.php';
$employee_manager = new Employee($pdo);

// Tangkap 'action' dari URL, default-nya adalah 'list'
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$active_menu = "employees"; // Menu sidebar akan selalu aktif untuk semua action employee

switch ($action) {
    case 'list':
        $title = "Employees List";
        require_once 'classes/Pagination.php';

        // 1. Tangkap kata kunci pencarian dari URL
        $search_term = isset($_GET['q']) ? $_GET['q'] : null;

        // 2. Tentukan halaman & data per halaman
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $records_per_page = 10; // Anda bisa sesuaikan jumlah ini

        // 3. Dapatkan total data dengan mempertimbangkan pencarian
        $total_employees = $employee_manager->getTotalCount($search_term);

        // 4. Buat URL dasar untuk link pagination, sertakan kata kunci pencarian jika ada
        $base_url = "index.php?page=employees&action=list";
        if ($search_term) {
            $base_url .= "&q=" . urlencode($search_term);
        }

        // 5. Buat object Pagination
        $pagination = new Pagination($total_employees, $current_page, $records_per_page, $base_url);
        
        // 6. Ambil data yang sudah dipaginasi dan difilter pencarian
        $list_employee = $employee_manager->getPaginated($pagination->getLimit(), $pagination->getOffset(), $search_term);

        // 7. Tentukan file view
        $content = 'view/employees/index.php';
        break;

    case 'create':
        $title = "Add New Employee";
        $content = 'view/employees/create.php';
        break;

    case 'store': // Untuk memproses data dari form tambah
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Siapkan data dari form
            $data = [
                'LastName' => $_POST['LastName'],
                'FirstName' => $_POST['FirstName'],
                'Title' => $_POST['Title'],
                'TitleOfCourtesy' => $_POST['TitleOfCourtesy'],
                'BirthDate' => $_POST['BirthDate'] ?: null, // Set null jika kosong
                'HireDate' => $_POST['HireDate'] ?: null, // Set null jika kosong
                'Address' => $_POST['Address'],
                'City' => $_POST['City'],
                'Region' => $_POST['Region'],
                'PostalCode' => $_POST['PostalCode'],
                'Country' => $_POST['Country'],
                'HomePhone' => $_POST['HomePhone'],
                'Extension' => $_POST['Extension'],
                'Notes' => $_POST['Notes'],
                'ReportsTo' => $_POST['ReportsTo'] ?: null // Set null jika kosong
            ];

            $result = $employee_manager->create($data);

            if ($result === true) {
                set_flash_message('Data karyawan berhasil ditambahkan!', 'success');
            } else {
                set_flash_message($result, 'danger'); // Tampilkan pesan error dari class
            }
        
            header('Location: index.php?page=employees&action=list');
            exit;
        }
        break;

    case 'edit': // Untuk menampilkan form edit
        $title = "Edit Employee Data";
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if (!$id) {
            header('Location: index.php?page=employees&action=list');
            exit;
        }

        $data_employee = $employee_manager->getById($id);
        $content = 'view/employees/edit.php';
        break;

    case 'update': // Untuk memproses data dari form edit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['EmployeeID'];
            $data = $_POST;

            if ($employee_manager->update($id, $data)) {
                set_flash_message('Data karyawan berhasil diperbarui!', 'success');
            } else {
                set_flash_message('Gagal memperbarui data karyawan.', 'danger');
            }
            
            header('Location: index.php?page=employees&action=list');
            exit;
        }
        break;

    case 'delete': // Untuk menghapus data
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            if ($employee_manager->delete($id)) {
                set_flash_message('Data karyawan berhasil dihapus!', 'success');
            } else {
                set_flash_message('Gagal menghapus data karyawan.', 'danger');
            }
        }

        header('Location: index.php?page=employees&action=list');
        exit;
        break;

    case 'detail': // Untuk menampilkan detail
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            set_flash_message('ID Karyawan tidak valid.', 'danger');
            header('Location: index.php?page=employees&action=list');
            exit;
        }

        $data_employee = $employee_manager->getById($id);

        if (!$data_employee) {
            set_flash_message("Data karyawan dengan ID {$id} tidak ditemukan.", 'warning');
            header('Location: index.php?page=employees&action=list');
            exit;
        }

        $title = "Employee Detail : " . htmlspecialchars($data_employee['FirstName'] . ' ' . $data_employee['LastName']);
        $content = 'view/employees/detail.php';
        break;
    
    default:
        $title = "Employees List";
        // Fallback ke method getPaginated agar konsisten dengan 'list'
        header('Location: index.php?page=employees&action=list');
        exit;
}