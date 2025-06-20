<?php
require '../auth.php';
requireLogin(0); 
include '../connection.php';

$region = isset($_GET['region']) ? $_GET['region'] : 'all';

$limit = 12;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$countQuery = "SELECT COUNT(*) as total FROM destinations";
if ($region !== 'all') {
    $countQuery .= " WHERE region_code = '$region'";
}
$countResult = mysqli_query($conn, $countQuery);
$totalData = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalData / $limit);

$queryDestinations = "SELECT * FROM destinations";
if ($region !== 'all') {
    $queryDestinations .= " WHERE region_code = '$region'";
}
$queryDestinations .= " LIMIT $limit OFFSET $offset";
$resultDestinations = mysqli_query($conn, $queryDestinations);

$queryRegions = "SELECT * FROM regions";
$resultRegions = mysqli_query($conn, $queryRegions);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExploreJogja</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
    <!-- Font -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<style>
    .w-20px {
        width: 20px;
        height: 20px;
    }

    .btn-filter.active {
        background-color: #0d6efd;
        color: white;
        border: 2px solid #0a58ca;
    }
</style>

<body>
    <div>
        <!-- NAV -->
        <div class="item-container">
            <nav class="navbar navbar-expand-lg px-5 py-3 navbar-dark bg-transparent">
                <div class="nav-navbar mt-3 mb-5 mx-0 mx-sm-5 my-4 py-1 px-2 px-xl-4 py-xl-2">
                    <div class="nav-navbar-items">
                        <a class="navbar-brand d-flex column align-items-center fw-bold" href="#">
                            <img src="../asetsImage/ExploreJogja.png" alt="Logo" width="30" height="24"
                                class="d-inline-block align-text-top ms-2">
                            ExploreJogja
                        </a>
                    </div>

                    <!-- TOGGLE BTN -->
                    <button class="navbar-toggler shadow-none border-0 d-lg-none" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- SIDEBAR -->
                    <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                        aria-labelledby="offcanvasNavbarLabel">
                        <!-- SIDEBAR HEADER -->
                        <div class="offcanvas-header text-white border-bottom">
                            <h5 style="font-family:Raleway ;" class="offcanvas-title fw-bold" id="offcanvasNavbarLabel">
                                ExploreJogja</h5>
                            <button type="button" class="btn-close btn-close-white shadow-none"
                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <!-- SIDEBAR BODY -->
                        <div class="offcanvas-body d-flex flex-column p-4 p-lg-0 flex-lg-row">
                            <ul class="navbar-nav justify-content-center align-items-center flex-grow-1">
                                <li class="nav-item mx-2">
                                    <a class="nav-link text-white" href="../index.php">Home</a>
                                </li>
                                <li class="nav-item mx-2">
                                    <a class="nav-link text-white" href="../index.php#about">About</a>
                                </li>
                                <li class="nav-item mx-2">
                                    <a class="nav-link text-white" href="#">Destinations</a>
                                </li>
                                <li class="nav-item mx-2">
                                    <a class="nav-link text-white" href="../index.php#regions">Regions</a>
                                </li>

                            </ul>
                            <!-- LOGIN SIGN UP -->
                            <div
                                class="d-flex justify-content-center align-items-center flex-column flex-lg-row gap-3 me-2">
                                <a class="text-white text-decoration-none p-2" href="../logout.php">Logout</a>
                            </div>
                        </div>

                    </div>
                </div>
            </nav>

            <!-- TITLE -->
            <section class="title mt-5 pt-4">
                <div class="title-content">
                    <h1 class="display-5 fw-bold">Temukan Destinasi Favoritmu</h1>
                    <h3 class="fst-italic">Satu Tiket Menuju Petualangan!</h3>
                    <p class="mb-4">Dapatkan tiket wisata ke destinasi terbaik di Yogyakarta dengan praktis.</p>
                </div>
            </section>
            <div class="blur-overlay"></div>
        </div>

        <!-- CONTENT -->
        <div>
            <!-- KATALOG -->
            <section>
                <div class="container-katalog">
                    <div
                        class="container-katalog-items mx-5 px-lg-2 my-3 gap-sm-4 gap-lg-4 d-flex flex-column text-center text-lg-start">
                        <!-- SUB TITLE -->
                        <h2 class="fw-bold text-primary mb-4 mb-sm-1 text-start">Destinations</h2>
                        <div class="d-flex justify-content-end mb-3 mb-lg-0">
                            <div class="katalog-destination-all row col-xl-7 col-sm-12 col-md-10">

                                <div class="row row-cols-3 row-cols-sm-5 row-cols-md-5 g-2 g-sm-3 m-0"
                                    id="region-items">

                                    <?php while ($row = mysqli_fetch_assoc($resultRegions)): ?>
                                        <div class="col">
                                            <a href="?region=<?= $row['region_code'] ?>"
                                                class="btn btn-filter w-100 fw-semibold px-2 py-1 px-sm-1 <?= ($region === $row['region_code']) ? 'active btn-primary' : 'btn-outline-primary' ?>">
                                                <?= str_replace('Kabupaten ', '', str_replace('Kota ', '', $row['region_name'])) ?>
                                            </a>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>

                        </div>

                        <div class="katalog-card row py-lg-4">
                            <!-- CARD DESTINASI -->
                            <div class="row">
                                <?php if (mysqli_num_rows($resultDestinations) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($resultDestinations)): ?>
                                        <div class="container-card col-sm-3 col-6 p-0 d-flex justify-content-center">
                                            <div class="card p-0 m-2 m-lg-3">
                                                <img style="width: 100%; max-width: 308px; height: auto; max-height: 308px; object-fit: cover;" src="../asetsImage/<?php  echo htmlspecialchars( $row['image'])?>" class="card-img-top"
                                                    alt="<?= $row['destination_name'] ?>">
                                                <div
                                                    class="card-body d-flex flex-column gap-1 gap-lg-3 px-2 p-md-3 py-0 mb-1 mb-md-0">
                                                    <div class="detail-card d-flex flex-column gap-0 gap-lg-2">
                                                        <div class="d-flex flex-row justify-content-between align-items-center">
                                                            <h5 class="card-title fw-semibold m-0 p-0 text-start">
                                                                <?php  echo htmlspecialchars($row['destination_name'])?>
                                                            </h5>
                                                            <a href="<?php echo htmlspecialchars($row['address']); ?>"><img src="../asetsImage/Address.png" alt="Lokasi"></a>
                                                        </div>
                                                        <div class="d-flex align-items-center flex-row gap-2">
                                                            <p class="my-0"><?php  echo htmlspecialchars($row['rating'])?></p>
                                                            <img src="../asetsImage/Star.png" class="w-15px h-15px"
                                                                alt="Rating">
                                                        </div>
                                                    </div>
                                                    <!--BUTTON PRICE-->
                                                    <div class="detail-btn d-flex flex-row justify-content-between">
                                                        <?php
                                                        $isLoggedIn = isset($_SESSION['username']);
                                                        ?>
                                                        <a href="<?php echo $isLoggedIn ? '#' : '../login.php?msg=Silahkan_Login'; ?>"
                                                            class="btn btn-primary px-3 px-sm-2 px-lg-4 py-1 fw-semibold w-auto"
                                                            <?php if ($isLoggedIn): ?> data-bs-toggle="modal"
                                                                data-bs-target="#orderModal" onclick="orderModal(
                                                            '<?php echo htmlspecialchars($row['destination_name'], ENT_QUOTES); ?>',
                                                            '<?php echo htmlspecialchars($row['image'], ENT_QUOTES); ?>',
                                                            '<?php echo addslashes($row['description']); ?>',
                                                            '<?php echo $row['ticket_price']; ?>',
                                                            '<?php echo $row['destination_code']; ?>',
                                                            '<?php echo $row['stok']; ?>'
                                                    )" <?php endif; ?>>
                                                            <?php echo 'Rp' . number_format($row['ticket_price'], 0, ',', '.'); ?>
                                                        </a>
                                                        <p class="my-0 align-self-center">Stock: <?= $row['stok'] ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted">Tidak ada destinasi ditemukan untuk kategori ini.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- HALAMAN -->
                        <?php if ($totalData > 12): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center mt-4">

                                    <!-- TOMBOL PREVIOUS -->
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="?region=<?= $region ?>&page=<?= $page - 1 ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>

                                    <!-- NOMOR HALAMAN -->
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link" href="?region=<?= $region ?>&page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <!-- TOMBOL NEXT -->
                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?region=<?= $region ?>&page=<?= $page + 1 ?>">Next</a>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- MODAL ORDER -->
                <div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content p-3">
                            <div class="modal-body row">
                                <div class="col-md-5 text-center">
                                    <img id="modalImage" src="" class="img-fluid rounded mb-2">
                                    <p id="modalDescription"></p>

                                </div>
                                <div class="col-md-7">
                                    <h5 id="modalTitle" class="fw-bold"></h5>
                                    <p>Harga satuan: Rp<span id="unitPrice"></span></p>
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <button class="btn btn-outline-secondary"
                                            onclick="updateQuantity(-1)">-</button>
                                        <span id="quantity">1</span>
                                        <button class="btn btn-outline-secondary" onclick="updateQuantity(1)">+</button>
                                    </div>
                                    <hr>
                                    <h6 class="fw-bold">Order Summary</h6>
                                    <p>Items: <span id="totalItems">1</span></p>
                                    <p>Total: Rp<span id="totalPrice">0</span></p>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button class="btn btn-primary" onclick="checkout()">Checkout</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL INVOICE -->
                <div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center px-4 py-5">
                            <img src="../asetsImage/Audit.svg" width="50" class="mx-auto mb-3">
                            <h5 class="fw-bold">Thank You!</h5>
                            <p>Your order has been confirmed</p>
                            <p>Invoice: <strong id="invoiceNumber"></strong></p>
                            <div class="d-flex justify-content-center my-2">
                                <button class="btn btn-primary fw-semibold rounded-4 w-50" data-bs-dismiss="modal">
                                    Order More
                                </button>
                            </div>
                            <div class="mt-2">
                                <p class="fw-bold mb-1">Your Ticket:</p>
                                <p id="ticketCodes" class="mb-3 text-muted"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Toast Alert -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
            <div id="alertToast" class="toast align-items-center text-white bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body" id="alertMessage">
                        <!-- Pesan alert akan muncul di sini -->
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="d-flex align-items-end">
            <div class="this-footer">
                <div class="bg-primary text-white d-flex align-items-center flex-column p-3 p-sm-4 gap-2">
                    <!-- FOOTER BRAND -->
                    <div class="d-flex flex-row">
                        <img src="../asetsImage/ExploreJogja.png" style="width: 20px; height: 20px;">
                        <h6 class=" fw-semibold mx-1 my-0 p-0">ExploreJogja</h6>
                    </div>
                    <!-- FOOTER MEDSOS -->
                    <div class="d-flex flex-row gap-2">
                        <a href="https://www.instagram.com/ayuatikahhh?igsh=MWp2YmRxcmt1OW8wdg=="><img
                                src="../asetsImage/Instagram.png"></a>
                        <a href="https://www.instagram.com/ayuatikahhh?igsh=MWp2YmRxcmt1OW8wdg=="><img
                                src="../asetsImage/Facebook.png"></a>
                        <a href="https://www.youtube.com/@ugm.yogyakarta"><img src="../asetsImage/YouTube.png"></a>
                    </div>
                    <!-- FOOTER DETAILS -->
                    <div class="detail-footer d-flex align-items-center flex-column">
                        <div class="d-flex flex-row">
                            <p class="fw-light m-0 p-0">Contact Us: +62 812-888-5555 | explorejogja@ugm.ac.id</p>
                        </div>
                        <div class="d-flex flex-row gap-2 text-center">
                            <img class="Copyright" src="../asetsImage/Copyright All Rights Reserved.svg">
                            <p class="fw-light m-0 p-0">2025 ExploreJogja - All Rights Reserved. A Project of Ayu
                                Atikah, Universitas Gadjah Mada. </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script>
        let currentPrice = 0;
        let quantity = 1;
        let maxStock = 10;
        let selectedDestinationCode = '';

        function orderModal(name, img, desc, price, code, stock) {
            document.getElementById('modalImage').src = `../asetsImage/${img}`;
            document.getElementById('modalDescription').innerText = desc;
            document.getElementById('unitPrice').innerText = price;
            document.getElementById('modalTitle').innerText = name;

            currentPrice = parseInt(price);
            quantity = 1;
            maxStock = parseInt(stock);
            selectedDestinationCode = code;

            updateModal();
        }


        function updateQuantity(delta) {
            quantity += delta;
            if (quantity < 1) quantity = 1;
            if (quantity > maxStock) quantity = maxStock;
            updateModal();
        }

        function updateModal() {
            document.getElementById('quantity').innerText = quantity;
            document.getElementById('totalItems').innerText = quantity;
            document.getElementById('totalPrice').innerText = (quantity * currentPrice).toLocaleString('id-ID');
        }

        function checkout() {
            if (quantity > 5) {
                showAlert('Maksimal pembelian adalah 5 tiket!', 'danger');
                return;
            }

            fetch('order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    destination_code: selectedDestinationCode,
                    quantity: quantity
                })
            })
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not OK");
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            document.getElementById('invoiceNumber').textContent = data.invoice;
                            document.getElementById('ticketCodes').innerHTML = data.tickets.map(t => `<div>${t}</div>`).join('');
                            const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                            invoiceModal.show();
                            const orderModalEl = document.getElementById('orderModal');
                            const orderModal = bootstrap.Modal.getInstance(orderModalEl);
                            orderModal.hide();
                        } else {
                            alert("Gagal membuat pesanan: " + data.error);
                        }
                    } catch (e) {
                        console.error("Bukan JSON valid:", text);
                        alert("Kesalahan tidak terduga, lihat console.");
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                    alert("Terjadi kesalahan jaringan atau server.");
                });
        }


        function updateQuantity(delta) {
            quantity += delta;
            if (quantity < 1) quantity = 1;
            if (quantity > maxStock) quantity = Math.min(maxStock, 5);

            if (quantity > 5) {
                quantity = 5;
                showAlert("Maksimal pembelian adalah 5 tiket.", "warning");
            }

            updateModal();
        }

        function showAlert(message, type = 'success') {
            const toastEl = document.getElementById('alertToast');
            const messageBox = document.getElementById('alertMessage');
            toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
            messageBox.textContent = message;
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
        }

    </script>
</body>

</html>