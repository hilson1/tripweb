<?php
include("frontend/session_start.php");
require 'connection.php';

// Default values
$destination = "Unknown Destination";
$description = "No destination specified.";
$main_image_filename = "default-destination.jpg";

// Fetch destination details
if (isset($_GET['destination-is'])) {
    $destination = trim($_GET['destination-is']);

    $sql = "SELECT description, main_image FROM destinations WHERE distination = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $destination);
    $stmt->execute();
    $stmt->bind_result($description, $fetched_image);

    if ($stmt->fetch() && !empty($fetched_image)) {
        $main_image_filename = $fetched_image;
    } else {
        $description = "No description found for this destination.";
    }

    $stmt->close();
}

$main_image_filename = basename($main_image_filename);
$background_url = "assets/destinations/" . htmlspecialchars($main_image_filename);
if (empty($main_image_filename) || !file_exists($background_url)) {
    $background_url = "assets/destinations/default-activity.jpg";
}

// Fetch related trips
$sql_trips = "SELECT trips.*, trip_images.main_image 
              FROM trips 
              INNER JOIN trip_images ON trips.tripid = trip_images.tripid 
              INNER JOIN destinations ON trips.location = destinations.distination
              WHERE destinations.distination = ?";
$stmt_trips = $conn->prepare($sql_trips);
$stmt_trips->bind_param("s", $destination);
$stmt_trips->execute();
$trip_result = $stmt_trips->get_result();

$safe_destination = htmlspecialchars($destination);
$safe_description = htmlspecialchars($description);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $safe_destination; ?> | Destination</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
     .destination-hero-head {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        height: 400px;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
      }

      .destination-header-title {
        position: absolute;
        text-align: center;
        color: white;
        z-index: 10;
      }

      .destination-header-title h1 {
        font-size: 4em;
        font-weight: 600;
        text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.7);
      }

      .features {
        margin-top: 40px;
      }

      .section-title {
        text-align: center;
        padding: 50px 0;
      }

      .section-title h1 {
        font-weight: 700;
        font-size: 36px;
        color: #000;
      }

      /* --- CARD GRID --- */
      #card-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        background-color: transparent;
      }

      @media (max-width: 768px) {
        #card-container {
          grid-template-columns: 1fr;
        }
      }

      /* --- CARD --- */
      .card {
        border: none;
        border-radius: 10px;
        width: 100%;
        max-width: 400px;
        height: 520px; /* consistent height */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: 0.3s;
        margin: 0 auto 30px;
        overflow: hidden;
      }

      .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-5px);
      }

      /* --- CARD IMAGE --- */
      .carousel img {
        height: 230px;
        width: 100%;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
      }

      /* --- CARD BODY --- */
      .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 15px;
      }

      /* --- PRICE --- */
      .price {
        font-size: 24px;
        font-weight: bold;
        color: black;
        margin-top: auto;
      }

      /* --- BUTTON --- */
      .card-body .btn {
        width: 100%;
        margin-top: 10px;
      }

      /* --- SCROLL BUTTON --- */
      .scroll-up {
        display: none;
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: #00a676;
        color: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        font-size: 20px;
        z-index: 999;
      }

  </style>
</head>
<body>

<?php include("frontend/header.php"); ?>

   <div class="destination-hero-head" style="background-image: url('<?php echo htmlspecialchars($background_url); ?>');">
        <span class="destination-header-title">
            <h1><?php echo htmlspecialchars($destination); ?> </h1>
        </span>
    </div>

    <div class="features">
        <div class="container text-left py-1">
            <h1 class="mt-4"><?php echo htmlspecialchars($destination); ?></h1>
            <p style="font-size: 1.5rem;"><?php echo htmlspecialchars($description); ?></p>
        </div>
    </div>
    <div class="features">
        <div class="container text-left py-1">
            <h1 class="mt-4">Popular trips in <?php echo htmlspecialchars($destination); ?></h1>
        </div>
</div>

<div class="features">
  <div class="container text-center py-3 card-container" id="card-container">
    <?php if ($trip_result->num_rows > 0) {
      while ($trip = $trip_result->fetch_assoc()) { ?>
        <div class="card">
          <div class="position-relative">
            <div class="carousel">
              <a href="view-trip?tripid=<?php echo htmlspecialchars($trip['tripid']); ?>">
                <img src="<?php echo htmlspecialchars($trip['main_image']); ?>" class="img-fluid slide active" alt="<?php echo htmlspecialchars($trip['title']); ?>">
              </a>
            </div>
          </div>
          <div class="card-body text-start">
            <h5><?php echo htmlspecialchars($trip['title']); ?></h5>
            <p class="mb-1"><i class="fas fa-map-marker-alt text-success"></i> <?php echo htmlspecialchars($trip['location']); ?></p>
            <p class="mb-1"><i class="fas fa-clock text-success"></i> <?php echo htmlspecialchars($trip['duration']); ?></p>
            <p class="mb-1"><i class="fas fa-users text-success"></i> <?php echo htmlspecialchars($trip['groupsize']); ?> People</p>
            <p class="mb-1"><i class="fas fa-route text-success"></i> <?php echo htmlspecialchars($trip['triptype']); ?></p>
            <div class="price fw-bold mt-2">$<?php echo number_format($trip['price']); ?></div>
            <a class="btn btn-warning w-100 mt-2" href="view-trip?tripid=<?php echo htmlspecialchars($trip['tripid']); ?>">View Details</a>
          </div>
        </div>
    <?php }
    } else {
      echo "<p>No trips found for this destination.</p>";
    } ?>
  </div>
</div>

<?php include("frontend/footer.php"); ?>

<div class="scroll-up" id="scrollUpButton" onclick="scrollToTop()">
  <i class="fas fa-chevron-up"></i>
</div>

<script>
window.onscroll = function() {
  var btn = document.getElementById("scrollUpButton");
  btn.style.display = (window.scrollY > 100) ? "flex" : "none";
};

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
