<?php
// file: helpers.php

/**
 * Menyimpan pesan notifikasi ke dalam session.
 *
 * @param string $message Pesan yang ingin ditampilkan.
 * @param string $type Tipe notifikasi (success, danger, warning, info).
 */
function set_flash_message(string $message, string $type = 'success') {
    // Pastikan session sudah berjalan
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Menampilkan pesan notifikasi jika ada, lalu menghapusnya dari session.
 * Cukup panggil fungsi ini di file view.
 */
function display_flash_message() {
    // Pastikan session sudah berjalan
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['flash_message'])) {
        $message_data = $_SESSION['flash_message'];
        
        // Tampilkan HTML alert
        echo '<div class="alert alert-' . htmlspecialchars($message_data['type']) . ' alert-dismissible fade show" role="alert">';
        echo htmlspecialchars($message_data['message']);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        
        // Hapus pesan dari session agar tidak tampil lagi
        unset($_SESSION['flash_message']);
    }
}