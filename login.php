<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (current_user()) {
    redirect(current_user()['role'] === 'admin' ? '/admin/dashboard.php' : '/warga/dashboard.php');
}

$error = null;
$oldEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldEmail = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$oldEmail]);
    $row = $stmt->fetch();

    if ($row && password_verify($password, $row['password'])) {
        login_user($row);
        redirect($row['role'] === 'admin' ? '/admin/dashboard.php' : '/warga/dashboard.php');
    } else {
        $error = 'Email atau kata sandi salah.';
    }
}

$pageTitle = 'Masuk — Balai Warga';
require __DIR__ . '/../includes/header_public.php';
?>

<main>
  <section class="auth-section">
    <div class="auth-card auth-card--sm">
      <p class="eyebrow">Masuk</p>
      <h2>Selamat datang kembali</h2>
      <p class="auth-card__lede">Masuk sebagai warga atau petugas kelurahan.</p>

      <?php if ($error): ?>
        <div class="form-errors"><ul><li><?= h($error) ?></li></ul></div>
      <?php endif; ?>

      <form method="post" class="ticket-form ticket-form--auth" novalidate>
        <div class="field field--full">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= h($oldEmail) ?>" required autofocus>
        </div>
        <div class="field field--full">
          <label for="password">Kata sandi</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div class="field field--full form-actions">
          <button type="submit" class="btn btn--primary btn--wide">Masuk</button>
        </div>
      </form>

      <p class="auth-card__switch">Belum punya akun? <a href="/auth/registrasi.php">Daftar sebagai warga</a></p>
      <p class="login-hint">Demo petugas: <code>admin@kelurahan.go.id</code> / <code>admin123</code></p>
    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer_public.php'; ?>
