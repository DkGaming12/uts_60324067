<?php
require_once 'config/database.php';

$stmt = $conn->prepare("SELECT * FROM kategori ORDER BY id_kategori DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Daftar Kategori</h2>
        <a href="create.php" class="btn btn-primary mb-3">Tambah</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['kode_kategori'] ?></td>
                        <td><?= $row['nama_kategori'] ?></td>
                        <td><?= $row['deskripsi'] ?></td>
                        <td>
                            <span class="badge <?= $row['status'] == 'Aktif' ? 'bg-success' : 'bg-danger' ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $row['id_kategori'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <button onclick="confirmDelete(<?= $row['id_kategori'] ?>)"
                                class="btn btn-danger btn-sm">Hapus</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Yakin hapus?')) {
                window.location = 'delete.php?id=' + id;
            }
        }
    </script>
</body>

</html>