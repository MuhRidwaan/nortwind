<?php
// file: pages/dashboard.php

require_once 'classes/Dashboard.php';
$dashboard_manager = new Dashboard($pdo);

// Ambil semua data yang diperlukan untuk dashboard
$stats = $dashboard_manager->getStats();
$sales_chart_data = $dashboard_manager->getMonthlySalesData();
$sales_by_category_data = $dashboard_manager->getSalesByCategory();
$top_products_data = $dashboard_manager->getTopSellingProducts(5);
$recent_orders = $dashboard_manager->getRecentOrders(5);

// Set variabel untuk view
$title = "Dashboard";
$active_menu = "dashboard";
$content = 'view/dashboard/index.php';
