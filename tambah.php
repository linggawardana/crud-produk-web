<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Tambah Produk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
  <h2>Tambah Produk</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Harga</label>
      <input type="number" name="harga" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Foto</label>
      <input type="file" name="foto" class="form-control">
    </div>
    <button name="simpan" class="btn btn-success">Simpan</button>
    <a href="index.php" class="btn btn-secondary">Kembali</a>
  </form>

<?php
if(isset($_POST['simpan'])){
  $nama = $_POST['nama_produk'];
  $harga = $_POST['harga'];

  $fotoPath = null;
  if(!empty($_FILES['foto']['name'])){
    $targetDir = "uploads/";
    if(!is_dir($targetDir)) mkdir($targetDir);
    $fotoPath = $targetDir . time() . "_" . basename($_FILES["foto"]["name"]);
    move_uploaded_file($_FILES["foto"]["tmp_name"], $fotoPath);
  }

  $stmt = $conn->prepare("INSERT INTO produk (nama_produk,harga,foto_produk) VALUES (?,?,?)");
  $stmt->bind_param("sds",$nama,$harga,$fotoPath);
  $stmt->execute();

  echo "<script>alert('Produk berhasil ditambah');window.location='index.php'</script>";
}
?>
</body>
</html>
