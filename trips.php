<?php
include("frontend/session_start.php");
include("connection.php");

// Prepared statement to fetch trip data along with the main image
// This is more secure than a direct query
$stmt_trips = $conn->prepare("SELECT trips.*, trip_images.main_image FROM trips INNER JOIN trip_images ON trips.tripid = trip_images.tripid");
$stmt_trips->execute();
$trip_result = $stmt_trips->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>trips</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px; /* Added gap for better spacing */
        }

        .card {
            border: none;
            border-radius: 10px;
            max-width: calc(33.33% - 20px);
            max-height: fit-content;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            margin: auto;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .badge-featured {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #ffc107;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 10;
        }

        .price {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 16px;
        }

        .btn-view-details {
            background-color: #fd7e14;
            color: #fff;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-view-details:hover {
            background-color: #e96b0c;
        }

        .next-departure {
            font-size: 14px;
            color: #6c757d;
        }

        .wishlist-icon {
            color: #dc3545;
            font-size: 20px;
            cursor: pointer;
        }

        .green-icon {
            color: #28a745;
        }

        .card-icon {
            color: #28a745;
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .card-contents p {
            margin-bottom: 5px;
        }

        .carousel {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
        }

        .carousel img {
            height: 250px;
            width: 100%;
            object-fit: cover;
        }

        .carousel-container {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            width: 100%;
            display: none;
        }

        .active {
            display: block;
        }

        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 50%;
            transition: background 0.3s;
        }

        .prev:hover, .next:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        @media (max-width: 768px) {
            .card {
                max-width: 100%; /* Stacks cards on small screens */
            }
        }
        .wishlist-icon {
    color: #cbdc35ff; /* Default color (unselected) */
    font-size: 20px;
    cursor: pointer;
    transition: color 0.3s ease; /* Smooth color transition */
}

.wishlist-icon.active {
    color: #dc3545; /* Active color (selected) */
}
    </style>
</head>
<body>

<?php
include("frontend/header.php");

// 1. Check for the 'sort' parameter in the URL
$sort_order = 'latest'; // Default sort order
if (isset($_GET['sort'])) {
    $sort_order = $_GET['sort'];
}

// 2. Define the ORDER BY clause based on the sort parameter
$order_by = "ORDER BY tripid DESC"; // Default: latest
if ($sort_order == 'oldest') {
    $order_by = "ORDER BY tripid ASC";
} elseif ($sort_order == 'popular') {
    $order_by = "ORDER BY views DESC"; // Assuming a 'views' column exists
}

// 3. Construct and execute the SQL query
$sql = "SELECT trips.*, trip_images.main_image FROM trips INNER JOIN trip_images ON trips.tripid = trip_images.tripid " . $order_by;
$stmt_trips = $conn->prepare($sql);
$stmt_trips->execute();
$trip_result = $stmt_trips->get_result();
?>

<section class="trips-section">
    <div class="sort-bar">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sort-container">
                        <span class="sort-text">Sort:</span>
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo ucfirst($sort_order); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="?sort=latest">Latest</a></li>
                                <li><a class="dropdown-item" href="?sort=oldest">Oldest</a></li>
                                <li><a class="dropdown-item" href="?sort=popular">Popular</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="trips-section">
    <div class="features">
        <div class="container text-center py-5 card-container" id="card-container">
            <?php if ($trip_result->num_rows > 0) {
                while ($trip = $trip_result->fetch_assoc()) { ?>
                    <div class="card">
                        <div class="position-relative">
                            <div class="carousel">
                                <div class="carousel-container">
                                    <a href="view-trip.php?tripid=<?php echo htmlspecialchars($trip['tripid']); ?>">
                                        <img src="<?php echo htmlspecialchars($trip['main_image']); ?>" class="slide active">
                                    </a>
                                </div>
                            </div>
                            <span class="badge-featured">
                                Featured
                            </span>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <a href="view-trip.php?tripid=<?php echo htmlspecialchars($trip['tripid']); ?>" style="text-decoration:none; color:black;">
                                    <h5 class="card-title mb-0">
                                        <?php
                                        $title = htmlspecialchars($trip["title"]);
                                        $words = explode(" ", $title);
                                        $limited_words = array_slice($words, 0, 6);
                                        echo implode(" ", $limited_words);
                                        ?>
                                    </h5>
                                </a>
                                <i class="fas fa-heart wishlist-icon" onclick="toggleWishlist(this)"></i>

                                <script>
                                    function toggleWishlist(element) {
                                        element.classList.toggle('active');
                                    }
                                </script>
                            </div>

                            <div class="card-contents" style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                                <p class="mb-1">
                                    <?php
                                    $description = $trip['description'];
                                    $words = explode(" ", $description);
                                    $firstTenWords = implode(" ", array_slice($words, 0, 10));
                                    echo htmlspecialchars($firstTenWords) . '...';
                                    ?>
                                </p>
                            </div>

                            <div class="d-flex justify-content-between align-items-end flex-wrap">
                                <div class="me-3 text-start">
                                    <p class="mb-1"><i class="fas fa-map-marker-alt card-icon"></i><?php echo htmlspecialchars($trip["location"]); ?></p>
                                    <p class="mb-1"><i class="fas fa-clock card-icon"></i><?php echo htmlspecialchars($trip["duration"]); ?></p>
                                    <p class="mb-1"><i class="fas fa-users card-icon"></i><?php echo htmlspecialchars($trip["groupsize"]); ?> People</p>
                                    <p class="mb-1"><i class="fas fa-walking card-icon"></i><?php echo htmlspecialchars($trip["activity"]); ?></p>
                                    <p class="mb-1"><i class="fas fa-route card-icon"></i><?php echo htmlspecialchars($trip["triptype"]); ?></p>
                                    <p class="mb-1"><i class="fas fa-bus-alt card-icon"></i><?php echo htmlspecialchars($trip["transportation"]); ?></p>
                                </div>

                                <div class="text-end">
                                    <div class="price">
                                        $<?php echo number_format($trip["price"]); ?>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-view-details w-100 mt-3" href="view-trip.php?tripid=<?php echo htmlspecialchars($trip['tripid']); ?>">
                                VIEW DETAILS
                            </a>
                            <p class="next-departure mt-3">
                                Next Departure: February 3, 2025 | February 4, 2025 | February 5, 2025
                            </p>
                        </div>
                    </div>
                <?php }
            } else {
                echo "<p>No trips found.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</section>

<?php
include("frontend/footer.php");
?>
<?php
include("frontend/scrollup.html");
?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wishlistIcons = document.querySelectorAll('.wishlist-icon');
        
        wishlistIcons.forEach(icon => {
            icon.addEventListener('click', () => {
                // Toggle the 'active' class on the clicked icon
                icon.classList.toggle('active');
            });
        });
    });
</script>
<script>
    // Since the carousel is now a single image, these functions aren't needed.
    // However, they are kept for potential future use with multiple images.
    function nextSlide(button) {
        const carousel = button.closest('.carousel');
        const slides = carousel.querySelectorAll('.slide');
        let currentIndex = Array.from(slides).findIndex(slide => slide.classList.contains('active'));
        slides[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % slides.length;
        slides[currentIndex].classList.add('active');
    }

    function prevSlide(button) {
        const carousel = button.closest('.carousel');
        const slides = carousel.querySelectorAll('.slide');
        let currentIndex = Array.from(slides).findIndex(slide => slide.classList.contains('active'));
        slides[currentIndex].classList.remove('active');
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        slides[currentIndex].classList.add('active');
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>