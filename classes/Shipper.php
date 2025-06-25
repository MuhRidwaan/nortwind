<?php
// file: classes/Shipper.php

class Shipper {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }
   
    public function create(array $data) {
        $query = "INSERT INTO shippers (CompanyName, Phone) 
                  VALUES (:CompanyName, :Phone)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($data);
            return true;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "Gagal menyimpan: Nama perusahaan jasa pengiriman mungkin sudah ada.";
            }
            return "Terjadi kesalahan pada database: " . $e->getMessage();
        }
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM shippers ORDER BY CompanyName ASC");
        return $stmt->fetchAll();
    }

    public function getById(string $ShipperID) {
        $query = "SELECT * FROM shippers WHERE ShipperID = :ShipperID";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['ShipperID' => $ShipperID]);
        return $stmt->fetch();
    }
 
    public function update(string $ShipperID, array $data) {
        unset($data['ShipperID']);

        $set_parts = [];
        foreach (array_keys($data) as $key) {
            $set_parts[] = "$key = :$key";
        }
        $set_sql = implode(', ', $set_parts);
        
        $query = "UPDATE shippers SET {$set_sql} WHERE ShipperID = :ShipperID";
        
        $data['ShipperID'] = $ShipperID;

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }
   
    public function delete(string $ShipperID) {
        $query = "DELETE FROM shippers WHERE ShipperID = :ShipperID";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['ShipperID' => $ShipperID]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTotalCount(string $searchTerm = null): int {
        $sql = "SELECT COUNT(ShipperID) FROM shippers";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', CompanyName, Phone) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
   
    public function getPaginated(int $limit, int $offset, string $searchTerm = null): array {
        $sql = "SELECT * FROM shippers";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', CompanyName, Phone) LIKE :searchTerm";
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