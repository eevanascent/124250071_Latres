<?php
// detail
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
$asset  = $result->fetch_assoc();
$stmt->close();

if (!$asset) {
    header('Location: dashboard.php');
    exit;
}
// eof detail
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail — <?= htmlspecialchars($asset['nama_alat']) ?> — MAM</title>
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
    <div class="detail-wrapper">

        <a href="dashboard.php" class="back-link mb-3 d-inline-flex">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>

        <div class="detail-img-wrap mb-4">
            <img src="<?= htmlspecialchars($asset['url_gambar']) ?>"
                 alt="<?= htmlspecialchars($asset['nama_alat']) ?>"
                 onerror="this.src='../assets/img/no-image.svg'">
        </div>

        <div class="detail-meta mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <label>SERIAL NUMBER</label>
                    <div class="val"><?= htmlspecialchars($asset['serial_number']) ?></div>
                </div>
                <?php
                $badgeClass = 'badge-tersedia';
                if ($asset['status'] === 'Dipinjam')    $badgeClass = 'badge-dipinjam';
                elseif ($asset['status'] === 'Maintenance') $badgeClass = 'badge-maintenance';
                ?>
                <span class="badge-status <?= $badgeClass ?>">
                    <?= htmlspecialchars($asset['status']) ?>
                </span>
            </div>

            <div class="mb-3">
                <label>NAMA ASSET / MODEL</label>
                <div class="val"><?= htmlspecialchars($asset['nama_alat']) ?></div>
                <div class="val-sm">Merk: <?= htmlspecialchars($asset['merk']) ?></div>
            </div>

            <hr class="my-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <label>KETERSEDIAAN STOK</label>
                    <div class="val"><?= $asset['jumlah'] ?> Unit</div>
                </div>
                <a href="edit.php?id=<?= $asset['id_asset'] ?>" class="btn-edit-detail">
                    <i class="bi bi-pencil-square"></i> Edit Data
                </a>
            </div>
        </div>

        <div class="detail-footer">
            ID Aset: #<?= str_pad($asset['id_asset'], 5, '0', STR_PAD_LEFT) ?> | Terdaftar dalam sistem MAM.
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>