<?php
// file: classes/Dashboard.php

class Dashboard {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Mengambil statistik ringkas untuk ditampilkan di info box.
     * @return array
     */
    public function getStats(): array {
        $stats = [];
        // Total Pendapatan
        $stats['total_revenue'] = $this->db->query("SELECT SUM(UnitPrice * Quantity * (1 - Discount)) FROM orderdetails")->fetchColumn();
        // Pesanan Baru (Bulan Ini)
        $stats['new_orders_this_month'] = $this->db->query("SELECT COUNT(OrderID) FROM orders WHERE MONTH(OrderDate) = MONTH(CURDATE()) AND YEAR(OrderDate) = YEAR(CURDATE())")->fetchColumn();
        // Total Pelanggan
        $stats['total_customers'] = $this->db->query("SELECT COUNT(CustomerID) FROM customers")->fetchColumn();
        // Produk Aktif
        $stats['total_products'] = $this->db->query("SELECT COUNT(ProductID) FROM products WHERE Discontinued = 0")->fetchColumn();
        return $stats;
    }

    /**
     * Mengambil data penjualan per bulan untuk 12 bulan terakhir.
     * @return array Data untuk ApexCharts line chart.
     */
    public function getMonthlySalesData(): array {
        $query = "SELECT 
                      DATE_FORMAT(o.OrderDate, '%Y-%m') as sales_month, 
                      SUM(od.UnitPrice * od.Quantity * (1 - od.Discount)) as monthly_revenue
                  FROM orders o
                  JOIN orderdetails od ON o.OrderID = od.OrderID
                  WHERE o.OrderDate >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                  GROUP BY sales_month
                  ORDER BY sales_month ASC";
        $results = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data = [];
        foreach ($results as $row) {
            $labels[] = date("M Y", strtotime($row['sales_month'] . "-01"));
            $data[] = round($row['monthly_revenue'], 2);
        }
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Mengambil data total penjualan berdasarkan kategori produk.
     * @return array Data untuk ApexCharts pie/donut chart.
     */
    public function getSalesByCategory(): array {
        $query = "SELECT 
                      c.CategoryName,
                      SUM(od.UnitPrice * od.Quantity * (1 - od.Discount)) as total_sales
                  FROM orderdetails od
                  JOIN products p ON od.ProductID = p.ProductID
                  JOIN categories c ON p.CategoryID = c.CategoryID
                  GROUP BY c.CategoryName
                  HAVING total_sales > 0
                  ORDER BY total_sales DESC";
        $results = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data = [];
        foreach ($results as $row) {
            $labels[] = $row['CategoryName'];
            $data[] = round($row['total_sales'], 2);
        }
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Mengambil produk terlaris berdasarkan jumlah unit yang terjual.
     * @param int $limit
     * @return array Data untuk ApexCharts bar chart.
     */
    public function getTopSellingProducts(int $limit = 5): array {
        $query = "SELECT 
                      p.ProductName, 
                      SUM(od.Quantity) as total_quantity_sold
                  FROM orderdetails od
                  JOIN products p ON od.ProductID = p.ProductID
                  GROUP BY p.ProductID, p.ProductName
                  ORDER BY total_quantity_sold DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data = [];
        // Data dibalik agar produk terlaris di atas pada grafik bar horizontal
        foreach (array_reverse($results) as $row) {
            $labels[] = $row['ProductName'];
            $data[] = $row['total_quantity_sold'];
        }
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Mengambil daftar pesanan terbaru.
     * @param int $limit
     * @return array
     */
    public function getRecentOrders(int $limit = 5): array {
        $query = "SELECT o.OrderID, c.CompanyName, 
                         (SELECT SUM(od.UnitPrice * od.Quantity * (1 - od.Discount)) 
                          FROM orderdetails od WHERE od.OrderID = o.OrderID) as total
                  FROM orders o
                  JOIN customers c ON o.CustomerID = c.CustomerID
                  ORDER BY o.OrderDate DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
