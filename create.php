<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/includes/helpers.php';

$pdo = Database::getInstance()->getConnection();

// Data untuk dropdown
$kategori = $pdo->query("SELECT id, nama FROM kategori ORDER BY nama")->fetchAll();
$supplier = $pdo->query("SELECT id, nama FROM supplier ORDER BY nama")->fetchAll();

// Isian lama (kalau validasi gagal, form tidak kosong lagi)
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

$judul = 'Tambah Produk';
require __DIR__ . '/includes/header.php';
?>

<h1>Tambah Produk</h1>

<div class="card">
    <form action="store.php" method="POST">
        <div class="form-group">
            <label for="nama">Nama Produk</label>
            <input type="text" id="nama" name="nama" value="<?= e($old['nama'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= (int) $k['id'] ?>"
                        <?= ((string) ($old['kategori_id'] ?? '') === (string) $k['id']) ? 'selected' : '' ?>>
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
                        <?= ((string) ($old['supplier_id'] ?? '') === (string) $s['id']) ? 'selected' : '' ?>>
                        <?= e($s['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="harga">Harga (Rp)</label>
            <input type="number" id="harga" name="harga" min="0" value="<?= e($old['harga'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" min="0" value="<?= e($old['stok'] ?? '0') ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>