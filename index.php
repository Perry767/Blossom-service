<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CleanPro Services</title>
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
        .nav-link:hover {
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
            text-align: center;
        }
        /* Hero Section */
        #hero {
            background: url('uploads/8b95803b9051ac249ddac056100dfefc.jpg') no-repeat center/cover;
            color: #ffffff;
            padding: 100px 0;
            text-align: center;
            position: relative;
        }
        #hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(30, 58, 138, 0.6);
        }
        #hero h1 {
            font-size: 2.5rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }
        #hero p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        #hero .btn-hero {
            background-color: #1e3a8a;
            border: none;
            padding: 10px 25px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 5px;
            color: #ffffff;
            transition: background-color 0.3s ease;
        }
        #hero .btn-hero:hover {
            background-color: #1c355e;
        }
        /* Services Section */
        #services .service-card {
            text-align: center;
            padding: 15px;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        #services .service-card img {
            max-width: 70px;
            margin-bottom: 10px;
        }
        #services .service-card h4 {
            color: #1e3a8a;
            font-weight: 500;
            font-size: 1.1rem;
        }
        #services .service-card p {
            font-size: 0.9rem;
            color: #666;
        }
        /* Pricing Section */
        #pricing .table {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }
        #pricing th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 0.9rem;
        }
        #pricing td {
            font-size: 0.9rem;
            color: #333;
        }
        /* Reviews Section */
        #reviews .review-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        #reviews .review-image-preview {
            max-width: 50px;
            max-height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }
        #reviews h5 {
            color: #1e3a8a;
            font-weight: 500;
            font-size: 1rem;
            margin-bottom: 5px;
        }
        #reviews p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }
        /* Gallery Section (Slideshow) */
        #gallery .carousel-item img {
            border-radius: 8px;
            max-height: 300px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }
        #gallery .gallery-description {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 10px;
            margin-top: 15px;
            text-align: center;
            font-size: 0.9rem;
            color: #1e3a8a;
        }
        #gallery .carousel-control-prev-icon,
        #gallery .carousel-control-next-icon {
            background-color: #1e3a8a;
            border-radius: 50%;
            padding: 15px;
        }
        /* Book Now Section */
        #book-now .alert {
            font-size: 0.9rem;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        #book-now .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        #book-now .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        #book-now .book-now-input {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
            font-size: 0.9rem;
            color: #333;
        }
        #book-now .book-now-input:focus {
            border-color: #1e3a8a;
            outline: none;
            box-shadow: 0 0 5px rgba(30, 58, 138, 0.2);
        }
        #book-now .book-now-btn {
            background-color: #1e3a8a;
            border: none;
            padding: 12px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 5px;
            color: #ffffff;
            transition: background-color 0.3s ease;
        }
        #book-now .book-now-btn:hover {
            background-color: #1c355e;
        }
        /* Footer Section */
        footer {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 50px 0;
            font-size: 0.9rem;
        }
        footer h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        footer p, footer a {
            color: #e0e7ff;
            margin-bottom: 10px;
        }
        footer a {
            text-decoration: none;
        }
        footer a:hover {
            color: #ffffff;
        }
        footer .social-icons a {
            margin-right: 15px;
        }
        footer .social-icons img {
            width: 24px;
            height: 24px;
        }
        /* WhatsApp Icon */
        .whatsapp-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        .whatsapp-icon img {
            width: 50px;
            height: 50px;
            transition: transform 0.3s ease;
        }
        .whatsapp-icon img:hover {
            transform: scale(1.1);
        }
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            #hero {
                padding: 80px 0;
            }
            #hero h1 {
                font-size: 2rem;
            }
            #hero p {
                font-size: 1rem;
            }
            .section-card {
                padding: 20px;
            }
            #gallery .carousel-item img {
                max-height: 200px;
            }
        }
        @media (max-width: 576px) {
            #book-now .book-now-input {
                font-size: 0.85rem;
                padding: 8px;
            }
            #book-now .book-now-btn {
                font-size: 0.9rem;
                padding: 10px;
            }
            #gallery .carousel-item img {
                max-height: 150px;
            }
            #hero h1 {
                font-size: 1.5rem;
            }
            #hero p {
                font-size: 0.9rem;
            }
            .whatsapp-icon img {
                width: 40px;
                height: 40px;
            }
            footer {
                text-align: center;
            }
            footer .social-icons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">BLOSSOM SERVICES</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#reviews">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="#book-now">Book Now</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero">
        <div class="container">
            <h1 data-aos="fade-down">Welcome to Blossom Services</h1>
            <p data-aos="fade-up">Professional cleaning services for homes and businesses.</p>
            <a href="#book-now" class="btn btn-hero" data-aos="zoom-in">Book Now</a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <div class="section-card" data-aos="fade-up">
                <h2>Our Services</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                            <img src="https://img.icons8.com/ios-filled/100/1e3a8a/broom.png" alt="Basic Cleaning">
                            <h4>Basic Cleaning</h4>
                            <p>General cleaning for your space.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                            <img src="uploads/cleaning.png" alt="Basic Cleaning">
                            <h4>Deep Cleaning</h4>
                            <p>Thorough cleaning solutions.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-card" data-aos="fade-up" data-aos-delay="300">
                            <img src="https://img.icons8.com/ios-filled/100/1e3a8a/vacuum-cleaner.png" alt="Carpet Cleaning">
                            <h4>Carpet Cleaning</h4>
                            <p>Specialized carpet care.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-5">
        <div class="container">
            <div class="section-card" data-aos="fade-up">
                <h2>Pricing</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Description</th>
                            <th>Price</th>
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
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3">No pricing entries found.</td></tr>';
                            }
                            $conn->close();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section id="reviews" class="py-5">
        <div class="container">
            <div class="section-card" data-aos="fade-up">
                <h2>Client Testimonials</h2>
                <div class="row">
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
                    if ($conn->connect_error) {
                        echo '<div class="alert alert-danger">Database connection failed: ' . $conn->connect_error . '</div>';
                    } else {
                        $result = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="col-md-6">
                                        <div class="review-card" data-aos="fade-up">
                                            <img src="' . (empty($row['client_image']) ? 'https://via.placeholder.com/50' : htmlspecialchars($row['client_image'])) . '" class="review-image-preview" alt="Client Image">
                                            <div>
                                                <h5>' . htmlspecialchars($row['client_name']) . '</h5>
                                                <p>' . htmlspecialchars($row['review_text']) . '</p>
                                                <p>' . str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']) . '</p>
                                            </div>
                                        </div>
                                      </div>';
                            }
                        } else {
                            echo '<p>No reviews found.</p>';
                        }
                        $conn->close();
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section (Slideshow) -->
    <section id="gallery" class="py-5">
        <div class="container">
            <div class="section-card" data-aos="fade-up">
                <h2>REVIEWS</h2>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
                if ($conn->connect_error) {
                    echo '<div class="alert alert-danger">Database connection failed: ' . $conn->connect_error . '</div>';
                } else {
                    $result = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
                    if ($result->num_rows > 0) {
                        echo '<div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">';
                        $first = true;
                        $descriptions = [];
                        while ($row = $result->fetch_assoc()) {
                            $activeClass = $first ? ' active' : '';
                            echo '<div class="carousel-item' . $activeClass . '">
                                    <div class="d-flex justify-content-center">
                                        <div class="row w-100">
                                            <div class="col-6"><img src="' . htmlspecialchars($row['before_image']) . '" class="d-block w-100" alt="Before"></div>
                                            <div class="col-6"><img src="' . htmlspecialchars($row['after_image']) . '" class="d-block w-100" alt="After"></div>
                                        </div>
                                    </div>
                                  </div>';
                            $descriptions[] = htmlspecialchars($row['description']);
                            $first = false;
                        }
                        echo '</div>
                              <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Previous</span>
                              </button>
                              <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Next</span>
                              </button>
                          </div>';
                        // Display description below the carousel
                        echo '<div id="galleryDescription" class="gallery-description">' . $descriptions[0] . '</div>';
                        // JavaScript to update description on slide change
                        echo '<script>
                                const descriptions = ' . json_encode($descriptions) . ';
                                const descriptionBox = document.getElementById("galleryDescription");
                                document.getElementById("galleryCarousel").addEventListener("slid.bs.carousel", function (event) {
                                    descriptionBox.textContent = descriptions[event.to];
                                });
                              </script>';
                    } else {
                        echo '<p>No gallery items found.</p>';
                    }
                    $conn->close();
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Book Now Section -->
    <section id="book-now" class="py-5">
        <div class="container">
            <div class="section-card" data-aos="fade-up">
                <h2>Book Now</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    error_log("Error message displayed: " . $_SESSION['error'] . " at " . date('Y-m-d H:i:s'));
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                    error_log("Success message displayed: " . $_SESSION['success'] . " at " . date('Y-m-d H:i:s'));
                    unset($_SESSION['success']);
                } else {
                    error_log("No success message found in session after booking submission at " . date('Y-m-d H:i:s'));
                }
                ?>
                <form action="submit_booking.php" method="POST" class="mb-4" onsubmit="return validateForm()">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control book-now-input" placeholder="Your Name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control book-now-input" placeholder="Your Email" required>
                        </div>
                        <div class="col-md-6">
                            <input type="tel" name="phone_number" class="form-control book-now-input" placeholder="Your Phone Number" required>
                        </div>
                        <div class="col-md-6">
                            <textarea name="address" class="form-control book-now-input" placeholder="Your Address" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <select name="service_type" class="form-control book-now-input" required>
                                <option value="">Select Service</option>
                                <option value="Basic Cleaning">Basic Cleaning</option>
                                <option value="Deep Cleaning">Deep Cleaning</option>
                                <option value="Carpet Cleaning">Carpet Cleaning</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="preferred_date" class="form-control book-now-input" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 book-now-btn">Submit Booking</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-md-4">
                    <h4>BLOSSOM SERVICES</h4>
                    <p>We provide top-notch cleaning services to ensure your space is spotless and inviting. Trust us to deliver quality and professionalism every time.</p>
                </div>
                <!-- Quick Links -->
                <div class="col-md-4">
                    <h4>Quick Links</h4>
                    <p><a href="#services">Services</a></p>
                    <p><a href="#pricing">Pricing</a></p>
                    <p><a href="#reviews">Reviews</a></p>
                    <p><a href="#gallery">Gallery</a></p>
                    <p><a href="#book-now">Book Now</a></p>
                </div>
                <!-- Contact Info -->
                <div class="col-md-4">
                    <h4>Contact Us</h4>
                    <p>Phone: <a href="tel:+2341234567890">+234 123 456 7890</a></p>
                    <p>Email: <a href="mailto:info@cleanproservices.com">info@cleanproservices.com</a></p>
                    <p>Address: 123 Clean Street, Lagos, Nigeria</p>
                    <div class="social-icons d-flex mt-3">
                        <a href="https://facebook.com" target="_blank"><img src="https://img.icons8.com/ios-filled/50/ffffff/facebook-new.png" alt="Facebook"></a>
                        <a href="https://twitter.com" target="_blank"><img src="https://img.icons8.com/ios-filled/50/ffffff/twitter.png" alt="Twitter"></a>
                        <a href="https://instagram.com" target="_blank"><img src="https://img.icons8.com/ios-filled/50/ffffff/instagram-new.png" alt="Instagram"></a>
                    </div>
                </div>
            </div>
            <hr style="border-color: #e0e7ff; margin: 20px 0;">
            <p class="text-center">© 2025 BLOSSOM SERVICES. All rights reserved.</p>
        </div>
    </footer>

    <!-- WhatsApp Icon -->
    <div class="whatsapp-icon">
        <a href="https://wa.me/2349071911619" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800 });
        document.querySelectorAll('.navbar-nav a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
            });
        });

        function validateForm() {
            const phoneNumber = document.querySelector('input[name="phone_number"]').value;
            const preferredDate = document.querySelector('input[name="preferred_date"]').value;
            const phoneRegex = /^\+?\d{10,15}$/;
            const today = new Date().toISOString().split('T')[0];

            if (!phoneRegex.test(phoneNumber)) {
                alert('Please enter a valid phone number (10-15 digits, optionally starting with +).');
                return false;
            }

            if (preferredDate < today) {
                alert('Preferred date cannot be in the past.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>