<?php
session_start();

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once '../config/koneksi.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '' || $confirm === '') {
        $error = 'Semua field wajib diisi.';
    } elseif ($password !== $confirm) {
        $error = 'Password dan konfirmasi password tidak cocok.';
    } else {
        // cek username duplikat
        $check = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
        $check->bind_param('s', $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'Username sudah digunakan.';
        } else {
            $ins = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $ins->bind_param('ss', $username, $password);
            $ins->execute();
            $ins->close();
            $success = 'Akun berhasil dibuat. Silakan masuk.';
        }
        $check->close();
    }
}


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - MAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../css/auth.css" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper">

    <div class="auth-panel">
        <div class="brand">MAM.</div>

        <h2>Daftar Admin Baru</h2>
        <p class="subtitle">Kelola stok kamera, lensa, dan perlengkapan produksi dengan mudah.</p>

        <?php if ($error): ?>
            <div class="alert-auth mb-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert-success-auth mb-3"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Username Admin</label>
                <input type="text" name="username" class="form-control" placeholder="Masukan Username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukan Password">
            </div>
            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Masukan Ulang Password">
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Buat Akun Sekarang</button>
        </form>

        <div class="footer-link mb-2">
            Sudah punya akun? <a href="../index.php">Masuk di sini</a>
        </div>
        <div class="copy">© 2026 Multimedia Lab Team - All rights reserved.</div>
    </div>

    <div class="auth-hero">
        <img src="../assets/img/hero.jpg" alt="Hero">
        <div class="hero-text">
            <h1>Capture Every Asset</h1>
            <p>Sistem Terpadu untuk Monitoring Kamera, Lensa, dan Peralatan Kreatif.</p>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>