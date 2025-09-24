<?php
include("frontend/session_start.php");
include("connection.php");

// Fetch activities and destinations for the dropdowns
$stmt_activities = $conn->prepare("SELECT DISTINCT activity FROM trips");
$stmt_activities->execute();
$activities_result = $stmt_activities->get_result();

$stmt_destinations = $conn->prepare("SELECT DISTINCT location FROM trips");
$stmt_destinations->execute();
$destinations_result = $stmt_destinations->get_result();


// Initialize search parameters
$search_query = "";
$max_price = null;
$destination = "";
$activity = "";
$duration = "";

// Check if a search was performed and retrieve parameters
if (isset($_GET['search'])) {
    // Sanitize user input to prevent SQL injection
    $max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (int)$_GET['max_price'] : null;
    $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
    $activity = isset($_GET['activity']) ? $_GET['activity'] : '';
    $duration = isset($_GET['duration']) ? $_GET['duration'] : '';
    $date = isset($_GET['date']) ? $_GET['date'] : '';

    // Construct the SQL query
    $sql_query = "SELECT trips.*, trip_images.main_image FROM trips INNER JOIN trip_images ON trips.tripid = trip_images.tripid WHERE 1=1";
    
    // Use a prepared statement to prevent SQL injection
    $params = [];
    $types = '';

    // Add conditions based on user input
    if (!empty($activity) && $activity !== 'Activity') {
        $sql_query .= " AND trips.activity = ?";
        $params[] = $activity;
        $types .= 's';
    }
    
    if (!empty($destination) && $destination !== 'Destination') {
        $sql_query .= " AND trips.location = ?";
        $params[] = $destination;
        $types .= 's';
    }

    if ($max_price !== null) {
        $sql_query .= " AND trips.price <= ?";
        $params[] = $max_price;
        $types .= 'd';
    }

    if (!empty($duration) && $duration !== '0 Days - 11 Days') {
        $duration_parts = explode('-', $duration);
        $min_days = (int)$duration_parts[0];
        $max_days = (int)$duration_parts[1];
        $sql_query .= " AND trips.duration BETWEEN ? AND ?";
        $params[] = $min_days;
        $params[] = $max_days;
        $types .= 'ii';
    }
    
    // You can add more conditions for the date or other fields as needed
    // if (!empty($date)) {
    //     $sql_query .= " AND trips.start_date >= ?";
    //     $params[] = $date;
    //     $types .= 's';
    // }

    $stmt = $conn->prepare($sql_query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no search was performed, get all trips to display
    $stmt = $conn->prepare("SELECT trips.*, trip_images.main_image FROM trips INNER JOIN trip_images ON trips.tripid = trip_images.tripid");
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
    <link rel="stylesheet" href="index.css">
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
            <?php
            if (isset($result) && $result->num_rows > 0) {
                while ($trip = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo $trip['main_image']; ?>" class="card-img-top" alt="Trip Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($trip['title']); ?></h5>
                                <p class="card-text">
                                    <?php
                                    $description = $trip['description'];
                                    $words = explode(" ", $description);
                                    $firstTenWords = implode(" ", array_slice($words, 0, 10));
                                    echo htmlspecialchars($firstTenWords) . '...';
                                    ?>
                                </p>
                                <ul class="list-unstyled mb-2">
                                    <li><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($trip['location']); ?></li>
                                    <li><i class="fas fa-clock"></i> <?php echo htmlspecialchars($trip['duration']); ?></li>
                                    <li><i class="fas fa-users"></i> <?php echo htmlspecialchars($trip['groupsize']); ?> People</li>
                                </ul>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="price">$<?php echo number_format($trip['price']); ?></h4>
                                    <a href="view-trip?tripid=<?php echo $trip['tripid']; ?>" class="btn btn-warning">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No trips found matching your criteria. Try adjusting your search.</p>";
            }
            ?>
        </div>
    </div>

    <?php include("frontend/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>