<?php
// --- Tampilkan error supaya mudah debug ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Set output jadi JSON ---
header('Content-Type: application/json; charset=utf-8');

// --- Koneksi ke database ---
include 'koneksi.php';

// --- Cek koneksi ---
if ($conn->connect_error) {
    echo json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]);
    exit;
}

// --- Ambil aksi dari parameter ---
$action = $_GET['action'] ?? $_POST['action'] ?? 'get';

// ---- GET DATA ----
if ($action === 'get') {
    $data = [];
    $q = $conn->query("SELECT * FROM produk ORDER BY no_produk DESC");

    if ($q) {
        while ($r = $q->fetch_assoc()) {
            $data[] = $r;
        }
        echo json_encode($data);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
    exit;
}

// ---- TAMBAH DATA ----
if ($action === 'tambah') {
    $nama = $_POST['nama_produk'] ?? '';
    $harga = $_POST['harga'] ?? 0;
    $foto_base64 = $_POST['foto_base64'] ?? '';

    $foto_path = null;
    if (!empty($foto_base64)) {
        $data = explode(',', $foto_base64);
        $img = base64_decode(end($data));
        $filename = 'uploads/' . time() . rand(1000, 9999) . '.jpg';
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        file_put_contents($filename, $img);
        $foto_path = $filename;
    }

    $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, foto_produk) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $nama, $harga, $foto_path);
    $ok = $stmt->execute();

    echo json_encode([
        "success" => $ok,
        "message" => $ok ? "Produk berhasil ditambah" : "Gagal menambah produk",
        "error"   => $ok ? null : $stmt->error
    ]);
    exit;
}

// ---- UPDATE DATA ----
if ($action === 'update') {
    $no = $_POST['no_produk'] ?? 0;
    $nama = $_POST['nama_produk'] ?? '';
    $harga = $_POST['harga'] ?? 0;
    $foto_base64 = $_POST['foto_base64'] ?? '';

    $foto_path_sql = '';
    if (!empty($foto_base64)) {
        $data = explode(',', $foto_base64);
        $img = base64_decode(end($data));
        $filename = 'uploads/' . time() . rand(1000, 9999) . '.jpg';
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        file_put_contents($filename, $img);
        $foto_path_sql = ", foto_produk='" . $conn->real_escape_string($filename) . "'";
    }

    $sql = "UPDATE produk SET nama_produk='$nama', harga=$harga $foto_path_sql WHERE no_produk=$no";
    $ok = $conn->query($sql);

    echo json_encode([
        "success" => $ok,
        "message" => $ok ? "Produk diupdate" : "Gagal update",
        "error"   => $ok ? null : $conn->error
    ]);
    exit;
}

// ---- HAPUS DATA ----
if ($action === 'hapus') {
    $no = $_POST['no_produk'] ?? 0;
    $q = $conn->query("SELECT foto_produk FROM produk WHERE no_produk=$no");
    if ($q && $r = $q->fetch_assoc()) {
        if (!empty($r['foto_produk']) && file_exists($r['foto_produk'])) {
            unlink($r['foto_produk']);
        }
    }
    $ok = $conn->query("DELETE FROM produk WHERE no_produk=$no");
    echo json_encode([
        "success" => $ok,
        "message" => $ok ? "Produk dihapus" : "Gagal hapus",
        "error"   => $ok ? null : $conn->error
    ]);
    exit;
}

// ---- JIKA ACTION TIDAK DIKENAL ----
echo json_encode(["error" => "Action tidak dikenal atau kosong."]);
?>
