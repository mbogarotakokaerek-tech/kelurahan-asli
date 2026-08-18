<?php
/**
 * Fungsi-fungsi bantu yang dipakai di seluruh aplikasi.
 */

function h(?string $str): string
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function format_tanggal(?string $iso, bool $denganJam = true): string
{
    if (!$iso) return '-';
    $ts = strtotime($iso);
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $hasil = date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    if ($denganJam) {
        $hasil .= ', ' . date('H:i', $ts);
    }
    return $hasil;
}

function generate_kode_tiket(PDO $pdo): string
{
    do {
        $kode = 'PGD-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
        $stmt = $pdo->prepare('SELECT COUNT(*) AS jumlah FROM laporan WHERE kode = ?');
        $stmt->execute([$kode]);
    } while ((int) $stmt->fetch()['jumlah'] > 0);

    return $kode;
}

function set_flash(string $type, string $pesan): void
{
    $_SESSION['flash'] = ['type' => $type, 'pesan' => $pesan];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function status_label(string $status): string
{
    $label = ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'];
    return $label[$status] ?? $status;
}

function kategori_list(): array
{
    return [
        'Infrastruktur & Jalan',
        'Kebersihan & Sampah',
        'Keamanan & Ketertiban',
        'Sosial & Bantuan',
        'Administrasi Kependudukan',
        'Lainnya',
    ];
}
