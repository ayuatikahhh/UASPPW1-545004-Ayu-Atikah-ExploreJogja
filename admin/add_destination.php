<?php
include 'auth_admin.php';
require '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $region = $_POST['regionDestinasi'];

    $regionPrefixes = [
        '3471' => 'KYK',
        '3403' => 'KGK',
        '3404' => 'KS',
        '3402' => 'KB',
        '3401' => 'KKP',
    ];

    if (!isset($regionPrefixes[$region])) {
        header("Location: destinationsAdmin.php?error=Kode region tidak dikenal");
        exit;
    }

    $prefix = $regionPrefixes[$region];
    $queryKode = "SELECT COUNT(*) as total FROM destinations WHERE region_code = '$region'";
    $resultKode = mysqli_query($conn, $queryKode);
    $dataKode = mysqli_fetch_assoc($resultKode);
    $jumlah = (int) $dataKode['total'] + 1;
    $destination_code = $prefix . str_pad($jumlah, 3, '0', STR_PAD_LEFT);

    $nama = mysqli_real_escape_string($conn, $_POST['namaDestinasi']);
    $harga = (int) $_POST['hargaDestinasi'];
    $stok = (int) $_POST['stokDestinasi'];
    $rating = (float) $_POST['ratingDestinasi'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['description']);
    $alamat = mysqli_real_escape_string($conn, $_POST['address']);

    // Validasi deskripsi maksimal 20 kata
    if (str_word_count($deskripsi) > 20) {
        header("Location: destinationsAdmin.php?error=Deskripsi maksimal 20 kata");
        exit;
    }

    if (isset($_FILES['gambarDestinasi']) && $_FILES['gambarDestinasi']['error'] === UPLOAD_ERR_OK) {
        $gambarName = $_FILES['gambarDestinasi']['name'];
        $gambarTmp = $_FILES['gambarDestinasi']['tmp_name'];
        $gambarSize = $_FILES['gambarDestinasi']['size'];
        $ext = strtolower(pathinfo($gambarName, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png'];
        if (!in_array($ext, $allowed)) {
            header("Location: destinationsAdmin.php?error=Format gambar tidak valid (jpg/jpeg/png)");
            exit;
        }

        if ($gambarSize > 2 * 1024 * 1024) {
            header("Location: destinationsAdmin.php?error=Ukuran gambar terlalu besar");
            exit;
        }

        // Resize & crop ke 308x308
        list($width, $height) = getimagesize($gambarTmp);
        $src = null;

        if ($ext === 'jpg' || $ext === 'jpeg') {
            $src = imagecreatefromjpeg($gambarTmp);
        } elseif ($ext === 'png') {
            $src = imagecreatefrompng($gambarTmp);
        }

        if (!$src) {
            header("Location: destinationsAdmin.php?error=Gagal memproses gambar");
            exit;
        }

        // Buat gambar baru dengan ukuran 308x308
        $cropSize = min($width, $height);
        $cropX = ($width - $cropSize) / 2;
        $cropY = ($height - $cropSize) / 2;

        $cropped = imagecrop($src, ['x' => $cropX, 'y' => $cropY, 'width' => $cropSize, 'height' => $cropSize]);
        $resized = imagecreatetruecolor(308, 308);
        imagecopyresampled($resized, $cropped, 0, 0, 0, 0, 308, 308, $cropSize, $cropSize);

        $newName = uniqid('img_') . '.' . $ext;
        $target = '../asetsImage/' . $newName;

        if ($ext === 'jpg' || $ext === 'jpeg') {
            imagejpeg($resized, $target);
        } else {
            imagepng($resized, $target);
        }

        imagedestroy($src);
        imagedestroy($cropped);
        imagedestroy($resized);

        // Simpan ke database
        $query = "INSERT INTO destinations 
            (destination_code, destination_name, ticket_price, stok, rating, region_code, image, description, address) 
            VALUES 
            ('$destination_code', '$nama', $harga, $stok, $rating, '$region', '$newName', '$deskripsi', '$alamat')";

        if (mysqli_query($conn, $query)) {
            header("Location: destinationsAdmin.php?success=Destinasi berhasil ditambahkan");
            exit;
        } else {
            header("Location: destinationsAdmin.php?error=Gagal menyimpan data: " . mysqli_error($conn));
            exit;
        }
    } else {
        header("Location: destinationsAdmin.php?error=Gambar wajib diupload");
        exit;
    }
} else {
    header("Location: destinationsAdmin.php?error=Invalid request");
    exit;
}
?>
