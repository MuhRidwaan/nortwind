<?php
// file: classes/Product.php

class Product {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }
   
    public function create(array $data) {
        // Query INSERT disesuaikan dengan kolom tabel products
        $query = "INSERT INTO products (ProductName, SupplierID, CategoryID, QuantityPerUnit, UnitPrice, UnitsInStock, UnitsOnOrder, ReorderLevel, Discontinued) 
                  VALUES (:ProductName, :SupplierID, :CategoryID, :QuantityPerUnit, :UnitPrice, :UnitsInStock, :UnitsOnOrder, :ReorderLevel, :Discontinued)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($data);
            return true;
        } catch (PDOException $e) {
            // Error handling konsisten dengan class sebelumnya
            if ($e->getCode() == 23000) {
                return "Gagal menyimpan: Data yang dimasukkan mungkin sudah ada atau melanggar aturan unik.";
            }
            return "Terjadi kesalahan pada database: " . $e->getMessage();
        }
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY ProductName ASC");
        return $stmt->fetchAll();
    }

 public function getById(string $ProductID) {
        // Query disempurnakan untuk mengambil nama kategori dan nama supplier
        $query = "SELECT p.*, c.CategoryName, s.CompanyName AS SupplierName
                  FROM products p
                  LEFT JOIN categories c ON p.CategoryID = c.CategoryID
                  LEFT JOIN suppliers s ON p.SupplierID = s.SupplierID
                  WHERE p.ProductID = :ProductID";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['ProductID' => $ProductID]);
        return $stmt->fetch();
    }
 
    public function update(string $ProductID, array $data) {
        // Logika update dinamis dipertahankan sesuai style
        unset($data['ProductID']);

        $set_parts = [];
        foreach (array_keys($data) as $key) {
            $set_parts[] = "$key = :$key";
        }
        $set_sql = implode(', ', $set_parts);
        
        $query = "UPDATE products SET {$set_sql} WHERE ProductID = :ProductID";
        
        $data['ProductID'] = $ProductID;

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }
   
    public function delete(string $ProductID) {
        $query = "DELETE FROM products WHERE ProductID = :ProductID";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['ProductID' => $ProductID]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTotalCount(string $searchTerm = null): int {
        // Menggunakan JOIN agar bisa mencari berdasarkan nama kategori juga
        $sql = "SELECT COUNT(p.ProductID) 
                FROM products p
                LEFT JOIN categories c ON p.CategoryID = c.CategoryID";
        $params = [];

        if ($searchTerm) {
            // Mencari di Nama Produk dan Nama Kategori
            $sql .= " WHERE CONCAT_WS(' ', p.ProductName, c.CategoryName) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
   
    public function getPaginated(int $limit, int $offset, string $searchTerm = null): array {
        // Menggunakan JOIN untuk mengambil CategoryName untuk ditampilkan di daftar produk
        $sql = "SELECT p.ProductID, p.ProductName, p.UnitPrice, p.UnitsInStock, p.Discontinued, c.CategoryName 
                FROM products p
                LEFT JOIN categories c ON p.CategoryID = c.CategoryID";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', p.ProductName, c.CategoryName) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY p.ProductName ASC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = $this->db->prepare($sql);
        
        // Binding parameter dinamis dipertahankan
        foreach ($params as $key => &$val) {
            if ($key == ':limit' || $key == ':offset') {
                $stmt->bindParam($key, $val, PDO::PARAM_INT);
            } else {
                $stmt->bindParam($key, $val, PDO::PARAM_STR);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
}