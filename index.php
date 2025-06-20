<?php
// include '../check_auth.php';
session_start();
include 'connection.php';

$sql = "SELECT * FROM view_top4_destinations";
$result = $conn->query($sql);

$regions = "SELECT * FROM view_region_summary";
$resultRegions = $conn->query($regions);

function getTotalDestinations($conn, $region_name)
{
    $stmt = $conn->prepare("SELECT total_destinations FROM view_region_summary WHERE region_name = ?");
    $stmt->bind_param("s", $region_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = 0;
    if ($row = $result->fetch_assoc()) {
        $total = $row['total_destinations'];
    }
    return $total;
}

$kulonprogo = getTotalDestinations($conn, 'Kabupaten Kulon Progo');
$sleman = getTotalDestinations($conn, 'Kabupaten Sleman');
$bantul = getTotalDestinations($conn, 'Kabupaten Bantul');
$gunungKidul = getTotalDestinations($conn, 'Kabupaten Gunung Kidul');
$jogja = getTotalDestinations($conn, 'Kota Yogyakarta');


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
    <link href="user/style.css" rel="stylesheet">
</head>

<body>
    <div>
        <div class="item-container">
            <nav class="navbar navbar-expand-lg px-5 py-3 navbar-dark bg-transparent">
                <div class="nav-navbar mt-3 mb-5 mx-0 mx-sm-5 my-4 py-1 px-2 px-xl-4 py-xl-2">
                    <div class="nav-navbar-items">
                        <a class="navbar-brand d-flex column align-items-center fw-bold" href="#">
                            <img src="asetsImage/ExploreJogja.png" alt="Logo" width="30" height="24"
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
                                    <a class="nav-link text-white" href="#">Home</a>
                                </li>
                                <li class="nav-item mx-2">
                                    <a class="nav-link text-white" href="#about">About</a>
                                </li>
                                <li class="nav-item mx-2">
                                    <a class="nav-link text-white" href="user/destinations.php">Destinations</a>
                                </li>
                                <li class="nav-item mx-2">
                                    <a class="nav-link text-white" href="#regions">Regions</a>
                                </li>

                            </ul>
                            <div
                                class="d-flex justify-content-center align-items-center flex-column flex-lg-row gap-3 me-2">
                                <?php
                                if (isset($_SESSION['username'])) {
                                    echo '<a class="text-white text-decoration-none p-2" href="logout.php">Logout</a>';
                                } else {
                                    echo '<a class="text-white text-decoration-none p-2" href="register.php">Sign Up</a>';
                                    echo '<a class="text-white text-decoration-none p-2" href="login.php">Login</a>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- TITLE -->
            <section class="title mt-5">
                <div class="title-content">
                    <h1 class="display-5 fw-bold">Jelajahi Pesona Yogyakarta</h1>
                    <h3 class="fst-italic">Satu Tiket Menuju Petualangan!</h3>
                    <p class="mb-4">Dapatkan tiket wisata ke destinasi terbaik di Yogyakarta dengan praktis.</p>
                    <a href="user/destinations.php" class="btn btn-sm btn-primary btn-md-lg px-2 py-1 py-lg-2 px-lg-5">Pesan
                        Sekarang</a>
                </div>
            </section>
            <div class="blur-overlay"></div>
        </div>


        <!-- CONTENT -->
        <div>
            <!-- KATALOG POPULAR -->
            <section>
                <div class="container-katalog">
                    <div
                        class="container-katalog-items mx-5 px-lg-2 my-3 gap-sm-4 gap-lg-4 d-flex flex-column text-center text-lg-start">
                        <!-- SUB TITLE -->
                        <h2 class="fw-bold text-primary mb-4 mb-sm-1">Popular Destinations</h2>

                        <div class="katalog-card row py-lg-4">


                            <!-- CARD POPULAR -->
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <div class="container-card col-sm-3 col-6 p-0 d-flex justify-content-center">
                                    <div class="card p-0 m-2 m-lg-3">

                                        <img src="asetsImage/<?php echo htmlspecialchars($row['image']); ?>"
                                            class="card-img-top" alt="...">
                                        <div
                                            class="card-body d-flex flex-column gap-1 gap-lg-3 px-2 p-md-3 py-0 mb-1 mb-md-0">
                                            <div class="detail-card d-flex flex-column gap-0 gap-lg-2">
                                                <div class="d-flex flex-row justify-content-between align-items-center">
                                                    <h5 class="card-title fw-semibold m-0 p-0">
                                                        <?php echo htmlspecialchars($row['destination_name']); ?>
                                                    </h5>
                                                    <a href="<?php echo htmlspecialchars($row['address']); ?>"><img
                                                            src="asetsImage/Address.png"></a>
                                                </div>
                                                <div class="d-flex align-items-center flex-row gap-2">
                                                    <p class="my-0"><?php echo number_format($row['rating'], 1); ?></p>
                                                    <img src="asetsImage/Star.png" class="w-20px h-20px">
                                                </div>
                                            </div>
                                            <!--BUTTON PRICE-->
                                            <div class="detail-btn d-flex flex-row justify-content-between">
                                                <?php
                                                $isLoggedIn = isset($_SESSION['username']);
                                                ?>
                                                <a href="<?php echo $isLoggedIn ? '#' : 'login.php?msg=Silahkan_Login'; ?>"
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
                                                <p class="my-0 align-self-center">Stock: <?php echo $row['stok']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <!-- BUTTON SEE MORE -->
                        <div class="see-more d-flex justify-content-center ">
                            <a href="user/destinations.php"
                                class="btn fw-semibold text-primary px-3 px-lg-4 py-1 py-lg-2 fw-semibold">See
                                More
                                <img src="asetsImage/Advance.png" class=" h-20 w-20">
                            </a>
                        </div>

                        <!-- MODAL ORDER -->
                        <div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content p-3">
                                    <div class="modal-body row">
                                        <div class="col-md-5 text-center">
                                            <!--IMAGE IN MODAL-->
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
                                                <button class="btn btn-outline-secondary"
                                                    onclick="updateQuantity(1)">+</button>
                                            </div>
                                            <hr>
                                            <h6 class="fw-bold">Order Summary</h6>
                                            <p>Items: <span id="totalItems">1</span></p>
                                            <p>Total: Rp<span id="totalPrice">0</span></p>
                                            <div class="d-flex justify-content-between mt-3">
                                                <button class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
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
                                    <img src="asetsImage/Audit.svg" width="50" class="mx-auto mb-3">
                                    <h5 class="fw-bold">Thank You!</h5>
                                    <p>Your order has been confirmed</p>
                                    <p>Invoice: <strong id="invoiceNumber"></strong></p>
                                    <div class="d-flex justify-content-center my-2">
                                        <button class="btn btn-primary fw-semibold rounded-4 w-50"
                                            data-bs-dismiss="modal">
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
                    </div>

                </div>
            </section>

            <!-- KATEGORI REGIONS -->
            <section id="regions">
                <div class="container-katalog-items my-5 my-sm-5">
                    <!-- SUB TITLE -->
                    <div class="d-flex justify-content-center">
                        <h2 class="fw-bold text-primary mb-3 mb-sm-5">Discover Beauty in Every Corner!</h2>
                    </div>

                    <!-- CARD -->
                    <div class="container h-100%">
                        <div class="my-4 mx-3 mx-md-5 ps-5 pe-4 px-sm-0 row d-flex flex-lg-column gap-lg-3 justify-content-center align-items-center"
                            data-masonry='{"percentPosition": true }'>
                            <!-- CARD KETEGORI 1 -->
                            <div class="cardregions col-6 col-sm-2 p-0 mx-2 mx-xl-3 mb-2 mb-md-0 image-card-1">
                                <a href="user/destinations.php?region=3471#region-items" class="text-decoration-none">
                                    <div class="card rounded-4 position-relative overflow-hidden">
                                        <img src="asetsImage/Kota Yogyakarta.png" class="rounded-4 image-card-1"
                                            alt="...">
                                        <div
                                            class="card-items-text position-absolute ps-2 pt-3 p-lg-3 align-items-start text-white">
                                            <h5 class="d-flex align-self-start fw-semibold shadow-sm m-0 p-0">Jogja
                                            </h5>
                                            <p class=" fw-light shadow-sm p-0 "><?php echo $jogja ?> Destinations</p>
                                        </div>
                                    </div>
                                </a>
                            </div>


                            <!-- CARD KETEGORI 2 -->
                            <div class="cardregions col-6 col-sm-2 mx-2 mx-xl-3 p-0 mb-2 mb-md-0 image-card-2">
                                <a href="user/destinations.php?region=3404#region-items" class=" text-decoration-none">
                                    <div class="card rounded-4 position-relative overflow-hidden">
                                        <img src="asetsImage/Kota Yogyakarta.png" class=" rounded-4 image-card-2"
                                            alt="...">
                                        <div
                                            class="card-items-text position-absolute ps-2 pt-3 p-lg-3 align-items-start text-white">
                                            <h5 class=" d-flex align-self-start  fw-semibold shadow-sm m-0">Sleman
                                            </h5>
                                            <p class=" fw-light shadow-sm p-0"><?php echo $sleman ?> Destinations</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- CARD KETEGORI 3 -->
                            <div class="cardregions col-6 col-sm-2 p-0 mx-2 mx-xl-3 mb-2 mb-md-0 image-card-3">
                                <a href="user/destinations.php?region=3401#region-items" class="text-decoration-none">
                                    <div class="card rounded-4 position-relative overflow-hidden">
                                        <img src="asetsImage/Kota Yogyakarta.png" class="  rounded-4 image-card-3"
                                            alt="...">
                                        <div
                                            class="card-items-text position-absolute ps-2 pt-3 p-lg-3 align-items-start text-white">
                                            <h5 class=" d-flex align-self-start fw-semibold shadow-sm m-0">Kulon Progo
                                            </h5>
                                            <p class="fw-light shadow-sm p-0"><?php echo $kulonprogo ?> Destinations</p>
                                        </div>

                                    </div>
                                </a>
                            </div>

                            <!-- CARD KETEGORI 4 -->
                            <div class="cardregions col-6 col-sm-2 p-0 mx-2 mx-xl-3 mb-2 mb-md-0 image-card-4">
                                <a href="user/destinations.php?region=3403#region-items" class="text-decoration-none">
                                    <div class="card rounded-4 position-relative overflow-hidden ">
                                        <img src="asetsImage/Kota Yogyakarta.png" class=" rounded-4 image-card-4"
                                            alt="...">
                                        <div
                                            class="card-items-text position-absolute ps-2 pt-3 p-lg-3 align-items-start text-white">
                                            <h5 class=" d-flex align-self-start fw-semibold shadow-sm m-0">Gunung Kidul
                                            </h5>
                                            <p class="fw-light shadow-sm p-0"><?php echo $gunungKidul ?> Destinations
                                            </p>
                                        </div>

                                    </div>
                                </a>
                            </div>

                            <!-- CARD KETEGORI 5-->
                            <div class="cardregions col-6 col-sm-2 p-0 mx-2 mx-xl-3 mb-2 mb-md-0 image-card-5">
                                <a href="user/destinations.php?region=3402#region-items" class="text-decoration-none">
                                    <div class="card rounded-4 position-relative overflow-hidden">
                                        <img src="asetsImage/Kota Yogyakarta.png" class="rounded-4 image-card-5"
                                            alt="...">
                                        <div
                                            class="card-items-text position-absolute ps-2 pt-3 p-lg-3 align-items-start text-white">
                                            <h5 class="d-flex align-self-start fw-semibold shadow-sm m-0">Bantul
                                            </h5>
                                            <p class="fw-light shadow-sm"><?php echo $bantul ?> Destinations</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>

                    </div>
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
            </section>


            <!-- WHY YOU CHOOSE EXPLORE JOGJA -->
            <section id="about">
                <!-- SUB TITLE -->
                <div class="container-katalog-items m-sm-5 mt-5 d-flex flex-column">
                    <div>
                        <h2 class="fw-bold text-primary m-0 mt-lg-4 text-center">Why Choose ExploreJogja?</h2>
                    </div>

                    <!-- CARD -->
                    <div class="d-flex flex-lg-row flex-column justify-content-center px-5 px-lg-2 mx-3 my-4  ">
                        <div class="choose d-flex ps-1 ps-lg-2 py-1 py-lg-2 m-2 m-lg-2 shadow gap-lg-1 rounded-4">
                            <img class="m-1 img-fluid" src="asetsImage/image 6.png">
                            <div class="detail-brand d-flex flex-column gap-1  pt-1 pe-1">
                                <p class="fw-semibold mx-1 p-0 my-0">Easy and Convenient</p>
                                <p class="fw-light mx-1 p-0 my-0">Book your Jogja tickets in just a few clicks, fast and
                                    hassle-free!</p>
                            </div>
                        </div>
                        <div class="choose d-flex ps-1 ps-lg-2 py-1 py-lg-2 m-2 m-lg-2 shadow gap-lg-1 rounded-4">
                            <img class="m-1 img-fluid" src="asetsImage/image 8.png">
                            <div class="detail-brand d-flex flex-column gap-1 pt-1 pe-1">
                                <p class="fw-semibold mx-1 p-0 my-0">Best Prices</p>
                                <p class="fw-light mx-1 p-0 my-0">Enjoy great deals and affordable rates for every
                                    destination.</p>
                            </div>
                        </div>
                        <div class="choose d-flex ps-1 ps-lg-2 py-1 py-lg-2 m-2 m-lg-2 shadow gap-lg-1 rounded-4">
                            <img class="m-1 img-fluid" src="asetsImage/image 7.png">
                            <div class="detail-brand d-flex flex-column gap-1  pt-1 pe-1">
                                <p class="fw-semibold mx-1 p-0 my-0">Complete Travel Options</p>
                                <p class="fw-light mx-1 p-0 my-0">Book your Jogja tickets in just a few clicks, fast and
                                    hassle-free!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FOOTER -->
            <footer class="d-flex align-items-end">
                <div class="this-footer">
                    <div class="bg-primary text-white d-flex align-items-center flex-column p-3 p-sm-4 gap-2">
                        <!-- FOOTER BRAND -->
                        <div class="d-flex flex-row">
                            <img src="asetsImage/ExploreJogja.png" style="width: 20px; height: 20px;">
                            <h6 class=" fw-semibold mx-1 my-0 p-0">ExploreJogja</h6>
                        </div>
                        <!-- FOOTER MEDSOS -->
                        <div class="d-flex flex-row gap-2">
                            <a href="https://www.instagram.com/ayuatikahhh?igsh=MWp2YmRxcmt1OW8wdg=="><img src="asetsImage/Instagram.png"></a>
                            <a href="https://www.instagram.com/ayuatikahhh?igsh=MWp2YmRxcmt1OW8wdg=="><img src="asetsImage/Facebook.png"></a>
                            <a href="https://www.youtube.com/@ugm.yogyakarta"><img src="asetsImage/YouTube.png"></a>
                        </div>
                        <!-- FOOTER DETAILS -->
                        <div class="detail-footer d-flex align-items-center flex-column">
                            <div class="d-flex flex-row">
                                <p class="fw-light m-0 p-0">Contact Us: +62 812-888-5555 | explorejogja@ugm.ac.id</p>
                            </div>
                            <div class="d-flex flex-row gap-2 text-center">
                                <img class="Copyright" src="asetsImage/Copyright All Rights Reserved.svg">
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
            document.getElementById('modalImage').src = `asetsImage/${img}`;
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

            fetch('user/order.php', {
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
    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showAlert("<?= $_SESSION['alert']['message'] ?>", "<?= $_SESSION['alert']['type'] ?>");
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

</body>
</html>
<?php $conn->close(); ?>