<?php
/**
 * Koneksi database (SQLite berbasis file — tidak perlu setup server DB terpisah).
 * File database otomatis dibuat di storage/kelurahan.sqlite saat pertama kali diakses.
 */

$dbDir = __DIR__ . '/../storage';
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0777, true);
}
$dbFile = $dbDir . '/kelurahan.sqlite';

try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
} catch (PDOException $e) {
    http_response_code(500);
    die('Koneksi database gagal. Pastikan ekstensi PHP "pdo_sqlite" aktif. Detail: ' . $e->getMessage());
}

// ---------- Skema tabel ----------
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        role TEXT NOT NULL CHECK(role IN ('warga','admin')) DEFAULT 'warga',
        nama TEXT NOT NULL,
        nik TEXT UNIQUE,
        no_hp TEXT,
        alamat TEXT,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    )
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS laporan (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        kode TEXT NOT NULL UNIQUE,
        user_id INTEGER NOT NULL,
        kategori TEXT NOT NULL,
        lokasi TEXT NOT NULL,
        deskripsi TEXT NOT NULL,
        foto TEXT,
        status TEXT NOT NULL CHECK(status IN ('baru','diproses','selesai')) DEFAULT 'baru',
        catatan_admin TEXT,
        created_at TEXT NOT NULL DEFAULT (datetime('now')),
        updated_at TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

// ---------- Seed akun admin default (hanya jika belum ada) ----------
$cek = $pdo->query("SELECT COUNT(*) AS jumlah FROM users WHERE role = 'admin'")->fetch();
if ((int) $cek['jumlah'] === 0) {
    $stmt = $pdo->prepare("INSERT INTO users (role, nama, email, password) VALUES ('admin', 'Administrator Kelurahan', 'admin@kelurahan.go.id', ?)");
    $stmt->execute([password_hash('admin123', PASSWORD_DEFAULT)]);
}
