<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/includes/helpers.php';

// Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$nama        = trim($_POST['nama'] ?? '');
$kategori_id = filter_var($_POST['kategori_id'] ?? '', FILTER_VALIDATE_INT);
$supplier_id = filter_var($_POST['supplier_id'] ?? '', FILTER_VALIDATE_INT);
$harga       = filter_var($_POST['harga'] ?? '', FILTER_VALIDATE_INT);
$stok        = filter_var($_POST['stok'] ?? '', FILTER_VALIDATE_INT);

// Simpan isian supaya form tidak kosong kalau gagal
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
    redirect('create.php');
}
if ($kategori_id === false || $supplier_id === false) {
    set_flash('gagal', 'Kategori dan supplier wajib dipilih.');
    redirect('create.php');
}
if ($harga === false || $harga < 0 || $stok === false || $stok < 0) {
    set_flash('gagal', 'Harga dan stok harus berupa angka 0 atau lebih.');
    redirect('create.php');
}

try {
    $pdo = Database::getInstance()->getConnection();

    // Prepared statement: nilai dikirim terpisah dari SQL
    $stmt = $pdo->prepare(
        "INSERT INTO produk (nama, kategori_id, supplier_id, harga, stok)
         VALUES (:nama, :kategori_id, :supplier_id, :harga, :stok)"
    );
    $stmt->execute([
        ':nama'        => $nama,
        ':kategori_id' => $kategori_id,
        ':supplier_id' => $supplier_id,
        ':harga'       => $harga,
        ':stok'        => $stok,
    ]);

    unset($_SESSION['old']);
    set_flash('sukses', 'Produk "' . $nama . '" berhasil ditambahkan.');
    redirect('index.php');
} catch (PDOException $ex) {
    // Contoh: kategori/supplier tidak ada (melanggar foreign key)
    set_flash('gagal', 'Gagal menyimpan produk. Pastikan kategori dan supplier valid.');
    redirect('create.php');
}