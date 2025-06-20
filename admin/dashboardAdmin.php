<?php

require '../auth.php';
requireLogin(1); 

include '../connection.php';



$queryCountUser = "SELECT COUNT(*) AS total FROM users WHERE role = 0";
$resultCountUser = mysqli_query($conn, $queryCountUser);
$rowTotalUser = mysqli_fetch_assoc($resultCountUser);
$totalUser = $rowTotalUser['total'];

$queryCountIncome = "SELECT SUM(total_price) as total FROM orders";
$resultTotalIncome = mysqli_query($conn, $queryCountIncome);
$rowTotalIncome = mysqli_fetch_assoc($resultTotalIncome);
$totalIncome = $rowTotalIncome['total'];

$queryCountDestinations = "SELECT COUNT(*) AS total FROM destinations";
$resultCountDestinations = mysqli_query($conn, $queryCountDestinations);
$rowTotalDestinations = mysqli_fetch_assoc($resultCountDestinations);
$totalDestinations = $rowTotalDestinations['total'];

$queryCountTickets = "SELECT COUNT(*) AS total FROM tickets";
$resultCountTickets = mysqli_query($conn, $queryCountTickets);
$rowTotalTickets = mysqli_fetch_assoc($resultCountTickets);
$totalTickets = $rowTotalTickets['total'];

$queryGrafikIncome = "SELECT * FROM view_monthly_income";
$resultGrafikIncome = mysqli_query($conn, $queryGrafikIncome);

$labels = [];
$data = [];

while ($rowGrafikIncome = mysqli_fetch_assoc($resultGrafikIncome)) {
    $labels[] = $rowGrafikIncome['month'];

   
    $clean = str_replace(['Rp ', '.'], '', $rowGrafikIncome['total_income']);
    $data[] = (int) $clean;
}


$labels_json = json_encode($labels);
$data_json = json_encode($data);

$queryTopDestinations = "SELECT destination_name, stok, rating FROM view_top4_destinations";
$resultTopDestinations = mysqli_query($conn, $queryTopDestinations);

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
                <a href="dashboardAdmin.php" class="fw-bold">Dashboard</a>
                <a href="detailOrdersAdmin.php">Orders</a>
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

            <!-- Summary Cards -->
            <div class="row text-white mt-md-5 mb-3 mt-2">
                <div class="col-md-3 col-6 mb-3">
                    <div class="rounded-2 p-0 border-0" style="background-color: rgba(13, 110, 253);">
                        <div class="ms-2 m-0 rounded-2" style="background-color: rgba(255, 255, 255, 0.4);">
                            <div class="text-white fw-4 text-center py-2">
                                <p class="m-0 p-0 fw-semibold">Users</p>
                                <p class="m-0 p-0"><?php echo $totalUser; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6 mb-3">
                    <div class="rounded-2 p-0 border-0" style="background-color: rgb(220, 53, 99)">
                        <div class="ms-2 m-0 rounded-2" style="background-color: rgba(255, 255, 255, 0.4);">
                            <div class="text-white fw-4 text-center py-2">
                                <p class="m-0 p-0 fw-semibold">Tickets Sale</p>
                                <p class="m-0 p-0"><?php echo $totalTickets; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6 mb-3">
                    <div class="rounded-2 p-0 border-0" style="background-color: rgb(67, 212, 0)">
                        <div class="ms-2 m-0 rounded-2" style="background-color: rgba(255, 255, 255, 0.4);">
                            <div class="text-white fw-4 text-center py-2">
                                <p class="m-0 p-0 fw-semibold">Total Income</p>
                                <p class="m-0 p-0"><?php echo "Rp " . number_format($totalIncome, 0, ',', '.'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6 mb-3">
                    <div class="rounded-2 p-0 border-0" style="background-color: rgb(255, 193, 7);">
                        <div class="ms-2 m-0 rounded-2" style="background-color: rgba(255, 255, 255, 0.4);">
                            <div class="text-white fw-4 text-center py-2">
                                <p class="m-0 p-0 fw-semibold">Total Destinations</p>
                                <p class="m-0 p-0"><?php echo $totalDestinations ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income per Bulan (Bar Chart Placeholder) -->
            <div class="row mb-4 gap-4 gap-md-0">
                <div class="col-md-6 align-items-stretch">
                    <div class="card mb-4 h-100">
                        <div class="card-header text-center fw-bold">Income</div>
                        <div class="card-body">
                            <canvas id="incomeChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4 h-100">
                        <div class="card-header text-center fw-bold">Destination Favorite</div>
                        <div class="tabel-contents card-body">

                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Destinations Name</th>
                                        <th>Stock</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    while ($row = mysqli_fetch_assoc($resultTopDestinations)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['destination_name']) ?></td>
                                            <td><?= $row['stok'] ?></td>
                                            <td><?= $row['rating'] ?></td>
                                        </tr>
                                    <?php endwhile; ?>
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
        const ctx = document.getElementById('incomeChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $labels_json; ?>,
                datasets: [{
                    label: 'Income (Rp)',
                    data: <?php echo $data_json; ?>,
                    backgroundColor: '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }



    </script>


</body>

</html>

<?php $conn->close(); ?>