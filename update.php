<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/includes/helpers.php';

// Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id          = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
$nama        = trim($_POST['nama'] ?? '');
$kategori_id = filter_var($_POST['kategori_id'] ?? '', FILTER_VALIDATE_INT);
$supplier_id = filter_var($_POST['supplier_id'] ?? '', FILTER_VALIDATE_INT);
$harga       = filter_var($_POST['harga'] ?? '', FILTER_VALIDATE_INT);
$stok        = filter_var($_POST['stok'] ?? '', FILTER_VALIDATE_INT);

if ($id === false) {
    set_flash('gagal', 'ID produk tidak valid.');
    redirect('index.php');
}

// Simpan isian supaya form tidak kembali ke data lama kalau validasi gagal
$_SESSION['old'] = [
    'nama'        => $nama,
    'kategori_id' => $_POST['kategori_id'] ?? '',
    'supplier_id' => $_POST['supplier_id'] ?? '',
    'harga'       => $_POST['harga'] ?? '',
    'stok'        => $_POST['stok'] ?? '',
];

// Validasi
if ($nama === '' || mb_strlen($nama) > 150) {
    set_flash('gagal', 'Nama produk wajib diisi (maksimal 150 karakter).');
    redirect('edit.php?id=' . $id);
}
if ($kategori_id === false || $supplier_id === false) {
    set_flash('gagal', 'Kategori dan supplier wajib dipilih.');
    redirect('edit.php?id=' . $id);
}
if ($harga === false || $harga < 0 || $stok === false || $stok < 0) {
    set_flash('gagal', 'Harga dan stok harus berupa angka 0 atau lebih.');
    redirect('edit.php?id=' . $id);
}

try {
    $pdo = Database::getInstance()->getConnection();

    // Prepared statement
    $stmt = $pdo->prepare(
        "UPDATE produk
         SET nama = :nama,
             kategori_id = :kategori_id,
             supplier_id = :supplier_id,
             harga = :harga,
             stok = :stok
         WHERE id = :id"
    );
    $stmt->execute([
        ':nama'        => $nama,
        ':kategori_id' => $kategori_id,
        ':supplier_id' => $supplier_id,
        ':harga'       => $harga,
        ':stok'        => $stok,
        ':id'          => $id,
    ]);

    unset($_SESSION['old']);
    set_flash('sukses', 'Produk "' . $nama . '" berhasil diperbarui.');
    redirect('index.php');
} catch (PDOException $ex) {
    set_flash('gagal', 'Gagal memperbarui produk. Pastikan kategori dan supplier valid.');
    redirect('edit.php?id=' . $id);
}