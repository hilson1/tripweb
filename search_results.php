<?php
include("frontend/session_start.php");
include("connection.php");

// Fetch dropdown options
$stmt_activities = $conn->prepare("SELECT DISTINCT TRIM(activity) AS activity FROM trips WHERE activity <> ''");
$stmt_activities->execute();
$activities_result = $stmt_activities->get_result();

$stmt_destinations = $conn->prepare("SELECT DISTINCT TRIM(location) AS location FROM trips WHERE location <> ''");
$stmt_destinations->execute();
$destinations_result = $stmt_destinations->get_result();

// Initialize defaults
$max_price = null;
$destination = "";
$activity = "";
$duration = "";

// Handle search
if (isset($_GET['search'])) {
    $max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (int)$_GET['max_price'] : null;
    $destination = strtolower(trim($_GET['destination'] ?? ''));
    $activity = strtolower(trim($_GET['activity'] ?? ''));
    $duration = $_GET['duration'] ?? '';

    // Base query
    $sql_query = "SELECT trips.*, trip_images.main_image 
                  FROM trips 
                  INNER JOIN trip_images ON trips.tripid = trip_images.tripid 
                  WHERE 1=1";

    $params = [];
    $types = '';

    // Filter: Activity
    if (!empty($activity) && $activity !== 'activity') {
        $sql_query .= " AND LOWER(TRIM(trips.activity)) = ?";
        $params[] = $activity;
        $types .= 's';
    }

    // Filter: Destination
    if (!empty($destination) && $destination !== 'destination') {
        $sql_query .= " AND LOWER(TRIM(trips.location)) = ?";
        $params[] = $destination;
        $types .= 's';
    }

    // Filter: Price
    if ($max_price !== null) {
        $sql_query .= " AND trips.price <= ?";
        $params[] = $max_price;
        $types .= 'i';
    }

    // Filter: Duration
    if (!empty($duration) && $duration !== '2 Days') {
        $min_days = 0;
        $max_days = PHP_INT_MAX;

        switch ($duration) {
            case '1-3': // 2–6 days
                $min_days = 2;
                $max_days = 6;
                break;
            case '4-7': // 2–14 days
                $min_days = 2;
                $max_days = 14;
                break;
            case '8-11': // 14+ days
                $min_days = 14;
                $max_days = PHP_INT_MAX;
                break;
            default:
                $min_days = 0;
                $max_days = PHP_INT_MAX;
        }

        // Handle duration stored as text like "5 Days"
        $sql_query .= " AND CAST(SUBSTRING_INDEX(trips.duration, ' ', 1) AS UNSIGNED) BETWEEN ? AND ?";
        $params[] = $min_days;
        $params[] = $max_days;
        $types .= 'ii';
    }

    // Finalize and execute
    $stmt = $conn->prepare($sql_query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default: show all trips
    $stmt = $conn->prepare("SELECT trips.*, trip_images.main_image 
                            FROM trips 
                            INNER JOIN trip_images ON trips.tripid = trip_images.tripid");
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<?php include("frontend/header.php"); ?>

<div class="container mt-5 pt-5">
    <h2 class="text-center mb-4">Search Results</h2>
    <div class="row" id="search-results-container">
        <?php if (isset($result) && $result->num_rows > 0): ?>
            <?php while ($trip = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars($trip['main_image']) ?>" class="card-img-top" alt="Trip Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($trip['title']) ?></h5>
                            <p class="card-text">
                                <?= htmlspecialchars(implode(' ', array_slice(explode(' ', $trip['description']), 0, 15))) ?>...
                            </p>
                            <ul class="list-unstyled mb-2">
                                <li><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($trip['location']) ?></li>
                                <li><i class="fas fa-clock"></i> <?= htmlspecialchars($trip['duration']) ?></li>
                                <li><i class="fas fa-hiking"></i> <?= htmlspecialchars($trip['activity']) ?></li>
                                <li><i class="fas fa-users"></i> <?= htmlspecialchars($trip['groupsize']) ?> People</li>
                            </ul>
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="price">$<?= number_format($trip['price']) ?></h4>
                                <a href="view-trip.php?tripid=<?= urlencode($trip['tripid']) ?>" class="btn btn-warning">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No trips found matching your criteria.</p>
        <?php endif; ?>
    </div>
</div>

<?php include("frontend/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
