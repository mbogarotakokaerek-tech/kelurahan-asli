<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (current_user()) {
    redirect(current_user()['role'] === 'admin' ? '/admin/dashboard.php' : '/warga/dashboard.php');
}

$errors = [];
$old = ['nama' => '', 'nik' => '', 'no_hp' => '', 'alamat' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['nama']   = trim($_POST['nama'] ?? '');
    $old['nik']    = trim($_POST['nik'] ?? '');
    $old['no_hp']  = trim($_POST['no_hp'] ?? '');
    $old['alamat'] = trim($_POST['alamat'] ?? '');
    $old['email']  = trim($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $konfirmasi     = $_POST['konfirmasi_password'] ?? '';

    if ($old['nama'] === '') $errors[] = 'Nama lengkap wajib diisi.';
    if (!preg_match('/^\d{16}$/', $old['nik'])) $errors[] = 'NIK harus terdiri dari 16 digit angka.';
    if ($old['no_hp'] === '') $errors[] = 'Nomor HP wajib diisi.';
    if ($old['alamat'] === '') $errors[] = 'Alamat wajib diisi.';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
    if (strlen($password) < 6) $errors[] = 'Kata sandi minimal 6 karakter.';
    if ($password !== $konfirmasi) $errors[] = 'Konfirmasi kata sandi tidak cocok.';

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT COUNT(*) AS jumlah FROM users WHERE email = ? OR nik = ?');
        $stmt->execute([$old['email'], $old['nik']]);
        if ((int) $stmt->fetch()['jumlah'] > 0) {
            $errors[] = 'Email atau NIK sudah terdaftar. Silakan masuk atau gunakan data lain.';
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO users (role, nama, nik, no_hp, alamat, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            'warga',
            $old['nama'],
            $old['nik'],
            $old['no_hp'],
            $old['alamat'],
            $old['email'],
            password_hash($password, PASSWORD_DEFAULT),
        ]);

        $userId = (int) $pdo->lastInsertId();
        login_user([
            'id' => $userId,
            'role' => 'warga',
            'nama' => $old['nama'],
            'email' => $old['email'],
        ]);

        set_flash('success', 'Registrasi berhasil! Selamat datang, ' . $old['nama'] . '.');
        redirect('/warga/dashboard.php');
    }
}

$pageTitle = 'Daftar Akun Warga — Balai Warga';
require __DIR__ . '/../includes/header_public.php';
?>

<main>
  <section class="auth-section">
    <div class="auth-card">
      <p class="eyebrow">Registrasi Warga</p>
      <h2>Buat akun warga</h2>
      <p class="auth-card__lede">Satu akun untuk membuat &amp; memantau semua laporan Anda ke kelurahan.</p>

      <?php if ($errors): ?>
        <div class="form-errors">
          <ul>
            <?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" class="ticket-form ticket-form--auth" novalidate>
        <div class="field field--full">
          <label for="nama">Nama lengkap</label>
          <input type="text" id="nama" name="nama" value="<?= h($old['nama']) ?>" required>
        </div>
        <div class="field">
          <label for="nik">NIK <span class="optional">(16 digit)</span></label>
          <input type="text" id="nik" name="nik" inputmode="numeric" maxlength="16" value="<?= h($old['nik']) ?>" required>
        </div>
        <div class="field">
          <label for="no_hp">Nomor HP / WhatsApp</label>
          <input type="tel" id="no_hp" name="no_hp" value="<?= h($old['no_hp']) ?>" required>
        </div>
        <div class="field field--full">
          <label for="alamat">Alamat lengkap</label>
          <input type="text" id="alamat" name="alamat" value="<?= h($old['alamat']) ?>" placeholder="Contoh: Jl. Mawar No. 12, RT 03 / RW 05" required>
        </div>
        <div class="field field--full">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= h($old['email']) ?>" required>
        </div>
        <div class="field">
          <label for="password">Kata sandi</label>
          <input type="password" id="password" name="password" minlength="6" required>
        </div>
        <div class="field">
          <label for="konfirmasi_password">Konfirmasi kata sandi</label>
          <input type="password" id="konfirmasi_password" name="konfirmasi_password" minlength="6" required>
        </div>
        <div class="field field--full form-actions">
          <button type="submit" class="btn btn--primary btn--wide">Daftar Sekarang</button>
        </div>
      </form>

      <p class="auth-card__switch">Sudah punya akun? <a href="/auth/login.php">Masuk di sini</a></p>
    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer_public.php'; ?>
