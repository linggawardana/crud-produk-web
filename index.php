<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Data Produk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
  <h2 class="mb-4">Data Produk</h2>
  <a href="tambah.php" class="btn btn-primary mb-3">+ Tambah Produk</a>

  <table class="table table-bordered table-striped">
    <tr>
      <th>No</th>
      <th>Nama Produk</th>
      <th>Harga</th>
      <th>Foto</th>
      <th>Aksi</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM produk ORDER BY no_produk DESC");
    $no = 1;
    while($row = $result->fetch_assoc()):
    ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['nama_produk']) ?></td>
      <td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
      <td>
        <?php if($row['foto_produk']): ?>
          <img src="<?= $row['foto_produk'] ?>" width="80">
        <?php endif; ?>
      </td>
      <td>
        <a href="edit.php?id=<?= $row['no_produk'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="hapus.php?id=<?= $row['no_produk'] ?>" onclick="return confirm('Hapus produk ini?')" class="btn btn-sm btn-danger">Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
