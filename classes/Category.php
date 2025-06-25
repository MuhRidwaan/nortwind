<?php
// file: classes/Customer.php

class Category {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }
   
    public function create(array $data) {

       
       $query = "INSERT INTO categories (CategoryName, Description) 
                  VALUES (:CategoryName, :Description)";
        
            try {
           $stmt = $this->db->prepare($query);
            $stmt->execute($data);
        return true; // Kembalikan true jika berhasil
        } catch (PDOException $e) {
            // Cek jika errornya adalah karena duplikat entry (kode error 23000)
        if ($e->getCode() == 23000) {
            return "Gagal menyimpan: Customer ID '{$data['CustomerID']}' sudah terdaftar.";
        }
        
            // Untuk error database lainnya
            return "Terjadi kesalahan pada database: " . $e->getMessage();
        }
    }


    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY CategoryName ASC");
        return $stmt->fetchAll();
    }


    public function getById(string $CategoryID) {
        $query = "SELECT * FROM categories WHERE CategoryID = :CategoryID";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['CategoryID' => $CategoryID]);
        return $stmt->fetch();
    }

 
    public function update(string $CategoryID, array $data) {
        // Hapus CategoryID dari array data karena tidak di-update di bagian SET
        unset($data['CategoryID']);

        // Bangun query SET secara dinamis dari data
        $set_parts = [];
        foreach (array_keys($data) as $key) {
            $set_parts[] = "$key = :$key";
        }
        $set_sql = implode(', ', $set_parts);
        
        $query = "UPDATE categories SET {$set_sql} WHERE CategoryID = :CategoryID";
        
        // Tambahkan lagi CategoryID ke array data untuk binding di WHERE clause
        $data['CategoryID'] = $CategoryID;

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }

   
    public function delete(string $CategoryID) {
        $query = "DELETE FROM categories WHERE CategoryID = :CategoryID";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['CategoryID' => $CategoryID]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTotalCount(string $searchTerm = null): int {
        $sql = "SELECT COUNT(CategoryID) FROM categories";
        $params = [];

        if ($searchTerm) {
            // Kita akan mencari di beberapa kolom sekaligus
            $sql .= " WHERE CONCAT_WS(' ', CategoryID, CategoryName, Description) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

   
    public function getPaginated(int $limit, int $offset, string $searchTerm = null): array {
        $sql = "SELECT * FROM categories";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', CategoryID, CategoryName, Description) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY CategoryName ASC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = $this->db->prepare($sql);
        
        // Binding parameter secara dinamis
        foreach ($params as $key => &$val) {
            // Untuk LIMIT dan OFFSET, kita harus bind sebagai integer
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