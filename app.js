// Peningkatan kecil untuk pengalaman dashboard — aplikasi tetap berfungsi tanpa JS ini.
document.addEventListener('DOMContentLoaded', function () {
  // Cegah klik pada tombol/link di dalam baris tabel ikut memicu navigasi baris.
  document.querySelectorAll('.tabel-pengaduan tbody tr').forEach(function (row) {
    row.querySelectorAll('a, button').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.stopPropagation();
      });
    });
  });
});
