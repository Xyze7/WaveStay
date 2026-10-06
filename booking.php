<?php
include 'koneksi.php';
session_start();

$unit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 🔒 CEK APAKAH PELANGGAN SUDAH LOGIN ATAU BELUM
if (!isset($_SESSION['role'])) {
    // Simpan dulu halaman yang ingin dituju agar setelah login bisa langsung balik ke sini
    $_SESSION['redirect_after_login'] = "booking.php?id=" . $unit_id;
    header("Location: login.php");
    exit;
}

$query = "SELECT * FROM units WHERE id = $unit_id";
$result = mysqli_query($conn, $query);
$unit = mysqli_fetch_assoc($result);

if (!$unit) {
    header("Location: index.php");
    exit;
}

// 🌊 BACKGROUND DINAMIS BERDASARKAN KATEGORI
if ($unit['category'] == 'villa') {
    $bg_image = 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=1920&q=80';
} elseif ($unit['category'] == 'snorkeling') {
    $bg_image = 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1920&q=80';
} else {
    $bg_image = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80';
}

$pesan_sukses = "";

if (isset($_POST['submit_booking'])) {
    $customer_name  = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
    $start_date     = $_POST['start_date'];
    
    $total_price    = 0;
    $end_date       = "NULL";
    $total_hours    = "NULL";

    if ($unit['price_type'] == 'per_night') {
        $end_date = $_POST['end_date'];
        
        $cek_bentrok = mysqli_query($conn, "SELECT * FROM bookings WHERE unit_id = $unit_id AND payment_status != 'cancelled' AND NOT ('$end_date' <= start_date OR '$start_date' >= end_date)");
        
        if (mysqli_num_rows($cek_bentrok) > 0) {
            $error = "Maaf, unit ini sudah dibooking orang lain pada tanggal tersebut! Silakan pilih tanggal lain.";
        } else {
            $datetime1 = new DateTime($start_date);
            $datetime2 = new DateTime($end_date);
            $interval = $datetime1->diff($datetime2);
            $nights = $interval->days;

            if ($nights > 0) {
                $total_price = $nights * $unit['price'];
                $end_date = "'$end_date'";
            } else {
                $error = "Tanggal selesai harus minimal 1 malam setelah tanggal mulai!";
            }
        }
    } else {
        $total_hours = intval($_POST['total_hours']);
        $cek_bentrok = mysqli_query($conn, "SELECT * FROM bookings WHERE unit_id = $unit_id AND start_date = '$start_date' AND payment_status != 'cancelled'");
        
        if (mysqli_num_rows($cek_bentrok) > 0) {
            $error = "Maaf, unit ini sudah di-booking pada tanggal tersebut. Silakan pilih jadwal jam/hari lain!";
        } else {
            if ($total_hours > 0) {
                $total_price = $total_hours * $unit['price'];
            } else {
                $error = "Jumlah jam sewa tidak boleh 0!";
            }
        }
    }

    if (!isset($error)) {
        $sql_insert = "INSERT INTO bookings (unit_id, customer_name, customer_phone, start_date, end_date, total_hours, total_price, payment_status) 
                       VALUES ($unit_id, '$customer_name', '$customer_phone', '$start_date', $end_date, $total_hours, $total_price, 'pending')";
        
        if (mysqli_query($conn, $sql_insert)) {
            $pesan_sukses = "Pemesanan berhasil! Total tagihan Anda: Rp " . number_format($total_price, 0, ',', '.') . ". Menunggu konfirmasi admin.";
        } else {
            $error = "Gagal menyimpan pesanan: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking - WaveStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-slate-100 antialiased min-h-screen flex flex-col justify-between selection:bg-yellow-400 selection:text-slate-950 relative overflow-x-hidden">

    <div class="fixed inset-0 z-0">
        <img src="<?php echo $bg_image; ?>" alt="Dynamic Background" class="w-full h-full object-cover filter brightness-[0.65] contrast-110">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-slate-950/60"></div>
    </div>

    <nav class="bg-slate-950/80 backdrop-blur-md border-b border-slate-800 p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center px-4">
            <h1 class="text-xl font-extrabold tracking-wider text-white">Wave<span class="text-yellow-400">Stay</span>.</h1>
            <div class="flex items-center gap-4">
                <span class="text-xs text-slate-300 hidden sm:inline">Halo, <strong class="text-yellow-400"><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a href="index.php" class="text-sm text-yellow-400 hover:underline">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-12 px-4 max-w-xl my-auto relative z-10">
        <div class="bg-slate-950/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-slate-800/80 space-y-6">
            
            <div>
                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 bg-yellow-400/10 text-yellow-300 border border-yellow-400/30 rounded-full">
                    <?php echo $unit['category']; ?>
                </span>
                <h2 class="text-2xl font-bold text-white mt-3">Form Pemesanan Unit</h2>
                <p class="text-xs text-slate-400">Sistem otomatis melindungi jadwal dari bentrok penyewa lain.</p>
            </div>

            <div class="flex items-center gap-4 bg-slate-900/90 p-4 rounded-2xl border border-slate-800">
                <img src="<?php echo $unit['image']; ?>" alt="<?php echo $unit['name']; ?>" class="w-20 h-20 object-cover rounded-xl border border-slate-700">
                <div>
                    <h3 class="font-bold text-white text-base"><?php echo htmlspecialchars($unit['name']); ?></h3>
                    <p class="text-yellow-400 font-extrabold text-sm mt-1">
                        Rp <?php echo number_format($unit['price'], 0, ',', '.'); ?> 
                        <span class="text-xs text-slate-400 font-normal"><?php echo ($unit['price_type'] == 'per_night') ? '/malam' : '/jam'; ?></span>
                    </p>
                </div>
            </div>

            <?php if (!empty($pesan_sukses)): ?>
                <div class="bg-emerald-950/90 border border-emerald-500/50 text-emerald-300 p-4 rounded-xl text-sm space-y-2">
                    <p><?php echo $pesan_sukses; ?></p>
                    <a href="index.php" class="inline-block bg-yellow-400 text-slate-950 px-4 py-2 rounded-lg font-bold text-xs">Kembali ke Katalog</a>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="bg-rose-950/90 border border-rose-500/50 text-rose-300 p-4 rounded-xl text-sm font-semibold">
                    ⚠️ <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="customer_name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor WhatsApp / HP</label>
                    <input type="text" name="customer_phone" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Tanggal Mulai / Check-in</label>
                    <input type="date" name="start_date" required class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
                </div>

                <?php if ($unit['price_type'] == 'per_night'): ?>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Tanggal Selesai / Check-out</label>
                        <input type="date" name="end_date" required class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
                    </div>
                <?php else: ?>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 memperkecil mb-1">Durasi Sewa (Jam)</label>
                        <input type="number" name="total_hours" min="1" value="1" required class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
                    </div>
                <?php endif; ?>

                <button type="submit" name="submit_booking" class="w-full bg-yellow-400 hover:bg-yellow-300 text-slate-950 font-bold py-3.5 rounded-xl transition shadow-lg shadow-yellow-400/10 mt-4">
                    Konfirmasi & Pesan Sekarang
                </button>
            </form>
        </div>
    </main>

    <footer class="text-center py-6 text-xs text-slate-500 border-t border-slate-800 relative z-10">
        &copy; 2026 WaveStay. All Rights Reserved.
    </footer>

</body>
</html>