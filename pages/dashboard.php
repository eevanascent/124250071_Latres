<?php

require_once '../config/session.php';
requireLogin();
require_once '../config/koneksi.php';

$assets = $conn->query("SELECT * FROM assets ORDER BY id_asset ASC");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - MAM</title>
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

<div class="container-fluid py-4">

    <!-- page header -->
    <div class="page-header mb-4">
        <div>
            <h2>Inventaris Alat Multimedia</h2>
            <p class="sub">Kelola stok kamera, lensa, dan aksesoris studio.</p>
        </div>
        <a href="tambah.php" class="btn btn-dark btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i> Tambah Alat
        </a>
    </div>
    <!-- eof pageheader -->

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-mam w-100 mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Serial Number</th>
                        <th>Nama Alat</th>
                        <th>Merk</th>
                        <th>Status</th>
                        <th style="width:80px">Jumlah</th>
                        <th style="width:120px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($assets->num_rows === 0): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data aset.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; while ($row = $assets->fetch_assoc()): ?>
                        <?php
                        $rowClass = '';
                        if ($row['status'] === 'Maintenance') $rowClass = 'table-warning';
                        elseif ($row['status'] === 'Dipinjam')  $rowClass = 'table-info';
                        ?>
                        <tr class="<?= $rowClass ?>">
                            <td><?= $no++ ?></td>
                            <td><span class="sn-badge"><?= htmlspecialchars($row['serial_number']) ?></span></td>
                            <td><?= htmlspecialchars($row['nama_alat']) ?></td>
                            <td><?= htmlspecialchars($row['merk']) ?></td>
                            <td>
                                <?php
                                $badgeClass = 'badge-tersedia';
                                if ($row['status'] === 'Dipinjam')    $badgeClass = 'badge-dipinjam';
                                elseif ($row['status'] === 'Maintenance') $badgeClass = 'badge-maintenance';
                                ?>
                                <span class="badge-status <?= $badgeClass ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>
                            <td><?= $row['jumlah'] ?></td>
                            <td class="text-center">
                                <a href="detail.php?id=<?= $row['id_asset'] ?>" class="action-btn action-btn-view me-1" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="edit.php?id=<?= $row['id_asset'] ?>" class="action-btn action-btn-edit me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="hapus.php?id=<?= $row['id_asset'] ?>"
                                   class="action-btn action-btn-del"
                                   title="Hapus"
                                   onclick="return confirm('Yakin ingin menghapus aset ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
