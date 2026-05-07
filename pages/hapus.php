<?php
require_once '../config/session.php';
requireLogin();
require_once '../config/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $del = $conn->prepare("DELETE FROM assets WHERE id_asset = ?");
    $del->bind_param('i', $id);
    $del->execute();
    $del->close();
}

header('Location: dashboard.php');
exit;
