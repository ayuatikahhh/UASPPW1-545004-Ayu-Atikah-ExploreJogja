<?php
session_start();

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'Silahkan_Login') {
        $_SESSION['alert'] = ['message' => 'Silahkan login dulu.', 'type' => 'warning'];
    } elseif ($_GET['msg'] == 'logout_success') {
        $_SESSION['alert'] = ['message' => 'Logout berhasil.', 'type' => 'success'];
    }
}


include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    if (empty($username) || empty($password)) {
        $_SESSION['alert'] = ['message' => 'Username dan password tidak boleh kosong.', 'type' => 'warning'];
        header('Location: login.php');
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['alert'] = ['message' => 'Password minimal 8 karakter', 'type' => 'warning'];
        header('Location: login.php');
        exit();
    }

    $stmt = $conn->prepare("SELECT id_user, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id_user, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {

            $_SESSION['id_user'] = $id_user;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['alert'] = ['message' => 'Login berhasil', 'type' => 'success'];

            if ($role == 1) {
                header('Location: admin/dashboardAdmin.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $_SESSION['alert'] = ['message' => 'Password salah', 'type' => 'danger'];
        }
    } else {
        $_SESSION['alert'] = ['message' => 'Username tidak ditemukan.', 'type' => 'danger'];
    }

    header("Location: login.php");
    exit();
}
$conn->close();
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="user/style.css" rel="stylesheet">
</head>

<body class="overflow-hidden">
    <div class="item-container" style="height: 100vh;">
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
                <button class="navbar-toggler shadow-none border-0 d-lg-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- SIDEBAR -->
                <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <!-- SIDEBAR HEADER -->
                    <div class="offcanvas-header text-white border-bottom">
                        <h5 style="font-family:Raleway ;" class="offcanvas-title fw-bold" id="offcanvasNavbarLabel">
                            ExploreJogja</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <!-- SIDEBAR BODY -->
                    <div class="offcanvas-body d-flex flex-column p-4 p-lg-0 flex-lg-row">
                        <ul class="navbar-nav justify-content-center align-items-center flex-grow-1">
                            <li class="nav-item mx-2">
                                <a class="nav-link text-white" href="index.php">Home</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link text-white" href="index.php#about">About</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link text-white" href="user/destinations.php">Destinations</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link text-white" href="index.php#regions">Regions</a>
                            </li>
                        </ul>
                        <!-- LOGIN SIGN UP -->
                        <div
                            class="d-flex justify-content-center align-items-center flex-column flex-lg-row gap-1 me-2">
                            <a class="text-white text-decoration-none p-2" href="register.php">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <section class="d-flex justify-content-center align-items-center min-vh-70">
            <div class="container container-main mx-5 mx-lg-0 px-5 px-lg-0">
                <div class="container-main-items row justify-content-center align-items-center">
                    <!-- TITLE -->
                    <div
                        class="title-in-login col-12 col-md-6 col-lg-5 mb-md-0 d-flex flex-column justify-content-center align-items-start text-white">
                        <h2 class="fw-bold">Welcome Back!</h2>
                        <p>Sign in to continue your adventure in Yogyakarta!</p>
                    </div>

                    <!-- CARD FROM -->
                    <div class="card-form-items col-12 col-md-6 col-lg-5 p-2 p-sm-5 p-md-4 p-lg-5">
                        <div class="d-flex flex-column row gap-4 m-3 align-items-center text-white">
                            <!-- Text -->
                            <div class="text-center">
                                <p class="m-0 fs-5 fw-semibold">Sign In</p>
                            </div>

                            <!-- FORM -->
                            <form method="POST" action="login.php" class="d-flex flex-column gap-4">
                                <div class="d-flex flex-column gap-3 card-from-input">
                                    <div>
                                        <p class="m-0">Username</p>
                                        <input type="text" id="username" name="username" class="form-control"
                                            placeholder="Enter your username">
                                    </div>
                                    <div>
                                        <p class="m-0 p-0">Password</p>
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="********">
                                    </div>
                                </div>
                                <!-- BUTTON -->
                                <div class="btn-login d-flex flex-column gap-3">
                                    <div>
                                        <button type="sumbit"
                                            class="text-white fw-semibold btn w-100 bg-primary">LOGIN</button>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <p class="m-0 pe-1">Have an account?</p>
                                        <small class="align-items-center d-flex"><a class="m-0 p-0"
                                                href="register.php">Sign Up Now!
                                            </a></small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="alertToast" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="alertMessage">
                    <!-- Message will be injected here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script>
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
    <?php unset($_SESSION['alert']); endif; ?>

</body>
</html>