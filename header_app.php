<?php
$user = current_user();
$aktif = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle ?? 'Dashboard — Balai Warga') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="app-body">

<div class="app-shell">
  <aside class="sidebar">
    <a href="/index.php" class="brand brand--sidebar">
      <span class="brand__mark" aria-hidden="true">
        <svg viewBox="0 0 48 48" width="26" height="26">
          <path d="M24 4 L44 16 V20 H4 V16 Z" fill="currentColor"/>
          <rect x="8" y="20" width="6" height="18" fill="currentColor"/>
          <rect x="21" y="20" width="6" height="18" fill="currentColor"/>
          <rect x="34" y="20" width="6" height="18" fill="currentColor"/>
          <rect x="4" y="40" width="40" height="4" fill="currentColor"/>
        </svg>
      </span>
      <span class="brand__text">
        <strong>Balai Warga</strong>
        <span><?= $user['role'] === 'admin' ? 'Panel Petugas' : 'Portal Warga' ?></span>
      </span>
    </a>

    <nav class="sidebar__nav">
      <?php if ($user['role'] === 'admin'): ?>
        <a href="/admin/dashboard.php" class="<?= $aktif === 'dashboard' ? 'active' : '' ?>">Dashboard Laporan</a>
        <a href="/admin/warga.php" class="<?= $aktif === 'warga' ? 'active' : '' ?>">Data Warga</a>
      <?php else: ?>
        <a href="/warga/dashboard.php" class="<?= $aktif === 'dashboard' ? 'active' : '' ?>">Dashboard Saya</a>
        <a href="/warga/lapor.php" class="<?= $aktif === 'lapor' ? 'active' : '' ?>">Buat Laporan Baru</a>
        <a href="/lacak.php">Lacak Laporan Umum</a>
      <?php endif; ?>
    </nav>

    <div class="sidebar__user">
      <p class="sidebar__user-name"><?= h($user['nama']) ?></p>
      <p class="sidebar__user-role"><?= $user['role'] === 'admin' ? 'Petugas Kelurahan' : 'Warga Terdaftar' ?></p>
      <a href="/auth/logout.php" class="btn btn--ghost btn--sm btn--wide">Keluar</a>
    </div>
  </aside>

  <div class="app-content">
    <?php $flash = get_flash(); if ($flash): ?>
      <div class="flash-wrap flash-wrap--app">
        <div class="flash flash--<?= h($flash['type']) ?>"><?= h($flash['pesan']) ?></div>
      </div>
    <?php endif; ?>

    <main class="app-main">
