<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/includes/helpers.php';

$pdo = Database::getInstance()->getConnection();

// JOIN produk dengan kategori dan supplier
$sql = "SELECT p.id, p.nama, p.harga, p.stok,
               k.nama AS kategori,
               s.nama AS supplier
        FROM produk p
        INNER JOIN kategori k ON p.kategori_id = k.id
        INNER JOIN supplier s ON p.supplier_id = s.id
        ORDER BY p.id DESC";

$produk = $pdo->query($sql)->fetchAll();

$judul = 'Daftar Produk';
require __DIR__ . '/includes/header.php';
?>

<div class="toolbar">
    <h1>Daftar Produk</h1>
    <a href="create.php" class="btn">+ Tambah Produk</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($produk) === 0): ?>
                <tr><td colspan="7" class="empty">Belum ada data produk.</td></tr>
            <?php endif; ?>

            <?php foreach ($produk as $i => $p): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($p['nama']) ?></td>
                    <td><?= e($p['kategori']) ?></td>
                    <td><?= e($p['supplier']) ?></td>
                    <td class="text-right"><?= e(rupiah((int) $p['harga'])) ?></td>
                    <td class="text-right"><?= (int) $p['stok'] ?></td>
                    <td>
                        <div class="aksi">
                            <a href="edit.php?id=<?= (int) $p['id'] ?>" class="btn btn-warning">Edit</a>

                            <form action="delete.php" method="POST"
                                  onsubmit="return confirm('Yakin hapus produk ini?');">
                                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>