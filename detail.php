<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_login('warga');

$user = current_user();
$kode = $_GET['kode'] ?? '';

$stmt = $pdo->prepare('SELECT * FROM laporan WHERE kode = ? AND user_id = ?');
$stmt->execute([$kode, $user['id']]);
$laporan = $stmt->fetch();

if (!$laporan) {
    set_flash('error', 'Laporan tidak ditemukan.');
    redirect('/warga/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'hapus') {
    if ($laporan['status'] === 'baru') {
        $del = $pdo->prepare('DELETE FROM laporan WHERE id = ? AND user_id = ?');
        $del->execute([$laporan['id'], $user['id']]);
        set_flash('success', 'Laporan berhasil dihapus.');
        redirect('/warga/dashboard.php');
    }
}

$statusUrutan = ['baru', 'diproses', 'selesai'];
$stepIndex = array_search($laporan['status'], $statusUrutan);

$pageTitle = $laporan['kode'] . ' — Balai Warga';
$activePage = 'dashboard';
require __DIR__ . '/../includes/header_app.php';
?>

<div class="page-head">
  <div>
    <p class="eyebrow"><?= h($laporan['kode']) ?></p>
    <h1><?= h($laporan['kategori']) ?></h1>
    <p class="page-head__sub">Dilaporkan pada <?= h(format_tanggal($laporan['created_at'])) ?></p>
  </div>
  <span class="badge badge--<?= h($laporan['status']) ?>" style="font-size:.8rem;padding:9px 16px;"><?= h(status_label($laporan['status'])) ?></span>
</div>

<div class="detail-grid">
  <div class="form-card">
    <div class="progress" style="margin-bottom:22px;">
      <?php foreach ($statusUrutan as $i => $s): ?>
        <span class="<?= $i <= $stepIndex ? 'active' : '' ?>"></span>
      <?php endforeach; ?>
    </div>

    <div class="detail-block">
      <p class="detail-block__label">Lokasi</p>
      <p class="detail-block__text"><?= h($laporan['lokasi']) ?></p>
    </div>

    <div class="detail-block">
      <p class="detail-block__label">Deskripsi</p>
      <p class="detail-block__text"><?= nl2br(h($laporan['deskripsi'])) ?></p>
    </div>

    <?php if ($laporan['foto']): ?>
      <div class="detail-block">
        <p class="detail-block__label">Lampiran</p>
        <p class="detail-block__text"><a href="/uploads/<?= h($laporan['foto']) ?>" target="_blank">Lihat lampiran ↗</a></p>
      </div>
    <?php endif; ?>

    <?php if ($laporan['catatan_admin']): ?>
      <div class="track-note">
        <strong>Catatan petugas:</strong> <?= h($laporan['catatan_admin']) ?>
      </div>
    <?php endif; ?>

    <?php if ($laporan['status'] === 'baru'): ?>
      <form method="post" onsubmit="return confirm('Hapus laporan ini secara permanen?');" style="margin-top:20px;">
        <input type="hidden" name="aksi" value="hapus">
        <button type="submit" class="btn btn--danger-text">Hapus laporan ini</button>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer_app.php'; ?>
