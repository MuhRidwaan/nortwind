<?php
// file: classes/OrderDetail.php

class OrderDetail {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Mengambil semua item detail untuk satu OrderID.
     * @param int $orderID
     * @return array
     */
    public function getByOrderId(int $orderID): array {
        // Menggunakan JOIN untuk mendapatkan Nama Produk
        $query = "SELECT od.*, p.ProductName 
                  FROM orderdetails od
                  JOIN products p ON od.ProductID = p.ProductID
                  WHERE od.OrderID = :OrderID";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['OrderID' => $orderID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
