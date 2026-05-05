<?php
require_once 'config/database.php';

$errors = [];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?msg=invalid");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php?msg=notfound");
    exit;
}

$data = $result->fetch_assoc();

$kode = $data['kode_kategori'];
$nama = $data['nama_kategori'];
$deskripsi = $data['deskripsi'];
$status = $data['status'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $status = $_POST['status'];

    if (empty($kode) || strlen($kode) < 4 || strpos($kode, 'KAT-') !== 0) {
        $errors[] = "Kode harus format KAT-XXX";
    }

    if (empty($nama) || strlen($nama) < 3) {
        $errors[] = "Nama minimal 3 karakter";
    }

    if (!empty($deskripsi) && strlen($deskripsi) > 200) {
        $errors[] = "Deskripsi maksimal 200 karakter";
    }

    if ($status !== 'Aktif' && $status !== 'Nonaktif') {
        $errors[] = "Status tidak valid";
    }

    $cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ? AND id_kategori != ?");
    $cek->bind_param("si", $kode, $id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $errors[] = "Kode kategori sudah digunakan";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE kategori SET kode_kategori=?, nama_kategori=?, deskripsi=?, status=? WHERE id_kategori=?");
        $stmt->bind_param("ssssi", $kode, $nama, $deskripsi, $status, $id);

        if ($stmt->execute()) {
            header("Location: index.php?msg=updated");
            exit;
        } else {
            $errors[] = "Gagal update data";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Kategori</h4>
                </div>
                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $e): ?>
                                    <li><?= $e ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Kode Kategori</label>
                            <input type="text" name="kode" class="form-control" value="<?= $kode ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Nama Kategori</label>
                            <input type="text" name="nama" class="form-control" value="<?= $nama ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control"><?= $deskripsi ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Status</label><br>
                            <input type="radio" name="status" value="Aktif" <?= $status=='Aktif'?'checked':'' ?>> Aktif
                            <input type="radio" name="status" value="Nonaktif" <?= $status=='Nonaktif'?'checked':'' ?>> Nonaktif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>