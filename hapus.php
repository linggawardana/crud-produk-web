<?php
include 'koneksi.php';
$id = $_GET['id'];

$q = $conn->query("SELECT foto_produk FROM produk WHERE no_produk=$id");
if($q && $r = $q->fetch_assoc()){
  if($r['foto_produk'] && file_exists($r['foto_produk'])) unlink($r['foto_produk']);
}
$conn->query("DELETE FROM produk WHERE no_produk=$id");
header("Location: index.php");
?>
