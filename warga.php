<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_login('admin');

$q = trim($_GET['q'] ?? '');

$sql = "SELECT users.*, (SELECT COUNT(*) FROM laporan WHERE laporan.user_id = users.id) AS jumlah_laporan
        FROM users WHERE role = 'warga'";
$params = [];
if ($q !== '') {
    $sql .= ' AND (nama LIKE ? OR email LIKE ? OR nik LIKE ?)';
    $like = '%' . $q . '%';
    $params = [$like, $like, $like];
}
$sql .= ' ORDER BY created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$daftarWarga = $stmt->fetchAll();

$pageTitle = 'Data Warga — Panel Petugas';
$activePage = 'warga';
require __DIR__ . '/../includes/header_app.php';
?>

<div class="page-head">
  <div>
    <p class="eyebrow">Panel Petugas</p>
    <h1>Data Warga Terdaftar</h1>
    <p class="page-head__sub">Total <?= count($daftarWarga) ?> warga terdaftar di sistem.</p>
  </div>
</div>

<form method="get" class="toolbar" style="justify-content:flex-end;">
  <div class="toolbar__right">
    <input type="text" name="q" value="<?= h($q) ?>" placeholder="Cari nama, email, atau NIK…">
    <button type="submit" class="btn btn--ghost btn--sm" style="border-color:var(--line);color:var(--ink);">Cari</button>
  </div>
</form>

<section class="table-wrap">
  <?php if (!$daftarWarga): ?>
    <div class="empty-state">
      <h3>Belum ada warga terdaftar</h3>
      <p>Data warga akan muncul di sini setelah mereka mendaftar akun.</p>
    </div>
  <?php else: ?>
    <table class="tabel-pengaduan">
      <thead>
        <tr>
          <th>Nama</th>
          <th>NIK</th>
          <th>Kontak</th>
          <th>Alamat</th>
          <th>Terdaftar</th>
          <th>Jumlah Laporan</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($daftarWarga as $w): ?>
          <tr>
            <td class="td-nama"><strong><?= h($w['nama']) ?></strong><span><?= h($w['email']) ?></span></td>
            <td class="td-kode"><?= h($w['nik']) ?></td>
            <td><?= h($w['no_hp']) ?></td>
            <td><?= h($w['alamat']) ?></td>
            <td><?= h(format_tanggal($w['created_at'], false)) ?></td>
            <td><span class="badge badge--diproses"><?= (int) $w['jumlah_laporan'] ?> laporan</span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../includes/footer_app.php'; ?>
