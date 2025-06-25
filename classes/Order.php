<?php
// file: classes/Order.php

class Order {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Membuat order baru beserta detailnya menggunakan transaksi.
     * @param array $orderData Data untuk tabel 'orders'.
     * @param array $detailsData Array dari item detail untuk 'orderdetails'.
     * @return int|string OrderID jika berhasil, pesan error jika gagal.
     */
    public function create(array $orderData, array $detailsData) {
        // Cek jika ada detail produk
        if (empty($detailsData)) {
            return "Tidak ada produk yang ditambahkan ke dalam pesanan.";
        }

        try {
            $this->db->beginTransaction();

            // 1. Masukkan data ke tabel 'orders'
            $queryOrder = "INSERT INTO orders (CustomerID, EmployeeID, OrderDate, RequiredDate, ShippedDate, ShipVia, Freight, ShipName, ShipAddress, ShipCity, ShipRegion, ShipPostalCode, ShipCountry) 
                           VALUES (:CustomerID, :EmployeeID, :OrderDate, :RequiredDate, :ShippedDate, :ShipVia, :Freight, :ShipName, :ShipAddress, :ShipCity, :ShipRegion, :ShipPostalCode, :ShipCountry)";
            $stmtOrder = $this->db->prepare($queryOrder);
            $stmtOrder->execute($orderData);
            $orderID = $this->db->lastInsertId();

            // 2. Masukkan data ke tabel 'orderdetails'
            $queryDetail = "INSERT INTO orderdetails (OrderID, ProductID, UnitPrice, Quantity, Discount) 
                            VALUES (:OrderID, :ProductID, :UnitPrice, :Quantity, :Discount)";
            $stmtDetail = $this->db->prepare($queryDetail);

            foreach ($detailsData as $item) {
                $stmtDetail->execute([
                    'OrderID' => $orderID,
                    'ProductID' => $item['ProductID'],
                    'UnitPrice' => $item['UnitPrice'],
                    'Quantity' => $item['Quantity'],
                    'Discount' => $item['Discount']
                ]);
            }

            $this->db->commit();
            return (int)$orderID;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return "Gagal membuat pesanan: " . $e->getMessage();
        }
    }

