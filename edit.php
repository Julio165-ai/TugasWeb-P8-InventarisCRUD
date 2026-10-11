<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/includes/helpers.php';

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false) {
    set_flash('gagal', 'ID produk tidak valid.');
    redirect('index.php');
}

$pdo = Database::getInstance()->getConnection();

// Prepared statement untuk mengambil satu produk
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id = :id");
$stmt->execute([':id' => $id]);
$produk = $stmt->fetch();

if (!$produk) {
    set_flash('gagal', 'Produk tidak ditemukan.');
    redirect('index.php');
}

$kategori = $pdo->query("SELECT id, nama FROM kategori ORDER BY nama")->fetchAll();
$supplier = $pdo->query("SELECT id, nama FROM supplier ORDER BY nama")->fetchAll();

// Kalau validasi gagal, tampilkan isian terakhir pengguna; kalau tidak, data dari database
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
$data = array_merge($produk, $old);

$judul = 'Edit Produk';
require __DIR__ . '/includes/header.php';
?>

<h1>Edit Produk</h1>

<div class="card">
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= (int) $produk['id'] ?>">

        <div class="form-group">
            <label for="nama">Nama Produk</label>
            <input type="text" id="nama" name="nama" value="<?= e($data['nama']) ?>" required>
        </div>

        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= (int) $k['id'] ?>"
                        <?= ((string) $data['kategori_id'] === (string) $k['id']) ? 'selected' : '' ?>>
                        <?= e($k['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select id="supplier_id" name="supplier_id" required>
                <option value="">-- Pilih Supplier --</option>
                <?php foreach ($supplier as $s): ?>
                    <option value="<?= (int) $s['id'] ?>"
                        <?= ((string) $data['supplier_id'] === (string) $s['id']) ? 'selected' : '' ?>>
                        <?= e($s['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="harga">Harga (Rp)</label>
            <input type="number" id="harga" name="harga" min="0" value="<?= e((string) $data['harga']) ?>" required>
        </div>

        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" min="0" value="<?= e((string) $data['stok']) ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>