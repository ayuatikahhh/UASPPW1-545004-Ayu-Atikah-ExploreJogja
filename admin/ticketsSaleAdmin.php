<?php
require '../auth.php';
requireLogin(1); 

include '../connection.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = '';
if ($search !== '') {
    $search = mysqli_real_escape_string($conn, $search);
    $whereClause = "WHERE ticket_code LIKE '%$search%'";
}

$totalQuery = "SELECT COUNT(*) as total FROM view_tickets_sold $whereClause";
$totalResult = mysqli_query($conn, $totalQuery);
$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);

$query = "SELECT * FROM view_tickets_sold $whereClause ORDER BY order_date DESC, ticket_code DESC LIMIT $limit OFFSET $offset";
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
                <a href="ticketsSaleAdmin.php" class="fw-bold">Tickets Sale</a>
                <a href="incomeAdmin.php">Income</a>
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

            <!-- Tiket Terjual -->
            <div class="card mt-md-5 mb-3 mt-2">
                <div class="card-header text-center fw-bold">Tiket Terjual</div>
                <div class="card-body">
                    <form method="get" class="mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Ticket Code..."
                            value="<?= htmlspecialchars($search) ?>">
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Tiket</th>
                                <th>Destinasi</th>
                                <th>User</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['ticket_code']) ?></td>
                                        <td><?= htmlspecialchars($row['destination_name']) ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td><?= $row['order_date'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <nav>
                        <ul class="pagination justify-content-center">

                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <!-- Nomor Halaman -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </nav>



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
            if (this.value.trim() === '') {
                const url = new URL(window.location.href);
                url.searchParams.delete('search');
                url.searchParams.delete('page');
                window.location.href = url.pathname;
            }
        });

        if (performance.navigation.type === 1) {
            const url = new URL(window.location.href);
            if (url.searchParams.has('search') || url.searchParams.has('page')) {
                url.searchParams.delete('search');
                url.searchParams.delete('page');
                window.location.href = url.pathname;
            }
        }
    </script>


</body>

</html>

<?php $conn->close(); ?>