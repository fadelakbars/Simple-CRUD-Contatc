<?php

// Konfigurasi Database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'crudcontact';

// Membuat Koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek Koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inisialisasi Variabel
$name = '';
$phone = '';
$id = 0;
$update = false;

// Tambah Kontak
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO contacts (name, phone) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $phone);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
}

// Edit Kontak
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $stmt = $conn->prepare("SELECT * FROM contacts WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $name = $result['name'];
    $phone = $result['phone'];
    $stmt->close();
}

// Update Kontak
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE contacts SET name=?, phone=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $phone, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
}

// Hapus Kontak
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
}

// Toggle Favorit
if (isset($_GET['favorite'])) {
    $id = $_GET['favorite'];

    // Cek status favorit saat ini
    $stmt = $conn->prepare("SELECT is_favorite FROM contacts WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $current = $stmt->get_result()->fetch_assoc()['is_favorite'];
    $stmt->close();

    // Toggle nilai favorit
    $new_fav = $current ? 0 : 1;
    $stmt = $conn->prepare("UPDATE contacts SET is_favorite=? WHERE id=?");
    $stmt->bind_param("ii", $new_fav, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
}

// Pencarian
$search = '';
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// Filter Favorit
$filter_fav = false;
if (isset($_GET['filter']) && $_GET['filter'] == 'favorites') {
    $filter_fav = true;
}

// Ambil Data Kontak
if ($filter_fav) {
    $sql = "SELECT * FROM contacts WHERE is_favorite=1 AND (name LIKE '%$search%' OR phone LIKE '%$search%') ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM contacts WHERE name LIKE '%$search%' OR phone LIKE '%$search%' ORDER BY created_at DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi Kontak</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>

        body {
            background-color: #f8f9fa;
        }
        .primary-color {
            background-color: #00FF9C;
        }
        .favorite {
            color: #FFD700;
        }

        /* Styling mobile yang lebih baik */
        @media (max-width: 576px) {
            body {
                padding: 10px;
                background-color: #f0f0f0;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }

            .form-control {
                font-size: 1.2rem;
                padding: 10px;
                width: 100%; /* Search bar full-width di layar kecil */
            }

            .navbar .btn-outline-light {
                font-size: 1.5rem;
                padding: 8px 15px;
            }

            .list-group-item {
                padding: 20px 15px;
                font-size: 1.3rem; /* Ukuran font lebih besar untuk mobile */
            }

            footer {
                display: flex;
                justify-content: space-around; /* Menyebarkan ikon dengan proporsional */
                align-items: center;
                padding: 15px 30px; /* Tambahkan padding */
                background-color: #fff;
                border-top: 1px solid #ccc;
            }

            footer a {
                font-size: 2rem; /* Membesarkan ikon */
                padding: 10px; /* Padding di sekitar ikon */
            }

            .dropdown-toggle {
                font-size: 1rem;
                padding: 5px 10px;
            }

            .btn-secondary {
                padding: 5px 10px;
            }

            .modal-dialog {
                max-width: 100%;
                margin: 10px;
            }

            .modal-content {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg primary-color">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="index.php"><h2>Kontak App</h2></a>
            <form class="d-flex" method="GET" action="index.php">
                <input class="form-control me-2" type="search" placeholder="Cari Kontak" aria-label="Search" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </nav>

    <!-- Daftar Kontak -->
    <div class="container-fluid my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Daftar Kontak</h3>
            <?php if ($filter_fav): ?>
                <a href="index.php" class="btn btn-secondary">Tampilkan Semua</a>
            <?php endif; ?>
        </div>
        <ul class="list-group">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5><?php echo htmlspecialchars($row['name']); ?> <?php if ($row['is_favorite']) echo '<i class="fas fa-star favorite"></i>'; ?></h5>
                            <p class="mb-0"><?php echo htmlspecialchars($row['phone']); ?></p>
                        </div>
                        <div>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $row['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $row['id']; ?>">
                                    <li><a class="dropdown-item" href="index.php?edit=<?php echo $row['id']; ?>">Edit</a></li>
                                    <li><a class="dropdown-item" href="index.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus kontak ini?')">Hapus</a></li>
                                    <li><a class="dropdown-item" href="index.php?favorite=<?php echo $row['id']; ?>"><?php echo $row['is_favorite'] ? 'Batal Favorit' : 'Jadikan Favorit'; ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="list-group-item">Tidak ada kontak ditemukan.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Footer -->
    <footer class="fixed-bottom bg-white border-top">
        <div class="container-fluid d-flex justify-content-between align-items-center py-2 px-5">
            <a href="index.php?filter=favorites" class="text-decoration-none text-dark mx-auto">
                <i class="fas fa-star fa-2x"></i>
            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#contactModal" class="text-decoration-none text-dark mx-auto">
                <i class="fas fa-plus-circle fa-2x"></i>
            </a>
        </div>
    </footer>

    <!-- Modal Tambah/Edit Kontak -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="index.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contactModalLabel"><?php echo $update ? 'Edit Kontak' : 'Tambah Kontak'; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($name); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone" required value="<?php echo htmlspecialchars($phone); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <?php if ($update): ?>
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <?php else: ?>
                            <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS dan Dependensi -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Membuka modal jika sedang dalam mode edit
        <?php if ($update): ?>
            var contactModal = new bootstrap.Modal(document.getElementById('contactModal'));
            contactModal.show();
        <?php endif; ?>
    </script>
</body>
</html>

<?php
// Menutup Koneksi
$conn->close();
?>
