<?php
// file: pages/orders.php

require_once 'classes/Order.php';
require_once 'classes/OrderDetail.php'; // diperlukan oleh class Order

$order_manager = new Order($pdo);
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$active_menu = "orders";

switch ($action) {
    case 'list':
        $title = "Orders List";
        require_once 'classes/Pagination.php';

        $search_term = isset($_GET['q']) ? $_GET['q'] : null;
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $records_per_page = 15;
        $total_orders = $order_manager->getTotalCount($search_term);
        $base_url = "index.php?page=orders&action=list" . ($search_term ? "&q=" . urlencode($search_term) : "");

        $pagination = new Pagination($total_orders, $current_page, $records_per_page, $base_url);
        $list_order = $order_manager->getPaginated($pagination->getLimit(), $pagination->getOffset(), $search_term);

        $content = 'view/orders/index.php';
        break;

    case 'create':
        $title = "Create New Order";
        
        // Ambil data untuk dropdowns
        $customers = $pdo->query("SELECT CustomerID, CompanyName FROM customers ORDER BY CompanyName ASC")->fetchAll(PDO::FETCH_ASSOC);
        $employees = $pdo->query("SELECT EmployeeID, FirstName, LastName FROM employees ORDER BY FirstName ASC")->fetchAll(PDO::FETCH_ASSOC);
        $shippers = $pdo->query("SELECT ShipperID, CompanyName FROM shippers ORDER BY CompanyName ASC")->fetchAll(PDO::FETCH_ASSOC);
        $products = $pdo->query("SELECT ProductID, ProductName, UnitPrice FROM products WHERE Discontinued = 0 ORDER BY ProductName ASC")->fetchAll(PDO::FETCH_ASSOC);
        
        $content = 'view/orders/create.php';
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Data master
            $orderData = [
                'CustomerID' => $_POST['CustomerID'],
                'EmployeeID' => $_POST['EmployeeID'],
                'OrderDate' => $_POST['OrderDate'] ?: null,
                'RequiredDate' => $_POST['RequiredDate'] ?: null,
                'ShippedDate' => $_POST['ShippedDate'] ?: null,
                'ShipVia' => $_POST['ShipVia'],
                'Freight' => $_POST['Freight'] ?: 0,
                'ShipName' => $_POST['ShipName'],
                'ShipAddress' => $_POST['ShipAddress'],
                'ShipCity' => $_POST['ShipCity'],
                'ShipRegion' => $_POST['ShipRegion'],
                'ShipPostalCode' => $_POST['ShipPostalCode'],
                'ShipCountry' => $_POST['ShipCountry']
            ];

            // Data detail
            $detailsData = [];
            if (isset($_POST['products']) && is_array($_POST['products'])) {
                foreach ($_POST['products'] as $prod) {
                    $detailsData[] = [
                        'ProductID' => $prod['id'],
                        'UnitPrice' => $prod['price'],
                        'Quantity' => $prod['qty'],
                        'Discount' => $prod['discount'] ?: 0
                    ];
                }
            }

            $result = $order_manager->create($orderData, $detailsData);

            if (is_int($result)) {
                set_flash_message("Pesanan dengan ID #{$result} berhasil dibuat!", 'success');
                header('Location: index.php?page=orders&action=detail&id=' . $result);
            } else {
                set_flash_message($result, 'danger'); // Tampilkan pesan error
                header('Location: index.php?page=orders&action=create');
            }
            exit;
        }
        break;

    case 'edit':
        $title = "Edit Order";
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?page=orders&action=list');
            exit;
        }

        $data_order = $order_manager->getById($id);
        if (!$data_order) {
            set_flash_message("Pesanan dengan ID {$id} tidak ditemukan.", 'warning');
            header('Location: index.php?page=orders&action=list');
            exit;
        }

        // Ambil data untuk dropdowns
        $customers = $pdo->query("SELECT CustomerID, CompanyName FROM customers ORDER BY CompanyName ASC")->fetchAll(PDO::FETCH_ASSOC);
        $employees = $pdo->query("SELECT EmployeeID, FirstName, LastName FROM employees ORDER BY FirstName ASC")->fetchAll(PDO::FETCH_ASSOC);
        $shippers = $pdo->query("SELECT ShipperID, CompanyName FROM shippers ORDER BY CompanyName ASC")->fetchAll(PDO::FETCH_ASSOC);
        $products = $pdo->query("SELECT ProductID, ProductName, UnitPrice FROM products WHERE Discontinued = 0 ORDER BY ProductName ASC")->fetchAll(PDO::FETCH_ASSOC);

        $title .= " #" . htmlspecialchars($data_order['OrderID']);
        $content = 'view/orders/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderID = isset($_POST['OrderID']) ? (int)$_POST['OrderID'] : 0;
            if (!$orderID) {
                set_flash_message("Update gagal: Order ID tidak valid.", 'danger');
                header('Location: index.php?page=orders&action=list');
                exit;
            }
            
            // Data master
            $orderData = [
                'CustomerID' => $_POST['CustomerID'],
                'EmployeeID' => $_POST['EmployeeID'],
                'OrderDate' => $_POST['OrderDate'] ?: null,
                'RequiredDate' => $_POST['RequiredDate'] ?: null,
                'ShippedDate' => $_POST['ShippedDate'] ?: null,
                'ShipVia' => $_POST['ShipVia'],
                'Freight' => $_POST['Freight'] ?: 0,
                'ShipName' => $_POST['ShipName'],
                'ShipAddress' => $_POST['ShipAddress'],
                'ShipCity' => $_POST['ShipCity'],
                'ShipRegion' => $_POST['ShipRegion'],
                'ShipPostalCode' => $_POST['ShipPostalCode'],
                'ShipCountry' => $_POST['ShipCountry']
            ];

            // Data detail
            $detailsData = [];
            if (isset($_POST['products']) && is_array($_POST['products'])) {
                foreach ($_POST['products'] as $prod) {
                    $detailsData[] = [
                        'ProductID' => $prod['id'],
                        'UnitPrice' => $prod['price'],
                        'Quantity' => $prod['qty'],
                        'Discount' => $prod['discount'] ?: 0
                    ];
                }
            }
            
            $result = $order_manager->update($orderID, $orderData, $detailsData);
            
            if ($result === true) {
                set_flash_message("Pesanan #{$orderID} berhasil diperbarui!", 'success');
                header('Location: index.php?page=orders&action=detail&id=' . $orderID);
            } else {
                set_flash_message($result, 'danger');
                header('Location: index.php?page=orders&action=edit&id=' . $orderID);
            }
            exit;
        }
        break;

    case 'detail':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?page=orders&action=list');
            exit;
        }
        $data_order = $order_manager->getById($id);
        if (!$data_order) {
            set_flash_message("Pesanan dengan ID {$id} tidak ditemukan.", 'warning');
            header('Location: index.php?page=orders&action=list');
            exit;
        }
        $title = "Order Detail #" . htmlspecialchars($data_order['OrderID']);
        $content = 'view/orders/detail.php';
        break;

         case 'shipping_list':
        $active_menu = "shipping"; // Ganti menu aktif ke "shipping"
        $title = "Daftar Pengiriman";
        require_once 'classes/Pagination.php';

        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $records_per_page = 20;
        $total_orders_to_ship = $order_manager->getUnshippedOrdersCount();

        $base_url = "index.php?page=orders&action=shipping_list";
        $pagination = new Pagination($total_orders_to_ship, $current_page, $records_per_page, $base_url);
        
        $list_order = $order_manager->getUnshippedOrdersPaginated($pagination->getLimit(), $pagination->getOffset());

        $content = 'view/orders/shipping_list.php';
        break;

    case 'ship_now':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id) {
            if ($order_manager->shipOrder($id)) {
                set_flash_message("Pesanan #{$id} berhasil ditandai sebagai telah dikirim.", 'success');
            } else {
                set_flash_message("Gagal memperbarui status pengiriman untuk pesanan #{$id}.", 'danger');
            }
        }
        header('Location: index.php?page=orders&action=shipping_list');
        exit;
        break;
        
    case 'delete':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id) {
            if ($order_manager->delete($id)) {
                set_flash_message("Pesanan #{$id} berhasil dihapus.", 'success');
            } else {
                set_flash_message("Gagal menghapus pesanan #{$id}.", 'danger');
            }
        }
        header('Location: index.php?page=orders&action=list');
        exit;
        break;
        
    default:
        header('Location: index.php?page=orders&action=list');
        exit;
}
