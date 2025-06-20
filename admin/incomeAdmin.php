<?php
require '../auth.php';
requireLogin(1); 

include '../connection.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$whereClause = '';
if ($search !== '') {
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $whereClause = "WHERE month LIKE '%$safeSearch%'";
}

$query = "SELECT * FROM view_monthly_income $whereClause";
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ExploreJogja - Admin Dashboard</title>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
    <!-- Font -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: rgba(13, 110, 253);
            position: relative;
            transition: all 0.3s ease;
        }

        .logout a {
            color: white;
            display: block;
            padding: 1rem;
            text-decoration: none;
        }

        .menu-sidebar a {
            color: white;
            display: block;
            padding: 1rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .menu-sidebar a:hover {
            font-weight: 700;
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 0 30px 30px 0;
        }

        .close-btn {
            display: none;
            color: white;
            font-size: 1.5rem;
            text-align: right;
            padding: 1rem;
            cursor: pointer;
        }

        .card-header {
            background-color: rgba(207, 226, 255);
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 60vw;
                height: 100vh;
                z-index: 1000;
            }

            .sidebar.show {
                display: block;
            }

            .close-btn {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3 p-md-4 " id="sidebar">
            <div class="close-btn d-md-none" onclick="toggleSidebar()">&times;</div>
            <div class="d-flex flex-row gap-2 mb-4 my-md-4 me-md-2">
                <img src="../asetsImage/ExploreJogja.png" style="height: 25px; width: 25px;">
                <h4 class="text-white fw-bold">ExploreJogja</h4>
            </div>

            <div class="menu-sidebar">
                <a href="dashboardAdmin.php">Dashboard</a>
                <a href="detailOrdersAdmin.php">Orders</a>
                <a href="ticketsSaleAdmin.php">Tickets Sale</a>
                <a href="incomeAdmin.php" class="fw-bold">Income</a>
                <a href="destinationsAdmin.php">Destinations</a>
            </div>

            <div class="logout row mt-5 m-2">
                <div class="logout-btn btn btn-sm btn-danger">
                    <a href="../logout.php" class="m-0 p-0">Logout</a>
                </div>
            </div>


        </div>

        <!-- Content -->
        <div class="flex-grow-1 p-3 m-md-4 m-3">
            <button class="btn btn-primary d-md-none mb-3" onclick="toggleSidebar()">☰ Menu</button>



            <!-- Income per Bulan (Bar Chart Placeholder) -->
            <div class="row mb-4 gap-4 gap-md-0">
                <div class="col">
                    <div class="card mt-md-5 mb-3 mt-2">
                        <div class="card-header text-center fw-bold">Monthly Income</div>
                        <div class="tabel-contents card-body">
                            <form method="get" class="mb-2">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search For Income Month..." value="<?= htmlspecialchars($search) ?>">
                            </form>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Income</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($result) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['month']) ?></td>
                                                <td><?= htmlspecialchars($row['total_income']) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Data tidak ditemukan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- ChartJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }
    </script>
    <script>
        const input = document.querySelector('input[name="search"]');

        input.addEventListener('input', function () {
            const search = this.value.trim();
            if (search === '') {
                const url = new URL(window.location.href);
                url.searchParams.delete('search');
                window.location.href = url.pathname;
            }
        });

        if (performance.navigation.type === 1) {
            const url = new URL(window.location.href);
            if (url.searchParams.has('search')) {
                url.searchParams.delete('search');
                window.location.href = url.pathname;
            }
        }
    </script>

</body>

</html>
<?php $conn->close(); ?>