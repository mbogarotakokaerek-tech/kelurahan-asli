<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle ?? 'Balai Warga — Kelurahan') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="topbar">
  <div class="topbar__inner">
    <a href="/index.php" class="brand">
      <span class="brand__mark" aria-hidden="true">
        <svg viewBox="0 0 48 48" width="30" height="30">
          <path d="M24 4 L44 16 V20 H4 V16 Z" fill="currentColor"/>
          <rect x="8" y="20" width="6" height="18" fill="currentColor"/>
          <rect x="21" y="20" width="6" height="18" fill="currentColor"/>
          <rect x="34" y="20" width="6" height="18" fill="currentColor"/>
          <rect x="4" y="40" width="40" height="4" fill="currentColor"/>
        </svg>
      </span>
      <span class="brand__text">
        <strong>Balai Warga</strong>
        <span>Kelurahan Sukamaju Indah</span>
      </span>
    </a>
    <nav class="topbar__nav">
      <a href="/lacak.php">Lacak Laporan</a>
      <?php if (current_user()): ?>
        <a href="<?= current_user()['role'] === 'admin' ? '/admin/dashboard.php' : '/warga/dashboard.php' ?>" class="btn btn--ghost btn--sm">Ke Dashboard</a>
      <?php else: ?>
        <a href="/auth/login.php">Masuk</a>
        <a href="/auth/registrasi.php" class="btn btn--ghost btn--sm">Daftar Akun</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<?php $flash = get_flash(); if ($flash): ?>
  <div class="flash-wrap">
    <div class="flash flash--<?= h($flash['type']) ?>"><?= h($flash['pesan']) ?></div>
  </div>
<?php endif; ?>
