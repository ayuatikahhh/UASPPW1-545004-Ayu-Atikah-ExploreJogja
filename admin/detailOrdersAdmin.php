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
    $whereClause = "WHERE invoice_number LIKE '%$search%' OR order_date LIKE '%$search%'";
}


$totalQuery = "SELECT COUNT(*) as total FROM view_order_summary $whereClause";
$totalResult = mysqli_query($conn, $totalQuery);
$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);

$query = "SELECT * FROM view_order_summary $whereClause ORDER BY order_date DESC, invoice_number DESC LIMIT $limit OFFSET $offset";
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
                <a href="detailOrdersAdmin.php" class="fw-bold">Orders</a>
                <a href="ticketsSaleAdmin.php">Tickets Sale</a>
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
            <!-- Detail Orders -->
            <div class="card mt-md-4 mb-3 mt-2">
                <div class="card-header text-center fw-bold">Detail Orders</div>
                <div class="card-body">
                    <form method="get" class="mb-3">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search Invoice Number atau Tanggal (dd-mm-yyyy)..."
                            value="<?= htmlspecialchars($search) ?>">

                    </form>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Invoice</th>
                                <th>User</th>
                                <th>Destination</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['invoice_number']) ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td><?= htmlspecialchars($row['destination_name']) ?></td>
                                        <td><?= $row['ticket_quantity'] ?></td>
                                        <td><?= 'Rp' . number_format($row['total_price'], 0, ',', '.') ?></td>
                                        <td><?= $row['order_date'] ?></td>
                                    </tr>
                                    <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada data ditemukan.</td>
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


            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                function toggleSidebar() {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.toggle('show');
                }


            </script>
            <script>
                const input = document.querySelector('input[name="search"]');

                // Hapus filter saat input dikosongkan manual
                input.addEventListener('input', function () {
                    const search = this.value.trim();
                    if (search === '') {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('search');
                        url.searchParams.delete('page');
                        window.location.href = url.pathname; // reload halaman tanpa query
                    }
                });

                // Hapus filter saat halaman di-refresh
                if (performance.navigation.type === 1) {
                    const url = new URL(window.location.href);
                    if (url.searchParams.has('search')) {
                        url.searchParams.delete('search');
                        url.searchParams.delete('page');
                        window.location.href = url.pathname;
                    }
                }
            </script>

</body>

</html>

<?php $conn->close(); ?>