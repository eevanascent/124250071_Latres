<?php
require_once '../config/session.php';
requireLogin();
require_once '../config/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM assets WHERE id_asset = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$asset= $result->fetch_assoc();
$stmt->close();

if (!$asset) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_alat'] ?? '');
    $merk = trim($_POST['merk'] ?? '');
    $status = $_POST['status'] ?? '';
    $jumlah = intval($_POST['jumlah'] ?? 0);
    $url = trim($_POST['url_gambar'] ?? '');

    if ($nama === '' || $merk === '' || $status === '' || $jumlah <= 0) {
        $error = 'Semua field wajib diisi dengan benar.';
    } else {
        if ($url === '') $url = $asset['url_gambar'];

        $upd = $conn->prepare("UPDATE assets SET nama_alat=?, merk=?, status=?, jumlah=?, url_gambar=? WHERE id_asset=?");
        $upd->bind_param('sssisi', $nama, $merk, $status, $jumlah, $url, $id);
        $upd->execute();
        $upd->close();
        header('Location: dashboard.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Alat — MAM</title>
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
            <i class="bi bi-arrow-left"></i> Batal &amp; Kembali
        </a>

        <h2 class="fw-bold mb-1">Perbarui Informasi Asset</h2>
        <p class="text-muted mb-4" style="font-size:.85rem">Lakukan perubahan pada detail perangkat untuk memastikan data inventaris tetap akurat.</p>

        <?php if ($error): ?>
            <div class="alert alert-danger mb-3" style="font-size:.85rem"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <div class="row g-0">
            <div class="col-md-7 form-col">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Serial Number</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($asset['serial_number']) ?>" readonly>
                            <div class="form-text">Serial Number tidak dapat diubah untuk menjaga integritas data.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Alat</label>
                            <input type="text" name="nama_alat" class="form-control"
                                   value="<?= htmlspecialchars($_POST['nama_alat'] ?? $asset['nama_alat']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" name="merk" class="form-control"
                                   value="<?= htmlspecialchars($_POST['merk'] ?? $asset['merk']) ?>">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-7">
                                <label class="form-label">Status Saat Ini</label>
                                <select name="status" class="form-select">
                                    <?php
                                    $cur = $_POST['status'] ?? $asset['status'];
                                    foreach (['Tersedia','Dipinjam','Maintenance'] as $s): ?>
                                        <option value="<?= $s ?>" <?= $cur === $s ? 'selected' : '' ?>><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-5">
                                <label class="form-label">Jumlah Unit</label>
                                <input type="number" name="jumlah" class="form-control" min="1"
                                       value="<?= htmlspecialchars($_POST['jumlah'] ?? $asset['jumlah']) ?>">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-image me-1"></i> Update URL Foto (Opsional)
                            </label>
                            <input type="url" name="url_gambar" class="form-control"
                                   placeholder="<?= htmlspecialchars($asset['url_gambar']) ?>"
                                   value="<?= htmlspecialchars($_POST['url_gambar'] ?? '') ?>">
                        </div>
                        <button type="submit" class="btn-save">
                            <i class="bi bi-floppy"></i> Simpan Perubahan Data
                        </button>
                    </form>
            </div>

            <div class="col-md-5 info-col">
                <div class="info-card">
                    <h6 class="mb-3"><i class="bi bi-pencil-square me-1"></i> Mode Penyuntingan</h6>
                    <p style="font-size:.82rem" class="text-muted mb-3">Anda sedang mengubah data asset. Pastikan untuk:</p>

                    <div class="info-item">
                        <i class="bi bi-check2-square"></i>
                        <span>Memverifikasi <strong>status terbaru</strong> (apakah alat baru saja kembali atau masuk servis).</span>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-check2-square"></i>
                        <span>Memastikan <strong>jumlah unit</strong> sudah sesuai dengan stok fisik di lemari penyimpanan.</span>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-check2-square"></i>
                        <span>Memperbarui <strong>URL foto</strong> jika terdapat kerusakan fisik yang perlu didokumentasikan.</span>
                    </div>

                    <div class="info-warning mt-3">
                        <i class="bi bi-exclamation-circle-fill me-1"></i>
                        Perubahan ini akan langsung berdampak pada laporan ketersediaan alat di Dashboard.
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