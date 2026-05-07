<?php
require_once '../config/session.php';
requireLogin();
require_once '../config/koneksi.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serial = trim($_POST['serial_number'] ?? '');
    $nama = trim($_POST['nama_alat'] ?? '');
    $merk = trim($_POST['merk'] ?? '');
    $status = $_POST['status'] ?? '';
    $jumlah = intval($_POST['jumlah'] ?? 0);
    $url = trim($_POST['url_gambar'] ?? '');

    $validSN = false;
    if (preg_match('/^CAM-[A-Z0-9]+-[A-Z0-9]+$/i', $serial)) $validSN = true;
    elseif (preg_match('/^LNS-[A-Z0-9]+-[A-Z0-9]+$/i', $serial)) $validSN = true;
    elseif (preg_match('/^DRN-[A-Z0-9]+-[A-Z0-9]+$/i', $serial)) $validSN = true;

    if ($serial === '' || $nama === '' || $merk === '' || $status === '' || $jumlah <= 0 || $url === '') {
        $error = 'Semua field wajib diisi dengan benar.';
    } elseif (!$validSN) {
        $error = 'Format Serial Number tidak valid. Gunakan format CAM-[MERK]-[NOMOR], LNS-[MERK]-[JARAK], atau DRN-[MERK]-[NOMOR].';
    } else {
        // cek duplikat serial number
        $cek = $conn->prepare("SELECT id_asset FROM assets WHERE serial_number = ?");
        $cek->bind_param('s', $serial);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error = 'Serial Number sudah digunakan. Harus unik.';
        } else {
            $ins = $conn->prepare("INSERT INTO assets (serial_number, nama_alat, merk, status, jumlah, url_gambar) VALUES (?,?,?,?,?,?)");
            $ins->bind_param('ssssis', $serial, $nama, $merk, $status, $jumlah, $url);
            $ins->execute();
            $ins->close();
            header('Location: dashboard.php');
            exit;
        }
        $cek->close();
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Alat — MAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../css/app.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- nav -->
<nav class="navbar-mam d-flex align-items-center justify-content-between">
    <a class="brand" href="dashboard.php">MAM.</a>
    <div class="nav-right">
        <span class="admin-label">Admin Multimedia</span>
        <a href="logout.php" class="btn-logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</nav>
<!-- eof nav -->

<div class="container py-4">
    <div class="form-page-wrapper">

        <a href="dashboard.php" class="back-link mb-3 d-inline-flex">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>

        <h2 class="fw-bold mb-1">Registrasi Alat Multimedia</h2>
        <p class="text-muted mb-4" style="font-size:.85rem">Tambahkan unit kamera, lensa, atau aksesoris baru ke dalam inventaris.</p>

        <?php if ($error): ?>
            <div class="alert alert-danger mb-3" style="font-size:.85rem"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <div class="row g-0">
            <div class="col-md-7 form-col">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control"
                                   placeholder="Contoh: CAM-SONY-001"
                                   value="<?= htmlspecialchars($_POST['serial_number'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Alat</label>
                            <input type="text" name="nama_alat" class="form-control"
                                   placeholder="Contoh: Sony Alpha a7 III Mirrorless"
                                   value="<?= htmlspecialchars($_POST['nama_alat'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" name="merk" class="form-control"
                                   placeholder="Contoh: Sony"
                                   value="<?= htmlspecialchars($_POST['merk'] ?? '') ?>">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-7">
                                <label class="form-label">Status Awal</label>
                                <select name="status" class="form-select">
                                    <option value="">Pilih Status</option>
                                    <option value="Tersedia"    <?= (($_POST['status'] ?? '') === 'Tersedia')    ? 'selected' : '' ?>>Tersedia</option>
                                    <option value="Dipinjam"   <?= (($_POST['status'] ?? '') === 'Dipinjam')   ? 'selected' : '' ?>>Dipinjam</option>
                                    <option value="Maintenance"<?= (($_POST['status'] ?? '') === 'Maintenance') ? 'selected' : '' ?>>Maintenance</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label class="form-label">Jumlah Unit</label>
                                <input type="number" name="jumlah" class="form-control" min="1"
                                       value="<?= htmlspecialchars($_POST['jumlah'] ?? '1') ?>">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-image me-1"></i> Link Foto Perangkat (URL)
                            </label>
                            <input type="url" name="url_gambar" class="form-control"
                                   placeholder="https://example.com/camera.jpg"
                                   value="<?= htmlspecialchars($_POST['url_gambar'] ?? '') ?>">
                            <div class="form-text">Gunakan URL gambar dari internet (Unsplash/Imgur).</div>
                        </div>
                        <button type="submit" class="btn-save">
                            <i class="bi bi-floppy"></i> Simpan Alat Multimedia
                        </button>
                    </form>
            </div>

            <div class="col-md-5 info-col">
                <div class="info-card">
                    <h6 class="mb-3"><i class="bi bi-info-circle-fill me-1"></i> Penomoran Asset</h6>
                    <p class="text-muted mb-3" style="font-size:.82rem">Format Serial Number (SN) untuk peralatan multimedia lab:</p>

                    <div class="mb-3">
                        <span class="sn-tag sn-tag-cam me-1">CAM</span> <strong>Kamera (Body/Kit)</strong><br>
                        <small class="text-muted">CAM-[MERK]-[NOMOR]</small><br>
                        <small class="text-muted">Contoh: CAM-SONY-001</small>
                    </div>
                    <div class="mb-3">
                        <span class="sn-tag sn-tag-lns me-1">LNS</span> <strong>Lensa & Optik</strong><br>
                        <small class="text-muted">LNS-[MERK]-[JARAK]</small><br>
                        <small class="text-muted">Contoh: LNS-CAN-50MM</small>
                    </div>
                    <div class="mb-4">
                        <span class="sn-tag sn-tag-drn me-1">DRN</span> <strong>Drone & Gimbal</strong><br>
                        <small class="text-muted">DRN-[MERK]-[NOMOR]</small><br>
                        <small class="text-muted">Contoh: DRN-DJI-05</small>
                    </div>

                    <div class="info-note">
                        <i class="bi bi-lightbulb-fill me-1"></i>
                        Pastikan SN unik untuk setiap unit agar pelacakan peminjaman lebih akurat.
                    </div>
                </div>
            </div>
        </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
