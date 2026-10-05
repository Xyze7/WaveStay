WaveStay - Island Rental Platform

WaveStay adalah platform web pemesanan fasilitas liburan pulau (Villa, Speedboat, Jetski, dan Snorkeling) yang dilengkapi dengan sistem keamanan jadwal anti-bentrok, autentikasi pengguna, serta panel khusus admin.

---

Persyaratan Sistem
- Aplikasi Laragon (atau XAMPP) terinstal di komputer.
- PHP versi 7.4 atau terbaru.
- Database MySQL.

---

Langkah-Langkah Membuka Website
1. Pindahkan Folder Projek: 
   Simpan folder projek wavestay ke dalam direktori root server lokal kamu (misalnya di laragon/www/wavestay).
2. Nyalakan Server: 
   Buka aplikasi Laragon, lalu klik tombol Start All.
3. Impor Database: 
   - Buka browser dan ketik http://localhost/phpmyadmin.
   - Buat database baru dengan nama wavestay.
   - Masuk ke database wavestay, pilih tab Import, lalu pilih dan upload file wavestay.sql dari folder projekmu.
4. Akses Website: 
   Buka browser dan ketik link berikut: http://wavestay.test (atau sesuaikan dengan domain lokal Laragon kamu).

---

Panduan Penggunaan Aplikasi

A. Sisi Pelanggan (User / Pembooking)
1. Buka Beranda (index.php): Pengguna akan melihat halaman utama dengan latar belakang laut serta daftar katalog unit sewa.
2. Filter Kategori: Pengguna dapat menyaring daftar unit berdasarkan kategori (Semua, Villa, Speedboat, Jetski, atau Snorkeling).
3. Sign In / Sign Up: Sebelum menyewa, pengguna wajib memiliki akun. Klik tombol Sign In jika sudah punya akun, atau Sign Up untuk mendaftar akun baru.
4. Melakukan Pemesanan: 
   - Pilih unit yang diinginkan, lalu klik tombol Booking Sekarang.
   - Isi formulir pemesanan (Nama, Nomor WhatsApp, dan Tanggal/Durasi sewa).
   - Sistem akan otomatis mengecek ketersediaan jadwal agar tidak bentrok dengan penyewa lain.
   - Setelah berhasil, data pesanan akan masuk dengan status pending.

B. Sisi Pengelola (Admin Dashboard)
1. Login Admin: Masuk ke halaman login.php menggunakan kredensial khusus admin:
   - Username: admin
   - Password: password123 (atau sesuai yang telah diubah).
2. Dashboard Admin (admin.php):
   - Acc Pesanan Masuk: Admin dapat melihat daftar transaksi dari pelanggan dan menekan tombol Konfirmasi Lunas untuk menyetujui pesanan.
   - Kelola Unit: Admin dapat mengubah status ketersediaan unit sewaktu-waktu (pilihan: available, booked, atau maintenance).
3. Log Out: Admin maupun pelanggan dapat mengakhiri sesi dengan menekan tombol Log Out di bagian navigasi atas.
