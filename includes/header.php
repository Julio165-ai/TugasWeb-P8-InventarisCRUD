<?php
require_once __DIR__ . '/helpers.php';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul ?? 'Inventaris') ?> - Inventaris</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="navbar">
    <div class="container navbar-inner">
        <a class="brand" href="index.php">📦 Inventaris</a>
        <nav>
            <a href="index.php">Daftar Produk</a>
            <a href="create.php">+ Tambah Produk</a>
        </nav>
    </div>
</header>

<main class="container">
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['tipe']) ?>">
            <?= e($flash['pesan']) ?>
        </div>
    <?php endif; ?>