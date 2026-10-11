<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/includes/helpers.php';

// Hanya menerima POST (tidak bisa dihapus lewat link biasa)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false) {
    set_flash('gagal', 'ID produk tidak valid.');
    redirect('index.php');
}

try {
    $pdo = Database::getInstance()->getConnection();

    // Ambil nama dulu untuk pesan flash (prepared statement)
    $cek = $pdo->prepare("SELECT nama FROM produk WHERE id = :id");
    $cek->execute([':id' => $id]);
    $produk = $cek->fetch();

    if (!$produk) {
        set_flash('gagal', 'Produk tidak ditemukan.');
        redirect('index.php');
    }

    // Prepared statement untuk menghapus
    $stmt = $pdo->prepare("DELETE FROM produk WHERE id = :id");
    $stmt->execute([':id' => $id]);

    set_flash('sukses', 'Produk "' . $produk['nama'] . '" berhasil dihapus.');
} catch (PDOException $ex) {
    set_flash('gagal', 'Gagal menghapus produk.');
}

redirect('index.php');