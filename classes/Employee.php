<?php
// file: classes/Employee.php

class Employee {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }
   
    public function create(array $data) {
        // Query disesuaikan dengan semua kolom di tabel employees, mengikuti style asli (hardcoded)
        $query = "INSERT INTO employees (LastName, FirstName, Title, TitleOfCourtesy, BirthDate, HireDate, Address, City, Region, PostalCode, Country, HomePhone, Extension, Notes, ReportsTo) 
                  VALUES (:LastName, :FirstName, :Title, :TitleOfCourtesy, :BirthDate, :HireDate, :Address, :City, :Region, :PostalCode, :Country, :HomePhone, :Extension, :Notes, :ReportsTo)";
        
        try {
            $stmt = $this->db->prepare($query);
            // $data harus berisi key yang sesuai dengan placeholder di query
            $stmt->execute($data); 
            return true; // Kembalikan true jika berhasil
        } catch (PDOException $e) {
            // Error handling dipertahankan seperti aslinya, namun pesan disesuaikan
            if ($e->getCode() == 23000) {
                // Error ini terjadi jika ada pelanggaran UNIQUE constraint
                return "Gagal menyimpan: Data yang dimasukkan mungkin sudah ada atau melanggar aturan unik.";
            }
        
            // Untuk error database lainnya
            return "Terjadi kesalahan pada database: " . $e->getMessage();
        }
    }

    public function getAll() {
        // Tabel dan urutan diubah
        $stmt = $this->db->query("SELECT * FROM employees ORDER BY LastName ASC, FirstName ASC");
        return $stmt->fetchAll();
    }

    public function getById(string $EmployeeID) {
        // Tabel dan nama kolom diubah
        $query = "SELECT * FROM employees WHERE EmployeeID = :EmployeeID";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['EmployeeID' => $EmployeeID]);
        return $stmt->fetch();
    }
 
    public function update(string $EmployeeID, array $data) {
        // Logika dinamis ini dipertahankan karena sudah ada di style asli Anda
        unset($data['EmployeeID']); // Menghapus EmployeeID dari data untuk SET

        $set_parts = [];
        foreach (array_keys($data) as $key) {
            $set_parts[] = "$key = :$key";
        }
        $set_sql = implode(', ', $set_parts);
        
        // Tabel dan nama kolom diubah
        $query = "UPDATE employees SET {$set_sql} WHERE EmployeeID = :EmployeeID";
        
        // Tambahkan lagi EmployeeID ke array data untuk binding di WHERE clause
        $data['EmployeeID'] = $EmployeeID;

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }
   
    public function delete(string $EmployeeID) {
        // Tabel dan nama kolom diubah
        $query = "DELETE FROM employees WHERE EmployeeID = :EmployeeID";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['EmployeeID' => $EmployeeID]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTotalCount(string $searchTerm = null): int {
        // Tabel, kolom COUNT, dan kolom pencarian diubah
        $sql = "SELECT COUNT(EmployeeID) FROM employees";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', EmployeeID, FirstName, LastName, Title, City, Country) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
   
    public function getPaginated(int $limit, int $offset, string $searchTerm = null): array {
        // Tabel, kolom pencarian, dan urutan diubah
        $sql = "SELECT * FROM employees";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', EmployeeID, FirstName, LastName, Title, City, Country) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY LastName ASC, FirstName ASC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = $this->db->prepare($sql);
        
        // Logika binding parameter dinamis ini dipertahankan karena bagian dari style asli
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