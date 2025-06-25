<?php
// file: pages/shippers.php

// Panggil Class Shipper
require_once 'classes/Shipper.php';
$shipper_manager = new Shipper($pdo);

// Tangkap 'action' dari URL, default-nya adalah 'list'
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$active_menu = "shippers"; // Untuk menandai menu sidebar yang aktif

switch ($action) {
    case 'list':
        $title = "Shippers List";
        require_once 'classes/Pagination.php';

        $search_term = isset($_GET['q']) ? $_GET['q'] : null;
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $records_per_page = 10;

        $total_shippers = $shipper_manager->getTotalCount($search_term);

        $base_url = "index.php?page=shippers&action=list";
        if ($search_term) {
            $base_url .= "&q=" . urlencode($search_term);
        }

        $pagination = new Pagination($total_shippers, $current_page, $records_per_page, $base_url);
        
        $list_shipper = $shipper_manager->getPaginated($pagination->getLimit(), $pagination->getOffset(), $search_term);

        $content = 'view/shippers/index.php';
        break;

    case 'create':
        $title = "Add New Shipper";
        $content = 'view/shippers/create.php';
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'CompanyName' => $_POST['CompanyName'],
                'Phone' => $_POST['Phone']
            ];

            $result = $shipper_manager->create($data);

            if ($result === true) {
                set_flash_message('Data shipper berhasil ditambahkan!', 'success');
            } else {
                set_flash_message($result, 'danger');
            }
        
            header('Location: index.php?page=shippers&action=list');
            exit;
        }
        break;

    case 'edit':
        $title = "Edit Shipper Data";
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if (!$id) {
            header('Location: index.php?page=shippers&action=list');
            exit;
        }

        $data_shipper = $shipper_manager->getById($id);
        $content = 'view/shippers/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ShipperID'];
            $data = [
                'CompanyName' => $_POST['CompanyName'],
                'Phone' => $_POST['Phone']
            ];

            if ($shipper_manager->update($id, $data)) {
                 set_flash_message('Data shipper berhasil diperbarui!', 'success');
            } else {
                 set_flash_message('Gagal memperbarui data shipper.', 'danger');
            }
            
            header('Location: index.php?page=shippers&action=list');
            exit;
        }
        break;

    case 'delete':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            if ($shipper_manager->delete($id)) {
                set_flash_message('Data shipper berhasil dihapus!', 'success');
            } else {
                set_flash_message('Gagal menghapus data shipper.', 'danger');
            }
        }

        header('Location: index.php?page=shippers&action=list');
        exit;
        break;

    case 'detail':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            set_flash_message('ID Shipper tidak valid.', 'danger');
            header('Location: index.php?page=shippers&action=list');
            exit;
        }

        $data_shipper = $shipper_manager->getById($id);

        if (!$data_shipper) {
            set_flash_message("Data shipper dengan ID {$id} tidak ditemukan.", 'warning');
            header('Location: index.php?page=shippers&action=list');
            exit;
        }

        $title = "Shipper Detail : " . htmlspecialchars($data_shipper['CompanyName']);
        $content = 'view/shippers/detail.php';
        break;
    
    default:
        header('Location: index.php?page=shippers&action=list');
        exit;
}
