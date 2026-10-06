<?php
include 'koneksi.php';
session_start();

// Validasi Keamanan: Jika belum login sebagai admin, lempar ke halaman login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.php");
    exit;
}

if (isset($_POST['update_status'])) {
    $unit_id = intval($_POST['unit_id']);
    $new_status = $_POST['new_status'];
    mysqli_query($conn, "UPDATE units SET status = '$new_status' WHERE id = $unit_id");
    header("Location: admin.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'confirm_booking') {
    $booking_id = intval($_GET['id']);
    mysqli_query($conn, "UPDATE bookings SET payment_status = 'success' WHERE id = $booking_id");
    header("Location: admin.php");
    exit;
}

$query_bookings = "SELECT bookings.*, units.name AS unit_name, units.category, units.image 
                   FROM bookings 
                   JOIN units ON bookings.unit_id = units.id 
                   ORDER BY bookings.created_at DESC";
$result_bookings = mysqli_query($conn, $query_bookings);

$query_units = "SELECT * FROM units";
$result_units = mysqli_query($conn, $query_units);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WaveStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-slate-100 antialiased selection:bg-yellow-400 selection:text-slate-950 relative min-h-screen">

    <!-- Background Laut Halus untuk Admin -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80" alt="Ocean Background" class="w-full h-full object-cover filter brightness-[0.3] blur-[1px]">
        <div class="absolute inset-0 bg-slate-950/80"></div>
    </div>

    <!-- Navbar Admin Terpisah -->
    <nav class="bg-slate-950/90 backdrop-blur-md border-b border-slate-800 p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center px-4">
            <h1 class="text-xl font-bold tracking-wider text-white">Wave<span class="text-yellow-400">Stay</span> <span class="text-blue-400 text-xs font-normal">Admin Panel</span></h1>
            <div class="flex items-center gap-3">
                <a href="index.php" target="_blank" class="text-xs bg-slate-900 border border-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-xl transition">Lihat Website &rarr;</a>
                <a href="admin.php?action=logout" class="text-xs bg-rose-950/80 border border-rose-500/40 text-rose-300 hover:bg-rose-900 px-3 py-2 rounded-xl transition font-semibold">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Konten Utama Admin -->
    <main class="container mx-auto py-10 px-4 space-y-10 max-w-6xl relative z-10">

        <!-- Tabel Transaksi Masuk -->
        <section class="bg-slate-950/90 backdrop-blur-xl rounded-3xl shadow-xl p-6 md:p-8 border border-slate-800">
            <h2 class="text-xl font-bold text-white mb-2">Kelola & Acc Pesanan Masuk</h2>
            <p class="text-xs text-slate-400 mb-6">Daftar transaksi dari pelanggan yang menunggu konfirmasi pembayaran.</p>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-800">
                            <th class="p-3.5">ID</th>
                            <th class="p-3.5">Penyewa</th>
                            <th class="p-3.5">Unit Dipesan</th>
                            <th class="p-3.5">Jadwal</th>
                            <th class="p-3.5">Total Harga</th>
                            <th class="p-3.5">Status Bayar</th>
                            <th class="p-3.5 text-center">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-800/60">
                        <?php if (mysqli_num_rows($result_bookings) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result_bookings)): ?>
                                <tr class="hover:bg-slate-900/50 transition">
                                    <td class="p-3.5 font-semibold text-slate-500">#<?php echo $row['id']; ?></td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-white"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                                        <div class="text-xs text-slate-400"><?php echo htmlspecialchars($row['customer_phone']); ?></div>
                                    </td>
                                    <td class="p-3.5">
                                        <span class="font-semibold text-yellow-400"><?php echo htmlspecialchars($row['unit_name']); ?></span>
                                    </td>
                                    <td class="p-3.5 text-xs text-slate-300">
                                        Mulai: <?php echo $row['start_date']; ?><br>
                                        <?php echo ($row['end_date']) ? "Selesai: " . $row['end_date'] : "Durasi: " . $row['total_hours'] . " Jam"; ?>
                                    </td>
                                    <td class="p-3.5 font-extrabold text-white">
                                        Rp <?php echo number_format($row['total_price'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="p-3.5">
                                        <?php if($row['payment_status'] == 'success'): ?>
                                            <span class="px-3 py-1 bg-emerald-950 border border-emerald-500/40 text-emerald-400 text-xs font-bold rounded-full">Lunas (Acc)</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-amber-950 border border-amber-500/40 text-amber-400 text-xs font-bold rounded-full">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <?php if($row['payment_status'] == 'pending'): ?>
                                            <a href="admin.php?action=confirm_booking&id=<?php echo $row['id']; ?>" class="bg-yellow-400 hover:bg-yellow-300 text-slate-950 text-xs px-3.5 py-2 rounded-xl transition font-bold shadow-md shadow-yellow-400/10">
                                                Acc / Konfirmasi Lunas
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-500 font-medium">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-500">Belum ada data pesanan masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Kelola Status Unit -->
        <section class="bg-slate-950/90 backdrop-blur-xl rounded-3xl shadow-xl p-6 md:p-8 border border-slate-800">
            <h2 class="text-xl font-bold text-white mb-2">Kelola Status Ketersediaan Unit</h2>
            <p class="text-xs text-slate-400 mb-6">Ubah status unit menjadi Available, Booked, atau Maintenance.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while($unit = mysqli_fetch_assoc($result_units)): ?>
                    <div class="border border-slate-800 rounded-2xl p-5 bg-slate-900/90 flex flex-col justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <img src="<?php echo $unit['image']; ?>" class="w-14 h-14 object-cover rounded-xl border border-slate-700">
                            <div>
                                <span class="text-[10px] font-bold uppercase px-2.5 py-0.5 bg-yellow-400/10 text-yellow-300 border border-yellow-400/30 rounded-full">
                                    <?php echo $unit['category']; ?>
                                </span>
                                <h3 class="font-bold text-white text-sm mt-1.5"><?php echo htmlspecialchars($unit['name']); ?></h3>
                            </div>
                        </div>

                        <div class="text-xs text-slate-400">Status Saat Ini: 
                            <strong class="text-yellow-400 uppercase"><?php echo $unit['status']; ?></strong>
                        </div>

                        <form action="" method="POST" class="flex gap-2">
                            <input type="hidden" name="unit_id" value="<?php echo $unit['id']; ?>">
                            <select name="new_status" class="text-xs border border-slate-700 rounded-xl px-3 py-2 bg-slate-950 text-white flex-1 focus:outline-none focus:border-yellow-400">
                                <option value="available" <?php echo ($unit['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                                <option value="booked" <?php echo ($unit['status'] == 'booked') ? 'selected' : ''; ?>>Booked</option>
                                <option value="maintenance" <?php echo ($unit['status'] == 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                            </select>
                            <button type="submit" name="update_status" class="bg-yellow-400 hover:bg-yellow-300 text-slate-950 font-bold text-xs px-4 py-2 rounded-xl transition">
                                Update
                            </button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

    </main>

</body>
</html>