     public function shipOrder(int $orderID): bool {
        // Menggunakan NOW() dari MySQL untuk waktu server yang akurat
        $query = "UPDATE orders SET ShippedDate = NOW() WHERE OrderID = :OrderID AND ShippedDate IS NULL";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['OrderID' => $orderID]);
    }

    public function getUnshippedOrdersCount(): int {
        $sql = "SELECT COUNT(OrderID) FROM orders WHERE ShippedDate IS NULL";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

        public function getUnshippedOrdersPaginated(int $limit, int $offset): array {
        $sql = "SELECT o.OrderID, o.OrderDate, o.RequiredDate, o.ShipName, c.CompanyName as CustomerName
                FROM orders o
                LEFT JOIN customers c ON o.CustomerID = c.CustomerID
                WHERE o.ShippedDate IS NULL
                ORDER BY o.RequiredDate ASC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Memperbarui order beserta detailnya menggunakan transaksi.
     * @param int $orderID ID dari order yang akan diupdate.
     * @param array $orderData Data baru untuk tabel 'orders'.
     * @param array $detailsData Array dari item detail baru untuk 'orderdetails'.
     * @return bool|string True jika berhasil, pesan error jika gagal.
     */
    public function update(int $orderID, array $orderData, array $detailsData) {
        if (empty($detailsData)) {
            return "Pesanan harus memiliki setidaknya satu produk.";
        }

        try {
            $this->db->beginTransaction();

            // 1. Update data master di tabel 'orders'
            $orderData['OrderID'] = $orderID; // Tambahkan OrderID untuk binding
            $queryOrder = "UPDATE orders SET 
                                CustomerID = :CustomerID, EmployeeID = :EmployeeID, OrderDate = :OrderDate, RequiredDate = :RequiredDate, 
                                ShippedDate = :ShippedDate, ShipVia = :ShipVia, Freight = :Freight, ShipName = :ShipName, ShipAddress = :ShipAddress, 
                                ShipCity = :ShipCity, ShipRegion = :ShipRegion, ShipPostalCode = :ShipPostalCode, ShipCountry = :ShipCountry
                           WHERE OrderID = :OrderID";
            $stmtOrder = $this->db->prepare($queryOrder);
            $stmtOrder->execute($orderData);

            // 2. Hapus semua detail order yang lama
            $stmtDelete = $this->db->prepare("DELETE FROM orderdetails WHERE OrderID = :OrderID");
            $stmtDelete->execute(['OrderID' => $orderID]);

            // 3. Masukkan kembali detail order yang baru
            $queryDetail = "INSERT INTO orderdetails (OrderID, ProductID, UnitPrice, Quantity, Discount) 
                            VALUES (:OrderID, :ProductID, :UnitPrice, :Quantity, :Discount)";
            $stmtDetail = $this->db->prepare($queryDetail);

            foreach ($detailsData as $item) {
                $stmtDetail->execute([
                    'OrderID' => $orderID,
                    'ProductID' => $item['ProductID'],
                    'UnitPrice' => $item['UnitPrice'],
                    'Quantity' => $item['Quantity'],
                    'Discount' => $item['Discount']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return "Gagal memperbarui pesanan: " . $e->getMessage();
        }
    }


    /**
     * Mengambil data order tunggal beserta detail produk dan totalnya.
     * @param int $OrderID
     * @return array|false
     */
    public function getById(int $OrderID) {
        $query = "SELECT o.*, c.CompanyName as CustomerName, 
                         e.FirstName, e.LastName, s.CompanyName as ShipperName
                  FROM orders o
                  LEFT JOIN customers c ON o.CustomerID = c.CustomerID
                  LEFT JOIN employees e ON o.EmployeeID = e.EmployeeID
                  LEFT JOIN shippers s ON o.ShipVia = s.ShipperID
                  WHERE o.OrderID = :OrderID";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['OrderID' => $OrderID]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            // Ambil detail order
            require_once 'OrderDetail.php';
            $orderDetailManager = new OrderDetail($this->db);
            $order['Details'] = $orderDetailManager->getByOrderId($OrderID);

            // Hitung total
            $total = 0;
            foreach ($order['Details'] as $detail) {
                $total += $detail['UnitPrice'] * $detail['Quantity'] * (1 - $detail['Discount']);
            }
            $order['TotalAmount'] = $total;
        }

        return $order;
    }

    /**
     * Menghapus order beserta detailnya menggunakan transaksi.
     * @param int $OrderID
     * @return bool
     */
    public function delete(int $OrderID): bool {
        try {
            $this->db->beginTransaction();
            // Hapus dari orderdetails terlebih dahulu
            $stmtDetails = $this->db->prepare("DELETE FROM orderdetails WHERE OrderID = :OrderID");
            $stmtDetails->execute(['OrderID' => $OrderID]);
            // Hapus dari orders
            $stmtOrder = $this->db->prepare("DELETE FROM orders WHERE OrderID = :OrderID");
            $stmtOrder->execute(['OrderID' => $OrderID]);
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getTotalCount(string $searchTerm = null): int {
        $sql = "SELECT COUNT(o.OrderID) 
                FROM orders o
                LEFT JOIN customers c ON o.CustomerID = c.CustomerID";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', o.OrderID, c.CompanyName, o.ShipName) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
   
    public function getPaginated(int $limit, int $offset, string $searchTerm = null): array {
        $sql = "SELECT o.OrderID, o.OrderDate, o.ShippedDate, o.ShipName, c.CompanyName as CustomerName
                FROM orders o
                LEFT JOIN customers c ON o.CustomerID = c.CustomerID";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', o.OrderID, c.CompanyName, o.ShipName) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY o.OrderDate DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => &$val) {
            if ($key == ':limit' || $key == ':offset') {
                $stmt->bindParam($key, $val, PDO::PARAM_INT);
            } else {
                $stmt->bindParam($key, $val, PDO::PARAM_STR);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
