<?php
// file: config.php

// --- PENGATURAN DATABASE ---
// Atur sesuai dengan kredensial database Anda.
define('DB_HOST', 'localhost');      // Host database, biasanya 'localhost'
define('DB_NAME', 'nortwind');       // Nama database yang kamu sebutkan
define('DB_USER', 'root');           // Username database
define('DB_PASS', '');               // Password database, kosongkan jika tidak ada
define('DB_CHARSET', 'utf8mb4');     // Set karakter untuk mendukung semua jenis karakter

// --- PENGATURAN KONEKSI (JANGAN DIUBAH JIKA TIDAK PERLU) ---

// DSN (Data Source Name) - String yang memberitahu PDO cara konek.
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

// Opsi untuk koneksi PDO
$options = [
    // Atur mode error untuk melempar exception, agar error bisa ditangkap
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // Atur mode pengambilan data default menjadi array asosiatif (nama kolom => nilai)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Nonaktifkan emulasi prepared statements untuk keamanan ekstra dengan MySQL modern
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// --- BUAT KONEKSI DATABASE ---

try {
    // Membuat object PDO baru untuk koneksi database.
    // Object ini akan kita gunakan di seluruh aplikasi untuk berinteraksi dengan DB.
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error.
    // Di aplikasi production, error ini sebaiknya dicatat di log, bukan ditampilkan ke pengguna.
    die("Koneksi ke database gagal: " . $e->getMessage());
}

// Sekarang, variabel $pdo sudah siap digunakan di file manapun yang memanggil config.php