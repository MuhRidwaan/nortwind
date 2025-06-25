<?php
// file: classes/Customer.php

class Customer {
    // Properti untuk menampung koneksi database PDO
    private $db;

    /**
     * Constructor untuk class Customer.
     * Saat object Customer dibuat, kita "suntikkan" koneksi database ($pdo) ke dalamnya.
     * Ini disebut Dependency Injection, sebuah praktik yang sangat baik dalam OOP.
     *
     * @param PDO $pdo Object koneksi database PDO.
     */
    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Method untuk membuat (Create) data customer baru.
     * @param array $data Data customer yang akan disimpan, berupa array asosiatif.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function create(array $data) {
        $query = "INSERT INTO customers (CustomerID, CompanyName, ContactName, ContactTitle, Address, City, Region, PostalCode, Country, Phone, Fax) 
                  VALUES (:CustomerID, :CompanyName, :ContactName, :ContactTitle, :Address, :City, :Region, :PostalCode, :Country, :Phone, :Fax)";
        
        
        try {
            $stmt = $this->db->prepare($query);
        $stmt->execute($data);
        return true; // Kembalikan true jika berhasil
        } catch (PDOException $e) {
            // Tangani error jika terjadi (misalnya, CustomerID duplikat)
          
            // Cek jika errornya adalah karena duplikat entry (kode error 23000)
        if ($e->getCode() == 23000) {
            return "Gagal menyimpan: Customer ID '{$data['CustomerID']}' sudah terdaftar.";
        }
        
        // Untuk error database lainnya
        return "Terjadi kesalahan pada database: " . $e->getMessage();
        }
    }

    /**
     * Method untuk membaca (Read) semua data customer.
     * @return array Mengembalikan array berisi semua data customer.
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM customers ORDER BY CompanyName ASC");
        return $stmt->fetchAll();
    }

    /**
     * Method untuk membaca (Read) satu data customer berdasarkan CustomerID.
     * @param string $customerID ID unik dari customer.
     * @return mixed Mengembalikan array data customer jika ditemukan, atau false jika tidak.
     */
    public function getById(string $customerID) {
        $query = "SELECT * FROM customers WHERE CustomerID = :CustomerID";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['CustomerID' => $customerID]);
        return $stmt->fetch();
    }

    /**
     * Method untuk memperbarui (Update) data customer.
     * @param string $customerID ID customer yang akan diupdate.
     * @param array $data Data baru untuk customer.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function update(string $customerID, array $data) {
        // Hapus CustomerID dari array data karena tidak di-update di bagian SET
        unset($data['CustomerID']);

        // Bangun query SET secara dinamis dari data
        $set_parts = [];
        foreach (array_keys($data) as $key) {
            $set_parts[] = "$key = :$key";
        }
        $set_sql = implode(', ', $set_parts);
        
        $query = "UPDATE customers SET {$set_sql} WHERE CustomerID = :CustomerID";
        
        // Tambahkan lagi CustomerID ke array data untuk binding di WHERE clause
        $data['CustomerID'] = $customerID;

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Method untuk menghapus (Delete) data customer.
     * @param string $customerID ID customer yang akan dihapus.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function delete(string $customerID) {
        $query = "DELETE FROM customers WHERE CustomerID = :CustomerID";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['CustomerID' => $customerID]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTotalCount(string $searchTerm = null): int {
        $sql = "SELECT COUNT(CustomerID) FROM customers";
        $params = [];

        if ($searchTerm) {
            // Kita akan mencari di beberapa kolom sekaligus
            $sql .= " WHERE CONCAT_WS(' ', CustomerID, CompanyName, ContactName, City, Country) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * DIUBAH: Method untuk mengambil data dengan paginasi, BISA DENGAN FILTER PENCARIAN.
     * @param int $limit Jumlah data yang ingin diambil.
     * @param int $offset Posisi awal data.
     * @param string|null $searchTerm Kata kunci pencarian.
     * @return array Array berisi data customer per halaman.
     */
    public function getPaginated(int $limit, int $offset, string $searchTerm = null): array {
        $sql = "SELECT * FROM customers";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', CustomerID, CompanyName, ContactName, City, Country) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY CompanyName ASC LIMIT :limit OFFSET :offset";
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