<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Syarat 8: semua output HTML lewat fungsi ini
function e(?string $nilai): string
{
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}

// Syarat 9: simpan pesan, tampil sekali setelah redirect
function set_flash(string $tipe, string $pesan): void
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);   // hilang setelah dibaca
    return $flash;
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function rupiah(int $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}