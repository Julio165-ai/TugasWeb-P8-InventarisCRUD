# Tugas Rutin 8 — CRUD Inventaris

| | |
|---|---|
| **Nama** | Juliotama Stefanus Sitepubudy |
| **NIM** | 423550030 |
| **Mata Kuliah** | Pemrograman Web |

Aplikasi CRUD inventaris produk dengan PHP native, PDO, dan MySQL.

## Cara Menjalankan
1. Pasang XAMPP, lalu nyalakan **Apache** dan **MySQL**.
2. Salin folder project ini ke `C:\xampp\htdocs\inventaris`.
3. Buka phpMyAdmin (`http://localhost/phpmyadmin`), buat database kosong bernama `inventaris_db`.
4. Pilih database `inventaris_db`, buka tab **Impor**, lalu pilih file `inventaris_db.sql` dan klik **Impor**.
5. Buka `http://localhost/inventaris/` di browser.

Konfigurasi koneksi ada di `config/Database.php` (host `127.0.0.1`, user `root`, tanpa password).

## Struktur Database
- `kategori` (id, nama)
- `supplier` (id, nama, telepon, alamat)
- `produk` (id, nama, kategori_id, supplier_id, harga, stok, created_at)
  - `kategori_id` → `kategori.id` (foreign key)
  - `supplier_id` → `supplier.id` (foreign key)

Data awal: 5 kategori, 5 supplier, dan 8 produk.

## Struktur Folder
```
inventaris/
├── assets/style.css       # tampilan
├── config/Database.php    # koneksi PDO (Singleton)
├── includes/
│   ├── header.php         # header, menu, tampilan flash message
│   ├── footer.php
│   └── helpers.php        # e(), flash message, redirect, rupiah
├── index.php              # daftar produk (JOIN kategori + supplier)
├── create.php / store.php # form tambah / simpan
├── edit.php / update.php  # form edit (terisi) / simpan perubahan
├── delete.php             # hapus (POST)
└── inventaris_db.sql      # database
```

## Pemenuhan Requirements
| # | Requirement | Implementasi |
|---|-------------|--------------|
| 1 | Database `inventaris_db`, 3 tabel + FK | `inventaris_db.sql` |
| 2 | Minimal 5 data per tabel | Data awal di file SQL |
| 3 | Koneksi PDO Singleton | `config/Database.php` |
| 4 | List produk dengan JOIN | `index.php` (INNER JOIN kategori dan supplier) |
| 5 | Form create dengan dropdown | `create.php` |
| 6 | Update (terisi) dan delete (konfirmasi) | `edit.php`, `update.php`, `delete.php` (konfirmasi `confirm()`) |
| 7 | Prepared statements | Semua query yang memakai input di `store`, `edit`, `update`, `delete` |
| 8 | `htmlspecialchars()` | Fungsi `e()` di `includes/helpers.php` |
| 9 | Flash message (redirect pattern) | `set_flash()` / `get_flash()` di `helpers.php` |
| 10 | UI rapi | `assets/style.css` |