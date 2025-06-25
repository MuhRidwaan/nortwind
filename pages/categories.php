<?php

require_once 'classes/Category.php';
$category = new Category($pdo);

// Tangkap 'action' dari URL, default-nya adalah 'list'
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$active_menu = "categories"; 

switch ($action) {
        case 'list':
        $title = "Category List";
        require_once 'classes/Pagination.php';

        $search_term = isset($_GET['q']) ? $_GET['q'] : null;
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $records_per_page = 10;
        $total_categories = $category->getTotalCount($search_term);
        $base_url = "index.php?page=categories&action=list";
        if ($search_term) {
            $base_url .= "&q=" . urlencode($search_term);
        }

        $pagination = new Pagination($total_categories, $current_page, $records_per_page, $base_url);
        $list_category = $category->getPaginated($pagination->getLimit(), $pagination->getOffset(), $search_term);
        $content = 'view/categories/index.php';
        break;

    case 'create':
        $title = "Add New Categories";
        $content = 'view/categories/create.php';
        break;

    case 'store': 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'CategoryName' => $_POST['CategoryName'],
                'Description' => $_POST['Description']
            ];
            $result = $category->create($data);
            if ($result === true) {
            set_flash_message('Data Categories"; berhasil ditambahkan!', 'success');
        } else { 
            set_flash_message($result, 'danger');
        }
        
        header('Location: index.php?page=categories&action=list');
            exit;
        }
        break;
   case 'edit':
        $title = "Edit Data Pelanggan";
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header('Location: index.php?page=categories&action=list');
            exit;
        }
        $data_category = $category->getById($id);
        $content = 'view/categories/edit.php';
        break;

    case 'update': 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        
            $id = $_POST['CategoryID'];
            $data = $_POST;
            $category->update($id, $data);
            header('Location: index.php?page=categories&action=list');
            exit;
        }
        break;
         case 'delete': 
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $category->delete($id);
             set_flash_message('Data Categories berhasil Dihapus!', 'success');
        }
        header('Location: index.php?page=categories&action=list');
        exit;
        break;

         case 'detail':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            set_flash_message('ID Customer tidak valid.', 'danger');
            header('Location: index.php?page=categories&action=list');
            exit;
        }

        $data_customer = $category->getById($id);

        if (!$data_customer) {
            set_flash_message("Data customer dengan ID {$id} tidak ditemukan.", 'warning');
            header('Location: index.php?page=categories&action=list');
            exit;
        }

        $title = "Detail Pelanggan: " . htmlspecialchars($data_customer['CompanyName']);
        $content = 'view/categories/detail.php'; // Arahkan ke file view baru
        break;

    default:
        $title = "categories List";
        $list_customer = $category->getAll();
        $content = 'view/categories/index.php';
        break;
}