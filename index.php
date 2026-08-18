<?php
require_once __DIR__ . '/includes/bootstrap.php';

// Jika sudah login, langsung arahkan ke dashboard masing-masing peran.
if (current_user()) {
    redirect(current_user()['role'] === 'admin' ? '/admin/dashboard.php' : '/warga/dashboard.php');
}

$pageTitle = 'Balai Warga — Layanan Pengaduan Kelurahan';
require __DIR__ . '/includes/header_public.php';
?>

<main>

  <section class="hero">
    <div class="hero__inner">
      <p class="eyebrow">Layanan Digital Kelurahan · Buka 24 Jam</p>
      <h1>Satu akun, untuk<br>seluruh urusan warga.</h1>
      <p class="hero__lede">Daftar sebagai warga untuk melaporkan masalah di lingkungan Anda, memantau status penanganan, dan berkomunikasi langsung dengan petugas kelurahan — semua dalam satu dashboard.</p>
      <div class="hero__cta">
        <a href="/auth/registrasi.php" class="btn btn--primary">Daftar Akun Warga</a>
        <a href="/auth/login.php" class="btn btn--ghost">Sudah punya akun? Masuk</a>
      </div>
    </div>
    <div class="hero__stamp" aria-hidden="true">
      <div class="stamp-ring">
        <span>SUARA WARGA</span>
        <span>DIDENGAR</span>
      </div>
    </div>
  </section>

  <section class="feature-section">
    <div class="section-head">
      <p class="eyebrow">Fitur Utama</p>
      <h2>Dibangun untuk warga &amp; petugas</h2>
    </div>
    <div class="feature-grid">
      <div class="feature-card">
        <span class="feature-card__num">01</span>
        <h3>Registrasi Warga</h3>
        <p>Daftar dengan data diri &amp; NIK, satu akun untuk semua laporan Anda ke depannya.</p>
      </div>
      <div class="feature-card">
        <span class="feature-card__num">02</span>
        <h3>Dashboard Pribadi</h3>
        <p>Pantau seluruh riwayat laporan Anda beserta status dan catatan dari petugas.</p>
      </div>
      <div class="feature-card">
        <span class="feature-card__num">03</span>
        <h3>Lacak Tanpa Login</h3>
        <p>Punya nomor tiket? Cek statusnya kapan saja tanpa perlu masuk akun.</p>
      </div>
      <div class="feature-card">
        <span class="feature-card__num">04</span>
        <h3>Panel Petugas</h3>
        <p>Petugas kelurahan mengelola seluruh laporan dan data warga dari satu dashboard.</p>
      </div>
    </div>
  </section>

  <section class="cta-band">
    <h2>Ada yang perlu dilaporkan hari ini?</h2>
    <p>Buat akun dalam waktu kurang dari satu menit.</p>
    <a href="/auth/registrasi.php" class="btn btn--primary">Mulai Daftar</a>
  </section>

</main>

<?php require __DIR__ . '/includes/footer_public.php'; ?>
