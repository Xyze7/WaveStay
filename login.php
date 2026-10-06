<?php
include 'koneksi.php';
session_start();

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cek apakah akun khusus Admin
    if ($username === 'admin' && $password === 'dea711') {
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit;
    } else {
        // Untuk pelanggan umum (simulasi login sukses)
        $_SESSION['role'] = 'customer';
        $_SESSION['username'] = $username;
        
        // Jika sebelumnya pelanggan mencoba membuka halaman booking, kembalikan ke sana
        if (isset($_SESSION['redirect_after_login'])) {
            $target = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: $target");
            exit;
        } else {
            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - WaveStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-slate-100 antialiased min-h-screen flex items-center justify-center relative px-4 selection:bg-yellow-400 selection:text-slate-950">

    <div class="fixed inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80" alt="Ocean Background" class="w-full h-full object-cover filter brightness-[0.5]">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/50 to-slate-950/70"></div>
    </div>

    <div class="relative z-10 w-full max-w-md bg-slate-950/90 backdrop-blur-xl p-8 rounded-3xl shadow-2xl border border-slate-800 space-y-6">
        <div class="text-center">
            <a href="index.php" class="text-2xl font-extrabold tracking-wider text-white">Wave<span class="text-yellow-400">Stay</span>.</a>
            <h2 class="text-xl font-bold text-white mt-4">Sign In untuk Melanjutkan</h2>
            <p class="text-xs text-slate-400 mt-1">Kamu harus masuk terlebih dahulu sebelum bisa melakukan pemesanan unit.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-3 rounded-xl text-xs font-semibold">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Username / Email</label>
                <input type="text" name="username" required placeholder="" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
            </div>

            <button type="submit" name="login" class="w-full bg-yellow-400 hover:bg-yellow-300 text-slate-950 font-bold py-3.5 rounded-xl transition shadow-lg shadow-yellow-400/10 mt-2">
                Sign In Sekarang
            </button>
        </form>

        <p class="text-center text-xs text-slate-400">
            Belum punya akun? <a href="register.php" class="text-yellow-400 font-semibold hover:underline">Sign Up di sini</a>
        </p>
        <div class="text-center pt-2">
            <a href="index.php" class="text-xs text-slate-500 hover:text-slate-300">&larr; Kembali ke Beranda Utama</a>
        </div>
    </div>

</body>
</html>