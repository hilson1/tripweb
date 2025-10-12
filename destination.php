<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Destinations | ThankYouNepalTrip</title>

  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="index.css">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
    }

    .hero {
      background: url('assets/img/1.jpg') no-repeat center center/cover;
      color: white;
      text-align: center;
      padding: 150px 20px;
    }

    .hero h1 {
      font-size: 3.5rem;
      font-family: 'Pacifico', cursive;
      font-weight: bold;
    }

    .hero p {
      font-size: 1.5rem;
    }

    .destination-section {
      padding: 60px 15px;
      text-align: center;
    }

    .destination-section h1 {
      font-size: 2.5rem;
      color: #17252a;
      margin-bottom: 20px;
      font-family: 'Pacifico', cursive;
    }

    .destination-section p {
      color: #000;
      font-size: 1.1rem;
      margin-bottom: 40px;
    }

    .destination-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.3s ease;
      height: 100%;
    }

    .destination-card:hover {
      transform: translateY(-8px);
    }

    .destination-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .destination-card-body {
      padding: 20px;
    }

    .destination-card-title {
      font-size: 1.5rem;
      color: #17252a;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .destination-card-text {
      color: #555;
      font-size: 1rem;
      margin-bottom: 15px;
      min-height: 70px;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
      .hero {
        padding: 100px 15px;
      }
      .hero h1 {
        font-size: 2.8rem;
      }
      .hero p {
        font-size: 1.2rem;
      }
    }

    @media (max-width: 768px) {
      .destination-card img {
        height: 180px;
      }
      .destination-card-title {
        font-size: 1.25rem;
      }
      .destination-card-text {
        font-size: 0.95rem;
      }
    }

    @media (max-width: 576px) {
      .hero {
        padding: 80px 10px;
      }
      .hero h1 {
        font-size: 2rem;
      }
      .hero p {
        font-size: 1rem;
      }
      .destination-section {
        padding: 40px 10px;
      }
      .destination-section h1 {
        font-size: 2rem;
      }
      .destination-section p {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>

<?php include("frontend/header.php"); ?>
<?php include("connection.php"); ?>

<header class="hero">
  <h1>DESTINATIONS</h1>
  <p>Discover the most beautiful places in Nepal</p>
</header>

<div class="destination-section">
<?php
if (isset($_GET['destination-is'])) {
    $destination = $_GET['destination-is'];
    $stmt = $conn->prepare("SELECT distination, description, main_image FROM destinations WHERE distination = ?");
    $stmt->bind_param("s", $destination);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $destinationName = htmlspecialchars($row['distination']);
        $description = nl2br(htmlspecialchars($row['description']));
        $image = htmlspecialchars($row['main_image']);
        ?>
        <div class="container">
            <h1><?php echo $destinationName; ?></h1>
            <img src="<?php echo $image; ?>" class="img-fluid rounded mb-4" alt="<?php echo $destinationName; ?>">
            <p><?php echo $description; ?></p>
            <a href="destinations.php" class="btn btn-outline-primary mt-3">← Back to all destinations</a>
        </div>
        <?php
    } else {
        echo "<p>Destination not found.</p>";
    }
    $stmt->close();
} else {
?>
  <h1>Explore by Destinations</h1>
  <p>Discover the best travel destinations in Nepal.</p>

  <div class="container text-center py-5">
    <div class="row g-4">
      <?php
      $sql = "SELECT distination, description, main_image FROM destinations ORDER BY distination ASC";
      $result_destinations = $conn->query($sql);

      if ($result_destinations && $result_destinations->num_rows > 0) {
          while ($destination = $result_destinations->fetch_assoc()) {
              $name = htmlspecialchars($destination['distination']);
              $description = htmlspecialchars($destination['description']);
              $image = htmlspecialchars($destination['main_image']);

              $words = explode(" ", $description);
              $shortDesc = implode(" ", array_slice($words, 0, 20));
              if (count($words) > 20) $shortDesc .= "...";
      ?>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="destination-card h-100">
            <a href="destinations.php?destination-is=<?php echo htmlspecialchars(urlencode($name)); ?>" style="text-decoration:none; color:inherit;">
              <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>">
              <div class="destination-card-body">
                <h3 class="destination-card-title"><?php echo $name; ?></h3>
                <p class="destination-card-text"><?php echo $shortDesc; ?></p>
                <p class="fw-semibold" style="color: #3aafa9;">Learn more</p>
              </div>
            </a>
          </div>
        </div>
      <?php
          }
      } else {
          echo "<p class='text-center'>No popular destinations found.</p>";
      }
      ?>
    </div>
  </div>
<?php
}
$conn->close();
?>
</div>

<?php include("frontend/footer.php"); ?>
<?php include("frontend/scrollup.html"); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
