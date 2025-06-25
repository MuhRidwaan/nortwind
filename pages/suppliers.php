<?php
// file: pages/suppliers.php

// Panggil Class Supplier
require_once 'classes/Supplier.php';
$supplier_manager = new Supplier($pdo);

// Tangkap 'action' dari URL, default-nya adalah 'list'
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$active_menu = "suppliers"; // Untuk menandai menu sidebar yang aktif

switch ($action) {
    case 'list':
        $title = "Suppliers List";
        require_once 'classes/Pagination.php';

        $search_term = isset($_GET['q']) ? $_GET['q'] : null;
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $records_per_page = 10;

        $total_suppliers = $supplier_manager->getTotalCount($search_term);

        $base_url = "index.php?page=suppliers&action=list";
        if ($search_term) {
            $base_url .= "&q=" . urlencode($search_term);
        }

        $pagination = new Pagination($total_suppliers, $current_page, $records_per_page, $base_url);
        
        $list_supplier = $supplier_manager->getPaginated($pagination->getLimit(), $pagination->getOffset(), $search_term);

        $content = 'view/suppliers/index.php';
        break;

    case 'create':
        $title = "Add New Supplier";
        $content = 'view/suppliers/create.php';
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
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

            $result = $supplier_manager->create($data);

            if ($result === true) {
                set_flash_message('Data supplier berhasil ditambahkan!', 'success');
            } else {
                set_flash_message($result, 'danger');
            }
        
            header('Location: index.php?page=suppliers&action=list');
            exit;
        }
        break;

    case 'edit':
        $title = "Edit Supplier Data";
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if (!$id) {
            header('Location: index.php?page=suppliers&action=list');
            exit;
        }

        $data_supplier = $supplier_manager->getById($id);
        $content = 'view/suppliers/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['SupplierID'];
            // Cukup kirim $_POST karena key-nya sudah sesuai dengan nama kolom
            $data = $_POST;

            if ($supplier_manager->update($id, $data)) {
                 set_flash_message('Data supplier berhasil diperbarui!', 'success');
            } else {
                 set_flash_message('Gagal memperbarui data supplier.', 'danger');
            }
            
            header('Location: index.php?page=suppliers&action=list');
            exit;
        }
        break;

    case 'delete':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            if ($supplier_manager->delete($id)) {
                set_flash_message('Data supplier berhasil dihapus!', 'success');
            } else {
                set_flash_message('Gagal menghapus data supplier.', 'danger');
            }
        }

        header('Location: index.php?page=suppliers&action=list');
        exit;
        break;

    case 'detail':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            set_flash_message('ID Supplier tidak valid.', 'danger');
            header('Location: index.php?page=suppliers&action=list');
            exit;
        }

        $data_supplier = $supplier_manager->getById($id);

        if (!$data_supplier) {
            set_flash_message("Data supplier dengan ID {$id} tidak ditemukan.", 'warning');
            header('Location: index.php?page=suppliers&action=list');
            exit;
        }

        $title = "Supplier Detail : " . htmlspecialchars($data_supplier['CompanyName']);
        $content = 'view/suppliers/detail.php';
        break;
    
    default:
        header('Location: index.php?page=suppliers&action=list');
        exit;
}
