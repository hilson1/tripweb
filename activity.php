<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Activities | ThankYouNepalTrip</title>

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
      background: url('assets/img/desti.jpg') no-repeat center center/cover;
      color: white;
      text-align: center;
      padding: 150px 20px;
    }

    .hero h1 {
      font-size: 3.5rem;
      font-weight: bold;
      font-family: 'Pacifico', cursive;
    }

    .hero p {
      font-size: 1.5rem;
    }

    .activity-section {
      padding: 60px 15px;
      text-align: center;
    }

    .activity-section h1 {
      font-size: 2.5rem;
      color: #17252a;
      margin-bottom: 20px;
      font-family: 'Pacifico', cursive;
    }

    .activity-section p {
      color: #000;
      font-size: 1.1rem;
      margin-bottom: 40px;
    }

    .trip-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.3s ease;
      height: 100%;
    }

    .trip-card:hover {
      transform: translateY(-8px);
    }

    .trip-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .trip-card-body {
      padding: 20px;
    }

    .trip-card-title {
      font-size: 1.5rem;
      color: #17252a;
      margin-bottom: 10px;
      font-weight: 700;
    }

    .trip-card-text {
      color: #555;
      font-size: 1rem;
      margin-bottom: 15px;
      min-height: 60px;
    }

    .trip-card a {
      color: #3aafa9;
      text-decoration: none;
      font-weight: bold;
    }

    .trip-card a:hover {
      text-decoration: underline;
    }

    @media (max-width: 992px) {
      .hero {
        padding: 100px 15px;
      }
      .hero h1 {
        font-size: 2.5rem;
      }
      .hero p {
        font-size: 1.2rem;
      }
    }

    @media (max-width: 768px) {
      .trip-card img {
        height: 180px;
      }
      .trip-card-title {
        font-size: 1.25rem;
      }
      .trip-card-text {
        font-size: 0.95rem;
      }
    }

    @media (max-width: 576px) {
      .activity-section {
        padding: 40px 10px;
      }
      .activity-section h1 {
        font-size: 2rem;
      }
      .activity-section p {
        font-size: 1rem;
      }
      .hero {
        padding: 80px 10px;
      }
      .hero h1 {
        font-size: 2rem;
      }
      .hero p {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>
<?php include("frontend/header.php"); ?>
<?php include("connection.php"); ?>

<header class="hero">
  <h1>ACTIVITIES</h1>
  <p>Discover exciting adventures and experiences in Nepal</p>
</header>

<div class="activity-section">
  <?php
  if (isset($_GET['activity-is'])) {
      $activity = $_GET['activity-is'];
      $stmt = $conn->prepare("SELECT activity, description, main_image FROM activities WHERE activity = ?");
      $stmt->bind_param("s", $activity);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($row = $result->fetch_assoc()) {
          $activityName = htmlspecialchars($row['activity']);
          $description = nl2br(htmlspecialchars($row['description']));
          $image = htmlspecialchars($row['main_image']);
  ?>
      <div class="container">
          <h1><?php echo $activityName; ?></h1>
          <img src="<?php echo $image; ?>" class="img-fluid rounded mb-4" alt="<?php echo $activityName; ?>">
          <p><?php echo $description; ?></p>
          <a href="activities" class="btn btn-outline-primary mt-3">← Back to all activities</a>
      </div>
  <?php
      } else {
          echo "<p>Activity not found.</p>";
      }
      $stmt->close();
  } else {
  ?>
      <h1>Explore by Activities</h1>
      <p>Get started with handpicked top-rated activities in Nepal.</p>

      <div class="container text-center py-5">
          <div class="row g-4">
              <?php
              $sql = "SELECT activity, description, main_image FROM activities ORDER BY activity ASC";
              $result_activities = $conn->query($sql);

              if ($result_activities && $result_activities->num_rows > 0) {
                  while ($activity = $result_activities->fetch_assoc()) {
              ?>
                      <div class="col-12 col-sm-6 col-md-4">
                          <div class="trip-card h-100">
                              <a href="activities?activity-is=<?php echo htmlspecialchars(urlencode($activity['activity'])); ?>" style="text-decoration:none; color:inherit;">
                                  <img src="<?php echo htmlspecialchars($activity['main_image']); ?>" alt="<?php echo htmlspecialchars($activity['activity']); ?>">
                                  <div class="trip-card-body">
                                      <h3 class="trip-card-title"><?php echo htmlspecialchars($activity['activity']); ?></h3>
                                      <p class="trip-card-text">
                                          <?php
                                          $description = htmlspecialchars($activity['description']);
                                          $words = explode(" ", $description);
                                          $firstWords = implode(" ", array_slice($words, 0, 20));
                                          echo $firstWords . (count($words) > 20 ? '...' : '');
                                          ?>
                                      </p>
                                      <p class="fw-semibold" style="color:#3aafa9;">Learn more</p>
                                  </div>
                              </a>
                          </div>
                      </div>
              <?php
                  }
              } else {
                  echo "<p class='text-center'>No popular activities found.</p>";
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
