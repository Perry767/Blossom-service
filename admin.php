<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CleanPro Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            color: #333;
            min-height: 100vh;
            margin: 0;
        }
        .sidebar {
            background-color: #1e3a8a;
            height: 100vh;
            position: fixed;
            width: 250px;
            padding-top: 20px;
            border-right: 1px solid #e5e7eb;
        }
        .sidebar a {
            color: #ffffff;
            padding: 15px;
            display: block;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .sidebar a:hover {
            background-color: #1c355e;
        }
        .main-content {
            margin-left: 250px;
            padding: 40px;
        }
        .navbar {
            background-color: #1e3a8a;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #e0e7ff !important;
        }
        .section-card {
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
        }
        h2 {
            color: #1e3a8a;
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .badge {
            font-size: 0.8rem;
        }
        .table {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }
        .table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 0.9rem;
        }
        .table td {
            font-size: 0.9rem;
            color: #333;
        }
        .btn-sm {
            font-size: 0.8rem;
            padding: 4px 10px;
        }
        .review-image-preview {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 5px;
        }
        .alert {
            font-size: 0.9rem;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        /* Added styles for reply status */
        .status-pending {
            color: #f39c12;
            font-weight: 500;
        }
        .status-replied {
            color: #28a745;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .section-card {
                padding: 20px;
            }
            .table {
                font-size: 0.85rem;
            }
        }
        @media (max-width: 576px) {
            .section-card {
                padding: 15px;
            }
            .btn-sm {
                font-size: 0.75rem;
                padding: 3px 8px;
            }
            .table {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">BLOSSOM Admin</a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#bookings" data-aos="fade-right">Manage Bookings</a>
        <a href="#pricing" data-aos="fade-right" data-aos-delay="100">Manage Pricing</a>
        <a href="#reviews" data-aos="fade-right" data-aos-delay="200">Manage Reviews</a>
        <a href="#gallery" data-aos="fade-right" data-aos-delay="300">Manage Gallery</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Bookings Section -->
        <section id="bookings" class="mb-5">
            <div class="section-card" data-aos="fade-up">
                <h2 class="card-title mb-4 d-flex align-items-center">
                    Bookings
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
                    if (!$conn->connect_error && isset($_SESSION['admin_id'])) {
                        $stmt = $conn->prepare("SELECT last_login FROM users WHERE id = ?");
                        $stmt->bind_param("i", $_SESSION['admin_id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $last_login = $result->fetch_assoc()['last_login'] ?? null;
                        $stmt->close();

                        if ($last_login) {
                            $new_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE created_at > '$last_login'");
                            $new_count = $new_bookings->fetch_assoc()['count'];
                            if ($new_count > 0) {
                                echo '<span class="badge bg-danger ms-2">' . $new_count . ' New</span>';
                            }
                        }
                    }
                    ?>
                </h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($conn->connect_error) {
                            echo '<div class="alert alert-danger">Database connection failed: ' . $conn->connect_error . '</div>';
                            error_log("Database connection failed in admin.php: " . $conn->connect_error);
                        } else {
                            $result = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC");
                            if ($result === false) {
                                echo '<div class="alert alert-danger">Query failed: ' . $conn->error . '</div>';
                                error_log("Query failed in admin.php: " . $conn->error);
                            } elseif ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $status_class = $row['reply_status'] === 'pending' ? 'status-pending' : 'status-replied';
                                    echo '<tr>
                                            <td>' . htmlspecialchars($row['client_name']) . '</td>
                                            <td>' . htmlspecialchars($row['client_email']) . '</td>
                                            <td>' . htmlspecialchars($row['phone_number']) . '</td>
                                            <td>' . htmlspecialchars($row['address']) . '</td>
                                            <td>' . htmlspecialchars($row['service_type']) . '</td>
                                            <td>' . htmlspecialchars($row['preferred_date']) . '</td>
                                            <td><span class="' . $status_class . '">' . htmlspecialchars($row['reply_status']) . '</span></td>
                                            <td>
                                                <a href="reply_booking.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm me-1">Reply</a>
                                                <a href="delete_booking.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</a>
                                            </td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8">No bookings found.</td></tr>';
                                error_log("No bookings found in admin.php at " . date('Y-m-d H:i:s'));
                            }
                            $conn->close();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="mb-5">
            <div class="section-card" data-aos="fade-up">
                <h2 class="card-title mb-4">Pricing</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <form action="update_pricing.php" method="POST" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="service_type" class="form-control" placeholder="Service Type" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="description" class="form-control" placeholder="Description" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="price" class="form-control" placeholder="Price" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Add</button>
                        </div>
                    </div>
                </form>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
                        if ($conn->connect_error) {
                            echo '<div class="alert alert-danger">Database connection failed: ' . $conn->connect_error . '</div>';
                        } else {
                            $result = $conn->query("SELECT * FROM pricing");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                            <td>' . htmlspecialchars($row['service_type']) . '</td>
                                            <td>' . htmlspecialchars($row['description']) . '</td>
                                            <td>$' . number_format($row['price'], 2) . '</td>
                                            <td>
                                                <a href="delete_pricing.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</a>
                                            </td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4">No pricing entries found.</td></tr>';
                            }
                            $conn->close();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Reviews Section with Client Image Upload -->
        <section id="reviews" class="mb-5">
            <div class="section-card" data-aos="fade-up">
                <h2 class="card-title mb-4">Reviews</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <form action="add_review.php" method="POST" enctype="multipart/form-data" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="client_name" class="form-control" placeholder="Client Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="review_text" class="form-control" placeholder="Review" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="rating" class="form-control" placeholder="Rating (1-5)" min="1" max="5" required>
                        </div>
                        <div class="col-md-2">
                            <input type="file" name="client_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Add</button>
                        </div>
                    </div>
                </form>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Review</th>
                            <th>Rating</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
                        if ($conn->connect_error) {
                            echo '<div class="alert alert-danger">Database connection failed: ' . $conn->connect_error . '</div>';
                        } else {
                            $result = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                            <td>' . htmlspecialchars($row['client_name']) . '</td>
                                            <td>' . htmlspecialchars($row['review_text']) . '</td>
                                            <td>' . str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']) . '</td>
                                            <td><img src="' . (empty($row['client_image']) ? 'https://via.placeholder.com/50' : htmlspecialchars($row['client_image'])) . '" class="review-image-preview" alt="Client Image"></td>
                                            <td>
                                                <a href="delete_review.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</a>
                                            </td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5">No reviews found.</td></tr>';
                            }
                            $conn->close();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Gallery Section -->
        <section id="gallery">
            <div class="section-card" data-aos="fade-up">
                <h2 class="card-title mb-4">Before & After Gallery</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <form action="add_gallery.php" method="POST" enctype="multipart/form-data" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="description" class="form-control" placeholder="Description" required>
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="before_image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="after_image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Upload</button>
                        </div>
                    </div>
                </form>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
                        if ($conn->connect_error) {
                            echo '<div class="alert alert-danger">Database connection failed: ' . $conn->connect_error . '</div>';
                        } else {
                            $result = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                            <td>' . htmlspecialchars($row['description']) . '</td>
                                            <td><img src="' . htmlspecialchars($row['before_image']) . '" style="max-width: 100px;"></td>
                                            <td><img src="' . htmlspecialchars($row['after_image']) . '" style="max-width: 100px;"></td>
                                            <td>
                                                <a href="delete_gallery.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</a>
                                            </td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4">No gallery items found.</td></tr>';
                            }
                            $conn->close();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000 });
        document.querySelectorAll('.sidebar a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>