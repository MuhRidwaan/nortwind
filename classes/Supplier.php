<?php
// file: classes/Supplier.php

class Supplier {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }
   
    public function create(array $data) {
        $query = "INSERT INTO suppliers (CompanyName, ContactName, ContactTitle, Address, City, Region, PostalCode, Country, Phone, Fax) 
                  VALUES (:CompanyName, :ContactName, :ContactTitle, :Address, :City, :Region, :PostalCode, :Country, :Phone, :Fax)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($data);
            return true;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "Gagal menyimpan: Nama perusahaan supplier mungkin sudah terdaftar.";
            }
            return "Terjadi kesalahan pada database: " . $e->getMessage();
        }
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM suppliers ORDER BY CompanyName ASC");
        return $stmt->fetchAll();
    }

    public function getById(string $SupplierID) {
        $query = "SELECT * FROM suppliers WHERE SupplierID = :SupplierID";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['SupplierID' => $SupplierID]);
        return $stmt->fetch();
    }
 
    public function update(string $SupplierID, array $data) {
        unset($data['SupplierID']);

        $set_parts = [];
        foreach (array_keys($data) as $key) {
            $set_parts[] = "$key = :$key";
        }
        $set_sql = implode(', ', $set_parts);
        
        $query = "UPDATE suppliers SET {$set_sql} WHERE SupplierID = :SupplierID";
        
        $data['SupplierID'] = $SupplierID;

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }
   
    public function delete(string $SupplierID) {
        $query = "DELETE FROM suppliers WHERE SupplierID = :SupplierID";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['SupplierID' => $SupplierID]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTotalCount(string $searchTerm = null): int {
        $sql = "SELECT COUNT(SupplierID) FROM suppliers";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', CompanyName, ContactName, City, Country) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
   
    public function getPaginated(int $limit, int $offset, string $searchTerm = null): array {
        $sql = "SELECT * FROM suppliers";
        $params = [];

        if ($searchTerm) {
             $sql .= " WHERE CONCAT_WS(' ', CompanyName, ContactName, City, Country) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY CompanyName ASC LIMIT :limit OFFSET :offset";
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
        return $stmt->fetchAll();
    }
}
