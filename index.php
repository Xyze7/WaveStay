<?php
include 'koneksi.php';
session_start();

// Fungsi Log Out
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
if ($kategori && in_array($kategori, ['villa', 'speedboat', 'jetski', 'snorkeling'])) {
    $query = "SELECT * FROM units WHERE category = '$kategori'";
} else {
    $query = "SELECT * FROM units";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WaveStay - Island Rental Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 antialiased selection:bg-yellow-400 selection:text-slate-950">

    <!-- Navbar Pelanggan -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800 transition-all duration-300">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-extrabold tracking-wider text-white flex items-center gap-2">
                Wave<span class="text-yellow-400">Stay</span>
            </a>
            <div class="space-x-1 md:space-x-3 text-sm font-medium flex items-center">
                <a href="index.php" class="px-4 py-2 rounded-full text-yellow-400 hover:bg-yellow-400/10 transition">Beranda</a>
                <a href="#katalog" class="px-4 py-2 rounded-full text-slate-300 hover:text-white hover:bg-slate-800 transition">Daftar Sewa</a>
                
                <?php if (isset($_SESSION['role'])): ?>
                    <!-- Jika sudah login -->
                    <span class="text-xs text-slate-300 px-2 hidden sm:inline">Hi, <strong class="text-yellow-400"><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="admin.php" class="px-3 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-500 transition">Admin Panel</a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="px-4 py-2 rounded-xl bg-rose-950/80 border border-rose-500/40 text-rose-300 hover:bg-rose-900 transition text-xs font-semibold">Log Out</a>
                <?php else: ?>
                    <!-- Jika belum login -->
                    <a href="login.php" class="px-4 py-2 rounded-full bg-slate-900 border border-slate-700 text-white hover:bg-slate-800 transition">Sign In</a>
                    <a href="register.php" class="px-4 py-2 rounded-full bg-yellow-400 hover:bg-yellow-300 text-slate-950 font-bold shadow-lg shadow-yellow-400/20 transition">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section Background Laut (Gradasi Mulus & Terang Alami) -->
    <header class="relative min-h-[90vh] flex items-center justify-center text-center px-4 overflow-hidden pt-20">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80" alt="Ocean Background" class="w-full h-full object-cover object-center scale-105 filter brightness-90">
            <!-- Gradasi disempurnakan agar bagian bawah tidak gelap pekat dan transisinya halus -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/40 via-slate-950/20 to-slate-900"></div>
        </div>

        <div class="relative z-10 max-w-3xl mx-auto space-y-6">
            <span class="inline-block px-4 py-1.5 rounded-full bg-yellow-400/20 border border-yellow-400/40 text-yellow-300 text-xs font-bold uppercase tracking-widest animate-float">
                🌊 #1 Island Experience Platform
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight tracking-tight drop-shadow-lg">
                Jelajahi Surga Tropis Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-amber-200">WaveStay</span>
            </h1>
            <p class="text-base md:text-lg text-slate-200 max-w-xl mx-auto font-light drop-shadow">
                Pilih fasilitas liburan pulau favoritmu, mulai dari villa mewah, speedboat, jetski, hingga paket snorkeling terbaik.
            </p>
            <div class="pt-4 flex justify-center gap-4">
                <a href="#katalog" class="bg-yellow-400 hover:bg-yellow-300 text-slate-950 font-bold py-3.5 px-8 rounded-xl shadow-xl shadow-yellow-400/20 transition-all duration-300 transform hover:-translate-y-1">
                    Mulai Pilih Unit
                </a>
            </div>
        </div>
    </header>

    <!-- Katalog Unit -->
    <main id="katalog" class="container mx-auto py-20 px-4 md:px-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4 border-b border-slate-800 pb-6">
            <div>
                <h3 class="text-3xl font-extrabold text-white tracking-tight">Katalog Pembookingan Unit</h3>
                <p class="text-sm text-slate-400 mt-1">Pilih kategori layanan dan amankan jadwal liburanmu.</p>
            </div>
            
            <div class="flex flex-wrap gap-2 bg-slate-950 p-1.5 rounded-2xl border border-slate-800">
                <a href="index.php#katalog" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-300 <?php echo ($kategori == '') ? 'bg-yellow-400 text-slate-950 shadow-md font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900'; ?>">Semua</a>
                <a href="index.php?kategori=villa#katalog" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-300 <?php echo ($kategori == 'villa') ? 'bg-yellow-400 text-slate-950 shadow-md font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900'; ?>">Villa</a>
                <a href="index.php?kategori=speedboat#katalog" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-300 <?php echo ($kategori == 'speedboat') ? 'bg-yellow-400 text-slate-950 shadow-md font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900'; ?>">Speedboat</a>
                <a href="index.php?kategori=jetski#katalog" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-300 <?php echo ($kategori == 'jetski') ? 'bg-yellow-400 text-slate-950 shadow-md font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900'; ?>">Jetski</a>
                <a href="index.php?kategori=snorkeling#katalog" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-300 <?php echo ($kategori == 'snorkeling') ? 'bg-yellow-400 text-slate-950 shadow-md font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900'; ?>">Snorkeling</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)) : ?>
                    <div class="bg-slate-950 rounded-2xl shadow-xl overflow-hidden border border-slate-800/80 flex flex-col justify-between transition-all duration-500 transform hover:-translate-y-2 hover:border-yellow-400/50 group">
                        <div>
                            <div class="h-52 relative overflow-hidden bg-slate-900">
                                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                                <span class="absolute top-3 right-3 text-xs font-bold uppercase tracking-wider px-3 py-1 bg-slate-950/80 backdrop-blur-md border border-yellow-400/30 text-yellow-300 rounded-full">
                                    <?php echo $row['category']; ?>
                                </span>
                            </div>

                            <div class="p-6">
                                <?php if (strpos($row['name'], 'Affordable') !== false): ?>
                                    <span class="px-2.5 py-0.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold rounded-full uppercase">Affordable</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 bg-amber-500/10 border border-amber-500/30 text-amber-400 text-[10px] font-bold rounded-full uppercase">Pricey / Luxury</span>
                                <?php endif; ?>

                                <h4 class="text-xl font-bold text-white group-hover:text-yellow-400 transition duration-300 mt-2"><?php echo htmlspecialchars($row['name']); ?></h4>
                                <p class="text-sm text-slate-400 mt-1 flex items-center gap-1.5">
                                    <span>👥</span> Kapasitas: <?php echo $row['capacity']; ?> Orang
                                </p>
                                
                                <div class="mt-6 flex items-baseline justify-between">
                                    <div>
                                        <span class="text-xs text-slate-500 block">Harga Sewa</span>
                                        <span class="text-lg font-extrabold text-yellow-400">
                                            Rp <?php echo number_format($row['price'], 0, ',', '.'); ?> 
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            <?php echo ($row['price_type'] == 'per_night') ? '/malam' : '/jam'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 pt-0">
                            <?php if($row['status'] == 'available'): ?>
                                <a href="booking.php?id=<?php echo $row['id']; ?>" class="block text-center bg-yellow-400 hover:bg-yellow-300 text-slate-950 font-bold py-3 rounded-xl transition shadow-lg shadow-yellow-400/10">
                                    Booking Sekarang &rarr;
                                </a>
                            <?php else: ?>
                                <button disabled class="w-full bg-slate-800 text-slate-500 font-semibold py-3 rounded-xl cursor-not-allowed border border-slate-700">
                                    <?php echo ucfirst($row['status']); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-20 text-slate-500">
                    <p class="text-lg">Belum ada unit tersedia untuk kategori ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-slate-950 border-t border-slate-800 text-slate-400 text-center py-10 mt-20">
        <div class="container mx-auto px-4 space-y-3">
            <h2 class="text-xl font-bold text-white tracking-wider">Wave<span class="text-yellow-400">Stay</span>.</h2>
            <p class="text-sm">Platform Rental Villa, Speedboat, Jetski, dan Snorkeling.</p>
            <p class="text-xs text-slate-600 pt-4">&copy; 2026 WaveStay. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>