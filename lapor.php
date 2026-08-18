<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_login('warga');

$user = current_user();
$errors = [];
$old = ['kategori' => '', 'lokasi' => '', 'deskripsi' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['kategori']  = trim($_POST['kategori'] ?? '');
    $old['lokasi']    = trim($_POST['lokasi'] ?? '');
    $old['deskripsi'] = trim($_POST['deskripsi'] ?? '');

    if (!in_array($old['kategori'], kategori_list(), true)) $errors[] = 'Pilih kategori yang valid.';
    if ($old['lokasi'] === '') $errors[] = 'Lokasi kejadian wajib diisi.';
    if ($old['deskripsi'] === '') $errors[] = 'Deskripsi laporan wajib diisi.';

    $namaFoto = null;
    if (!empty($_FILES['foto']['name'])) {
        $file = $_FILES['foto'];
        $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'pdf'];
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Gagal mengunggah lampiran.';
        } elseif (!in_array($ekstensi, $ekstensiDiizinkan, true)) {
            $errors[] = 'Lampiran harus berformat JPG, PNG, atau PDF.';
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran lampiran maksimal 2MB.';
        } else {
            $namaFoto = uniqid('lampiran_') . '.' . $ekstensi;
            $tujuan = __DIR__ . '/../uploads/' . $namaFoto;
            if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
                $errors[] = 'Gagal menyimpan lampiran ke server.';
                $namaFoto = null;
            }
        }
    }

    if (!$errors) {
        $kode = generate_kode_tiket($pdo);
        $stmt = $pdo->prepare('INSERT INTO laporan (kode, user_id, kategori, lokasi, deskripsi, foto, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$kode, $user['id'], $old['kategori'], $old['lokasi'], $old['deskripsi'], $namaFoto, 'baru']);

        set_flash('success', 'Laporan berhasil dikirim. Nomor tiket Anda: ' . $kode);
        redirect('/warga/detail.php?kode=' . urlencode($kode));
    }
}

$pageTitle = 'Buat Laporan Baru — Balai Warga';
$activePage = 'lapor';
require __DIR__ . '/../includes/header_app.php';
?>

<div class="page-head">
  <div>
    <p class="eyebrow">Laporan Baru</p>
    <h1>Ceritakan apa yang terjadi</h1>
    <p class="page-head__sub">Laporan Anda akan diteruskan ke petugas kelurahan yang menangani wilayah Anda.</p>
  </div>
</div>

<?php if ($errors): ?>
  <div class="form-errors">
    <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="ticket-form form-card" novalidate>
  <div class="field">
    <label for="kategori">Kategori pengaduan</label>
    <select id="kategori" name="kategori" required>
      <option value="" disabled <?= $old['kategori'] === '' ? 'selected' : '' ?>>Pilih kategori</option>
      <?php foreach (kategori_list() as $k): ?>
        <option value="<?= h($k) ?>" <?= $old['kategori'] === $k ? 'selected' : '' ?>><?= h($k) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="field">
    <label for="lokasi">Lokasi kejadian</label>
    <input type="text" id="lokasi" name="lokasi" value="<?= h($old['lokasi']) ?>" placeholder="Contoh: RT 03 / RW 05, dekat masjid" required>
  </div>

  <div class="field field--full">
    <label for="deskripsi">Ceritakan detailnya</label>
    <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan apa yang terjadi, sejak kapan, dan dampaknya bagi warga sekitar." required><?= h($old['deskripsi']) ?></textarea>
  </div>

  <div class="field field--full">
    <label for="foto">Lampiran foto/dokumen <span class="optional">(opsional, JPG/PNG/PDF, maks 2MB)</span></label>
    <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png,.pdf">
  </div>

  <div class="field field--full form-actions">
    <button type="submit" class="btn btn--primary btn--wide">Kirim Laporan</button>
  </div>
</form>

<?php require __DIR__ . '/../includes/footer_app.php'; ?>
