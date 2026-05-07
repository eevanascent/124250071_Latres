<?php
session_start();

if (!empty($_SESSION['user_id'])) {
    header('Location: pages/dashboard.php');
    exit;
}

require_once 'config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $conn->prepare("SELECT id_user, username FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            header('Location: pages/dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
        $stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - MAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/auth.css" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper">

    <div class="auth-hero">
        <img src="assets/img/hero.jpg" alt="https://images.unsplash.com/photo-1622641975099-00367502f581?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
        <div class="hero-text">
            <h1>MAM System</h1>
            <p>Portal Manajemen Kamera, Lensa, dan Perlengkapan Produksi.</p>
        </div>
    </div>

    <div class="auth-panel">
        <div class="brand">MAM.</div>

        <h2>Selamat Datang</h2>
        <p class="subtitle">Silakan masuk untuk mengelola aset multimedia.</p>

        <?php if ($error): ?>
            <div class="alert-auth mb-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan Username"
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                   form="form-login">
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukan Password"
                   form="form-login">
        </div>

        <form id="form-login" method="POST" action="">
            <button type="submit" class="btn btn-primary w-100 mb-3">Masuk Sekarang</button>
        </form>

        <div class="footer-link mb-2">
            Belum punya akun? <a href="pages/register.php">Daftar Akun</a>
        </div>
        <div class="copy">© 2026 Multimedia Lab Team - All rights reserved.</div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
