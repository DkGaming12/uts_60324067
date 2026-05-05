<?php
require_once 'config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?msg=invalid");
    exit;
}

$id = (int) $_GET['id'];

$cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE id_kategori = ?");
$cek->bind_param("i", $id);
$cek->execute();
$result = $cek->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php?msg=notfound");
    exit;
}

$stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?msg=deleted");
    } else {
        header("Location: index.php?msg=failed");
    }
} else {
    header("Location: index.php?msg=error");
}

exit;
?>