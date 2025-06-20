<?php
require '../auth.php';
requireLogin(1);
include '../connection.php';

if (isset($_GET['kode'])) {
    $kode = $_GET['kode'];

    $cek = mysqli_query($conn, "SELECT image FROM destinations WHERE destination_code = '$kode'");
    $data = mysqli_fetch_assoc($cek);
    if ($data && file_exists("uploads/" . $data['image'])) {
        unlink("uploads/" . $data['image']);
    }

    $query = "DELETE FROM destinations WHERE destination_code = '$kode'";
    if (mysqli_query($conn, $query)) {
        header("Location: destinationsAdmin.php?success=Destinasi berhasil dihapus");
    } else {
        header("Location: destinationsAdmin.php?error=Gagal hapus: " . mysqli_error($conn));
    }
} else {
    header("Location: destinationsAdmin.php?error=Data tidak ditemukan");
}
?>
