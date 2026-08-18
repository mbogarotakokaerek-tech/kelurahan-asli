<?php
require_once __DIR__ . '/includes/bootstrap.php';

$laporan = null;
$dicari = false;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['kode'])) {
    $dicari = true;
    $kode = strtoupper(trim($_GET['kode']));
    $stmt = $pdo->prepare('SELECT * FROM laporan WHERE kode = ?');
    $stmt->execute([$kode]);
    $laporan = $stmt->fetch();
}

$statusUrutan = ['baru', 'diproses', 'selesai'];
$pageTitle = 'Lacak Laporan — Balai Warga';
require __DIR__ . '/includes/header_public.php';
?>

<main>
  <section class="track-section">
    <div class="section-head">
      <p class="eyebrow">Lacak Laporan</p>
      <h2>Cek status pengaduan Anda</h2>
      <p>Masukkan nomor tiket yang Anda terima saat pertama kali melapor.</p>
    </div>

    <form method="get" class="track-form">
      <input type="text" name="kode" placeholder="Contoh: PGD-260818-A9F3" value="<?= h($_GET['kode'] ?? '') ?>" required>
      <button type="submit" class="btn btn--primary">Lacak</button>
    </form>

    <div class="track-result">
      <?php if ($dicari && !$laporan): ?>
        <div class="track-empty">
          <strong>Nomor tiket tidak ditemukan.</strong><br>
          Periksa kembali nomor tiket yang Anda masukkan.
        </div>
      <?php elseif ($laporan): ?>
        <?php $stepIndex = array_search($laporan['status'], $statusUrutan); ?>
        <div class="track-card">
          <div class="track-card__top">
            <div>
              <h3><?= h($laporan['kode']) ?></h3>
              <p><?= h($laporan['kategori']) ?> · <?= h($laporan['lokasi']) ?></p>
            </div>
            <span class="badge badge--<?= h($laporan['status']) ?>"><?= h(status_label($laporan['status'])) ?></span>
          </div>
          <div class="progress">
            <?php foreach ($statusUrutan as $i => $s): ?>
              <span class="<?= $i <= $stepIndex ? 'active' : '' ?>"></span>
            <?php endforeach; ?>
          </div>
          <p class="track-date">Dilaporkan pada <?= h(format_tanggal($laporan['created_at'])) ?></p>
          <?php if (!empty($laporan['catatan_admin'])): ?>
            <div class="track-note"><strong>Catatan petugas:</strong> <?= h($laporan['catatan_admin']) ?></div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer_public.php'; ?>
