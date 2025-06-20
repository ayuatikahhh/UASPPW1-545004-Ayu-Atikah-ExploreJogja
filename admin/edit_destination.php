<?php
require '../auth.php';
requireLogin(1); 

include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode = $_POST['destination_code'];
    $nama = mysqli_real_escape_string($conn, $_POST['destination_name']);
    $stok = (int) $_POST['stok'];
    $price = (int) $_POST['price'];
    $rating = (float) $_POST['rating'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['description']);
    $alamat = mysqli_real_escape_string($conn, $_POST['address']);

    if (str_word_count($deskripsi) > 20) {
        header("Location: destinationsAdmin.php?error=Deskripsi maksimal 20 kata");
        exit;
    }

    $gambarLama = $_POST['old_image'];
    $gambarBaru = $gambarLama;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $gambarName = $_FILES['foto']['name'];
        $gambarTmp = $_FILES['foto']['tmp_name'];
        $gambarSize = $_FILES['foto']['size'];
        $ext = strtolower(pathinfo($gambarName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            header("Location: destinationsAdmin.php?error=Format gambar tidak valid");
            exit;
        }

        if ($gambarSize > 2 * 1024 * 1024) {
            header("Location: destinationsAdmin.php?error=Ukuran gambar terlalu besar");
            exit;
        }

        $gambarBaru = uniqid('img_') . '.' . $ext;
        $targetPath = "../asetsImage/" . $gambarBaru;

        // Resize & crop to 308x308 using GD
        list($width, $height) = getimagesize($gambarTmp);
        $srcAspect = $width / $height;
        $targetSize = 308;

        $src = null;
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $src = imagecreatefromjpeg($gambarTmp);
        } else {
            $src = imagecreatefrompng($gambarTmp);
        }

        $cropSize = min($width, $height);
        $srcX = ($width - $cropSize) / 2;
        $srcY = ($height - $cropSize) / 2;

        $resized = imagecreatetruecolor($targetSize, $targetSize);
        imagecopyresampled($resized, $src, 0, 0, $srcX, $srcY, $targetSize, $targetSize, $cropSize, $cropSize);

        if ($ext === 'jpg' || $ext === 'jpeg') {
            imagejpeg($resized, $targetPath, 90);
        } else {
            imagepng($resized, $targetPath);
        }

        imagedestroy($src);
        imagedestroy($resized);
    }

    $query = "UPDATE destinations 
              SET destination_name='$nama', stok=$stok, ticket_price=$price, rating=$rating,
                  description='$deskripsi', address='$alamat', image='$gambarBaru' 
              WHERE destination_code='$kode'";

    if (mysqli_query($conn, $query)) {
        header("Location: destinationsAdmin.php?success=Berhasil update destinasi");
    } else {
        header("Location: destinationsAdmin.php?error=Gagal update: " . mysqli_error($conn));
    }
} else {
    header("Location: destinationsAdmin.php?error=Invalid request");
}
?>
