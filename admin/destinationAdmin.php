<?php
require '../auth.php';
requireLogin(1);

include '../connection.php';

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$whereClause = '';

if (!empty($search)) {
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $whereClause = "WHERE destination_name LIKE '%$safeSearch%' OR region_name LIKE '%$safeSearch%'";
} else {
    $whereClause = '';
}

$countQuery = "SELECT COUNT(*) as total FROM view_destinations_overview $whereClause";
$countResult = mysqli_query($conn, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);


$queryDestinations = "SELECT * FROM view_destinations_overview $whereClause LIMIT $limit OFFSET $offset";
$resultDestinations = mysqli_query($conn, $queryDestinations);


?>


<!DOCTYPE html>
<html lang="en">

<head>

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
                <a href="incomeAdmin.php">Income</a>
                <a href="destinationsAdmin.php" class="fw-bold">Destinations</a>
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

            <!-- Destinasi Section -->
            <div class="card mt-md-4 mb-3 mt-2">
                <div class="card-header d-flex justify-content-between">
                    <span class="fw-bold">Destinations</span>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#add">Tambah</button>
                </div>
                <div class="card-body">
                    <form method="GET" id="searchForm">
                        <input type="text" class="form-control mb-2" name="search" id="searchInput"
                            placeholder="Search Destination..."
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Destinations</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Region</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $no = ($page - 1) * $limit + 1;
                            while ($row = mysqli_fetch_assoc($resultDestinations)): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['destination_name']) ?></td>
                                    <td>Rp<?= number_format($row['ticket_price'], 0, ',', '.') ?></td>
                                    <td><?= $row['stok'] ?></td>
                                    <td><?= $row['region_name'] ?></td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal"
                                            data-bs-target="#editDestinasi<?= $row['destination_code'] ?>">
                                            ✏️ Edit
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <a href="delete_destination.php?kode=<?= $row['destination_code'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus destinasi ini?');">
                                            🗑️ Hapus
                                        </a>
                                    </td>

                                </tr>

                                <!-- Modal Edit (Unik per destinasi) -->
                                <div class="modal fade" id="editDestinasi<?= $row['destination_code'] ?>" tabindex="-1"
                                    aria-labelledby="editDestinasiLabel<?= $row['destination_code'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="edit_destination.php" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="editDestinasiLabel<?= $row['destination_code'] ?>">Edit
                                                        Destinasi</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="destination_code"
                                                        value="<?= $row['destination_code'] ?>">


                                                    <div class="mb-3">
                                                        <label for="editNama<?= $row['destination_code'] ?>"
                                                            class="form-label">Nama Destinasi</label>
                                                        <input type="text" class="form-control"
                                                            id="editNama<?= $row['destination_code'] ?>"
                                                            name="destination_name"
                                                            value="<?= htmlspecialchars($row['destination_name']) ?>"
                                                            required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="editStok<?= $row['destination_code'] ?>"
                                                            class="form-label">Stok Tiket</label>
                                                        <input type="number" class="form-control"
                                                            id="editStok<?= $row['destination_code'] ?>" name="stok"
                                                            value="<?= $row['stok'] ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="editPrice<?= $row['destination_code'] ?>"
                                                            class="form-label">Price</label>
                                                        <input type="number" class="form-control"
                                                            id="editPrice<?= $row['destination_code'] ?>" name="price"
                                                            value="<?= $row['ticket_price'] ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="editRating<?= $row['destination_code'] ?>"
                                                            class="form-label">Rating</label>
                                                        <input type="number" step="0.1" min="0" max="5" class="form-control"
                                                            id="editRating<?= $row['destination_code'] ?>" name="rating"
                                                            value="<?= $row['rating'] ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="file-input-wrapper">

                                                            <label for="foto<?= $row['destination_code'] ?>"
                                                                class="file-input-label">📁 Upload foto baru</label><br>
                                                            <input type="file" name="foto"
                                                                id="foto<?= $row['destination_code'] ?>" class="file-input"
                                                                accept="image/png,image/jpeg">
                                                            <small style="display: block; margin-top: 5px; color: #6c757d;">
                                                                Format: JPG, JPEG, PNG
                                                            </small>
                                                            <input type="hidden" name="old_image"
                                                                value="<?= $row['image'] ?>">
                                                        </div>

                                                        <!-- Tampilkan preview gambar lama -->
                                                        <div class="preview-container text-center my-3"
                                                            id="previewLama<?= $row['destination_code'] ?>">
                                                            <img src="../asetsImage/<?= $row['image'] ?>" alt="Gambar Lama"
                                                                class="img-fluid rounded shadow"
                                                                style="max-height: 150px; object-fit: cover;">
                                                            <p class="text-muted mt-2">Foto saat ini</p>
                                                        </div>


                                                        <!-- Preview gambar baru -->
                                                        <div class="preview-container text-center my-3"
                                                            id="previewBaru<?= $row['destination_code'] ?>"
                                                            style="display: none;">
                                                        </div>
                                                    </div>

                                                    <!--DESKRIPSI EDIT-->
                                                    <div class="mb-3">
                                                        <label for="editDeskripsi<?= $row['destination_code'] ?>"
                                                            class="form-label">Deskripsi</label>
                                                        <textarea class="form-control"
                                                            id="editDeskripsi<?= $row['destination_code'] ?>"
                                                            name="description" rows="3" required><?= htmlspecialchars($row['description']) ?>
                                                                                                        </textarea>
                                                        <div id="deskripsiWarning<?= $row['destination_code'] ?>"
                                                            style="display: none; color:red;">Maksimal 20 kata.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="editLinkMaps<?= $row['destination_code'] ?>"
                                                            class="form-label">Link Google Maps</label>
                                                        <input type="url" class="form-control"
                                                            id="editLinkMaps<?= $row['destination_code'] ?>" name="address"
                                                            value="<?= htmlspecialchars($row['address']) ?>" required>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">📋Simpan
                                                        Perubahan</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">❌Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>">Sebelumnya</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>">Berikutnya</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>



            <!--MODAL TAMBAH-->
            <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="formTambahDestinasi" action="add_destination.php" method="POST"
                            enctype="multipart/form-data">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Destinasi</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <!-- ALERT AREA
                                <?php if (isset($_GET['error'])): ?>
                                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                                <?php endif; ?>
                                <?php if (isset($_GET['success'])): ?>
                                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                                <?php endif; ?> -->

                                <div class="mb-3">
                                    <label for="namaDestinasi" class="form-label">Nama Destinasi</label>
                                    <input type="text" class="form-control" id="namaDestinasi" name="namaDestinasi"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="hargaDestinasi" class="form-label">Harga Tiket</label>
                                    <input type="number" class="form-control" id="hargaDestinasi" name="hargaDestinasi"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="stokDestinasi" class="form-label">Stok Tiket</label>
                                    <input type="number" class="form-control" id="stokDestinasi" name="stokDestinasi"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="ratingDestinasi" class="form-label">Rating</label>
                                    <input type="number" class="form-control" id="ratingDestinasi"
                                        name="ratingDestinasi" min="1" max="5" step="0.1" required>
                                </div>

                                <div class="mb-3">
                                    <label for="regionDestinasi" class="form-label">Region</label>
                                    <select id="regionDestinasi" name="regionDestinasi" class="form-select" required>
                                        <option value="">Pilih Region</option>
                                        <option value="3471">Kota Yogyakarta</option>
                                        <option value="3403">Kabupaten Gunung Kidul</option>
                                        <option value="3404">Kabupaten Sleman</option>
                                        <option value="3402">Kabupaten Bantul</option>
                                        <option value="3401">Kabupaten Kulon Progo</option>
                                    </select>
                                </div>

                                <!--DESKRIPSI TAMBAH-->
                                <div class="mb-3">
                                    <label for="editDeskripsi" class="form-label">Deskripsi (maksimal 20 kata)</label>
                                    <textarea class="form-control" id="editDeskripsi" name="description" rows="3"
                                        required></textarea>
                                    <div id="deskripsiWarning" style="display: none; color:red;">Maksimal 20 kata</div>
                                </div>

                                <div class="mb-3">
                                    <label for="editLinkMaps" class="form-label">Link Google Maps</label>
                                    <input type="url" class="form-control" id="editLinkMaps" name="address" required>
                                </div>


                                <div class="mb-3">
                                    <label for="gambarDestinasi" class="form-label">📷 Upload Gambar</label>
                                    <input type="file" class="form-control" id="gambarDestinasi" name="gambarDestinasi"
                                        accept="image/png,image/jpeg">
                                    <div class="preview-container mt-2" id="previewDestinasi" style="display: none;">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Batal</button>
                                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <!-- Alert -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="alertToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="alertMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- ChartJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($resultDestinations as $row): ?>
                const fileInput<?= $row['destination_code'] ?> = document.getElementById('foto<?= $row['destination_code'] ?>');
                const previewBaru<?= $row['destination_code'] ?> = document.getElementById('previewBaru<?= $row['destination_code'] ?>');
                const previewLama<?= $row['destination_code'] ?> = document.getElementById('previewLama<?= $row['destination_code'] ?>');

                fileInput<?= $row['destination_code'] ?>.addEventListener('change', function (e) {
                    const file = e.target.files[0];

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewBaru<?= $row['destination_code'] ?>.innerHTML = `
                        <img src="${e.target.result}" alt="Preview Baru"
                            class="img-fluid rounded shadow"
                            style="max-height: 150px; object-fit: cover;">
                        <p class="text-muted mt-2">Preview foto baru</p>
                    `;
                            previewBaru<?= $row['destination_code'] ?>.style.display = 'block';

                            // Sembunyikan preview lama
                            if (previewLama<?= $row['destination_code'] ?>) {
                                previewLama<?= $row['destination_code'] ?>.style.display = 'none';
                            }
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewBaru<?= $row['destination_code'] ?>.style.display = 'none';
                        if (previewLama<?= $row['destination_code'] ?>) {
                            previewLama<?= $row['destination_code'] ?>.style.display = 'block';
                        }
                    }
                });
            <?php endforeach; ?>
        });

        // tombol edit 
        document.querySelectorAll('.btnEdit').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('editId').value = this.dataset.id;
                document.getElementById('editNama').value = this.dataset.nama;
                document.getElementById('editStok').value = this.dataset.stok;
                document.getElementById('editPrice').value = this.dataset.price;
                document.getElementById('editRating').value = this.dataset.rating;
                document.getElementById('editDeskripsi').value = this.dataset.deskripsi;
                document.getElementById('editLinkMaps').value = this.dataset.alamat;
            });
        });


        // IMAGE PREVIEW SCRIPT
        document.getElementById('gambarDestinasi').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('previewDestinasi');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="img-fluid rounded shadow">';
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Pencarian
        let timeout = null;

        document.getElementById('searchInput').addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                document.getElementById('searchForm').submit();
            }, 800);
        });


        // Alert 
        function showAlert(message, type = 'success') {
            const toastEl = document.getElementById('alertToast');
            const messageBox = document.getElementById('alertMessage');
            toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
            messageBox.textContent = message;

            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
        }

        document.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);

            if (params.has("error")) {
                const errorMessage = decodeURIComponent(params.get("error"));
                showAlert(errorMessage, "danger");
            }

            if (params.has("success")) {
                const successMessage = decodeURIComponent(params.get("success"));
                showAlert(successMessage, "success");
            }
        });



        document.querySelectorAll("textarea[id^='editDeskripsi']").forEach(function (input) {
            const kode = input.id.replace("editDeskripsi", ""); // Ambil kode uniknya
            const warning = document.getElementById("deskripsiWarning" + kode);
            const formEdit = input.closest("form");

            if (warning && formEdit) {
                input.addEventListener("input", function () {
                    const kata = this.value.trim().split(/\s+/);
                    if (kata.length > 20) {
                        warning.style.display = "block";
                        this.value = kata.slice(0, 20).join(" ");
                    } else {
                        warning.style.display = "none";
                    }
                });

                formEdit.addEventListener("submit", function (e) {
                    const kata = input.value.trim().split(/\s+/);
                    if (kata.length > 20) {
                        e.preventDefault();
                        warning.style.display = "block";
                        showAlert("Deskripsi tidak boleh lebih dari 20 kata!", "danger");
                    }
                });
            }
        });



    </script>

</body>

</html>

<?php $conn->close(); ?>