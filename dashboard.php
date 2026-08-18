<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_login('warga');

$user = current_user();

$stmt = $pdo->prepare('SELECT * FROM laporan WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user['id']]);
$semuaLaporan = $stmt->fetchAll();

$total = count($semuaLaporan);
$jumlah = ['baru' => 0, 'diproses' => 0, 'selesai' => 0];
foreach ($semuaLaporan as $l) {
    $jumlah[$l['status']]++;
}

$pageTitle = 'Dashboard Saya — Balai Warga';
$activePage = 'dashboard';
require __DIR__ . '/../includes/header_app.php';
?>

<div class="page-head">
  <div>
    <p class="eyebrow">Dashboard Saya</p>
    <h1>Halo, <?= h(explode(' ', $user['nama'])[0]) ?> 👋</h1>
    <p class="page-head__sub">Berikut ringkasan seluruh laporan yang pernah Anda ajukan.</p>
  </div>
  <a href="/warga/lapor.php" class="btn btn--primary">+ Buat Laporan Baru</a>
</div>

<section class="stat-grid">
  <div class="stat-card">
    <p class="stat-card__label">Total laporan</p>
    <p class="stat-card__value"><?= $total ?></p>
  </div>
  <div class="stat-card stat-card--baru">
    <p class="stat-card__label">Baru</p>
    <p class="stat-card__value"><?= $jumlah['baru'] ?></p>
  </div>
  <div class="stat-card stat-card--diproses">
    <p class="stat-card__label">Diproses</p>
    <p class="stat-card__value"><?= $jumlah['diproses'] ?></p>
  </div>
  <div class="stat-card stat-card--selesai">
    <p class="stat-card__label">Selesai</p>
    <p class="stat-card__value"><?= $jumlah['selesai'] ?></p>
  </div>
</section>

<section class="table-wrap">
  <?php if (!$semuaLaporan): ?>
    <div class="empty-state">
      <h3>Belum ada laporan</h3>
      <p>Laporan yang Anda buat akan muncul di sini beserta status penanganannya.</p>
      <a href="/warga/lapor.php" class="btn btn--primary" style="margin-top:14px;">Buat Laporan Pertama</a>
    </div>
  <?php else: ?>
    <table class="tabel-pengaduan">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Kategori</th>
          <th>Lokasi</th>
          <th>Tanggal</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($semuaLaporan as $l): ?>
          <tr onclick="window.location='/warga/detail.php?kode=<?= urlencode($l['kode']) ?>'">
            <td class="td-kode"><?= h($l['kode']) ?></td>
            <td><?= h($l['kategori']) ?></td>
            <td><?= h($l['lokasi']) ?></td>
            <td><?= h(format_tanggal($l['created_at'], false)) ?></td>
            <td><span class="badge badge--<?= h($l['status']) ?>"><?= h(status_label($l['status'])) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../includes/footer_app.php'; ?>
