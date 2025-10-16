<?php
include("frontend/session_start.php");
include("connection.php");

// Default sort
$sort_order = 'latest';
if (isset($_GET['sort'])) {
    $sort_order = $_GET['sort'];
}

// Sort logic
$order_by = "ORDER BY tripid DESC";
if ($sort_order == 'oldest') {
    $order_by = "ORDER BY tripid ASC";
} elseif ($sort_order == 'popular') {
    $order_by = "ORDER BY views DESC";
}

// Fetch trips and their main image
$sql = "SELECT trips.*, trip_images.main_image 
        FROM trips 
        INNER JOIN trip_images ON trips.tripid = trip_images.tripid 
        $order_by";
$stmt_trips = $conn->prepare($sql);
$stmt_trips->execute();
$trip_result = $stmt_trips->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trips | ThankYouNepalTrip</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        .card-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; }
        .card { border: none; border-radius: 10px; max-width: calc(33.33% - 20px); transition: 0.3s; margin: auto; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
        .carousel img { height: 250px; width: 100%; object-fit: cover; border-radius: 10px 10px 0 0; }
        .badge-featured { position: absolute; top: 10px; left: 10px; background-color: #ffc107; color: #fff; padding: 5px 10px; border-radius: 5px; font-size: 12px; }
        .price { font-size: 24px; font-weight: bold; color: #333; }
        .btn-view-details { background-color: #fd7e14; color: #fff; font-weight: bold; border-radius: 5px; transition: background-color 0.3s; }
        .btn-view-details:hover { background-color: #e96b0c; }
        .wishlist-icon { color: #cbdc35ff; font-size: 20px; cursor: pointer; transition: color 0.3s ease; }
        .wishlist-icon.active { color: #dc3545; }
        .card-icon { color: #28a745; margin-right: 10px; width: 20px; text-align: center; }
        @media (max-width: 768px) { .card { max-width: 100%; } }
    </style>
</head>
<body>

<?php include("frontend/header.php"); ?>

<section class="trips-section py-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>All Trips</h2>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    Sort: <?php echo ucfirst($sort_order); ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="sortMenu">
                    <li><a class="dropdown-item" href="?sort=latest">Latest</a></li>
                    <li><a class="dropdown-item" href="?sort=oldest">Oldest</a></li>
                    <li><a class="dropdown-item" href="?sort=popular">Popular</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="trips-section">
    <div class="features">
        <div class="container text-center py-5 card-container">
            <?php if ($trip_result->num_rows > 0): ?>
                <?php while ($trip = $trip_result->fetch_assoc()): ?>
                    <div class="card">
                        <div class="position-relative">
                            <a href="view-trip?tripid=<?php echo htmlspecialchars($trip['tripid']); ?>">
                                <img src="<?php echo htmlspecialchars($trip['main_image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($trip['title']); ?>">
                            </a>
                            <span class="badge-featured">Featured</span>
                        </div>

                        <div class="card-body text-start">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0"><?php echo htmlspecialchars($trip['title']); ?></h5>
                                <i class="fas fa-heart wishlist-icon" onclick="this.classList.toggle('active')"></i>
                            </div>

                            <p class="mb-2 text-muted">
                                <?php
                                    $desc = explode(" ", $trip['description']);
                                    echo htmlspecialchars(implode(" ", array_slice($desc, 0, 12))) . '...';
                                ?>
                            </p>

                            <p class="mb-1"><i class="fas fa-map-marker-alt card-icon"></i><?php echo htmlspecialchars($trip['location']); ?></p>
                            <p class="mb-1"><i class="fas fa-clock card-icon"></i><?php echo htmlspecialchars($trip['duration']); ?></p>
                            <p class="mb-1"><i class="fas fa-users card-icon"></i><?php echo htmlspecialchars($trip['groupsize']); ?> People</p>
                            <p class="mb-1"><i class="fas fa-walking card-icon"></i><?php echo htmlspecialchars($trip['activity']); ?></p>
                            <p class="mb-1"><i class="fas fa-route card-icon"></i><?php echo htmlspecialchars($trip['triptype']); ?></p>
                            <p class="mb-1"><i class="fas fa-bus-alt card-icon"></i><?php echo htmlspecialchars($trip['transportation']); ?></p>

                            <div class="price mt-3">$<?php echo number_format($trip['price']); ?></div>

                            <a class="btn btn-view-details w-100 mt-3" href="view-trip?tripid=<?php echo htmlspecialchars($trip['tripid']); ?>">
                                VIEW DETAILS
                            </a>
                            <p class="text-muted small mt-2">Next Departure: Feb 3, 2025 | Feb 4, 2025</p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No trips found.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
include("frontend/footer.php");
include("frontend/scrollup.html");
$conn->close();
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>
