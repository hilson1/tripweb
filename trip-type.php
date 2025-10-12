<?php
include("frontend/session_start.php");
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Types | ThankYouNepalTrip</title>

    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        .hero {
            background: url('assets/img/tiger.jpg') no-repeat center center/cover;
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

        .triptype-section {
            padding: 60px 15px;
            text-align: center;
        }

        .triptype-section h1 {
            font-size: 2.5rem;
            color: #17252a;
            margin-bottom: 20px;
            font-family: 'Pacifico', cursive;
        }

        .triptype-section p {
            color: #000000ff;
            font-size: 1.1rem;
            margin-bottom: 40px;
        }

        /* Card styles same as destination/activity */
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
            min-height: 70px;
        }

        .trip-card a {
            color: #3aafa9;
            text-decoration: none;
            font-weight: bold;
        }

        .trip-card a:hover {
            text-decoration: underline;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>
</head>

<body>

<?php include("frontend/header.php"); ?>

<header class="hero">
    <h1>TRIP TYPES</h1>
    <p>Choose your adventure style and explore Nepal your way</p>
</header>

<div class="triptype-section">
    <?php
    // ✅ Single trip type view
    if (isset($_GET['triptype-is'])) {
        $triptype = $_GET['triptype-is'];
        $stmt = $conn->prepare("SELECT triptype, description, main_image FROM triptypes WHERE triptype = ?");
        $stmt->bind_param("s", $triptype);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $tripName = htmlspecialchars($row['triptype']);
            $description = nl2br(htmlspecialchars($row['description']));
            $image = htmlspecialchars($row['main_image']);
    ?>
            <div class="container">
                <h1><?php echo $tripName; ?></h1>
                <img src="<?php echo $image; ?>" class="img-fluid rounded mb-4" alt="<?php echo $tripName; ?>">
                <p><?php echo $description; ?></p>
                <a href="trip-types.php" class="btn btn-outline-primary mt-3">← Back to all trip types</a>
            </div>
    <?php
        } else {
            echo "<p>Trip type not found.</p>";
        }
        $stmt->close();
    } else {
        // ✅ All trip types view
    ?>
        <h1>Explore by Trip Types</h1>
        <p>Discover the best travel experiences tailored to your interests.</p>

        <div class="features" style="margin-top:-50px;">
            <div class="container text-center py-5 card-container">
                <div class="row g-4">
                    <?php
                    $sql = "SELECT triptype, description, main_image FROM triptypes ORDER BY triptype ASC";
                    $result_triptypes = $conn->query($sql);

                    if ($result_triptypes && $result_triptypes->num_rows > 0) {
                        while ($trip = $result_triptypes->fetch_assoc()) {
                            ?>
                            <div class="col-md-4">
                                <div class="trip-card">
                                    <a href="trip-types.php?triptype-is=<?php echo htmlspecialchars(urlencode($trip['triptype'])); ?>" style="text-decoration:none; color:inherit;">
                                        <img src="<?php echo htmlspecialchars($trip['main_image']); ?>" alt="<?php echo htmlspecialchars($trip['triptype']); ?>">
                                        <div class="trip-card-body">
                                            <h3 class="trip-card-title"><?php echo htmlspecialchars($trip['triptype']); ?></h3>
                                            <p class="trip-card-text">
                                                <?php
                                                $description = htmlspecialchars($trip['description']);
                                                $words = explode(" ", $description);
                                                $firstWords = implode(" ", array_slice($words, 0, 20));
                                                echo $firstWords . (count($words) > 20 ? '...' : '');
                                                ?>
                                            </p>
                                        </div>
                                         <a href="destinations.php?destination-is=<?php echo urlencode($name); ?>" 
                                            class="fw-semibold text-decoration-none" style="color: #3aafa9;">
                                            Learn more
                                            </a>
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p class='text-center'>No trip types found.</p>";
                    }
                    ?>
                </div>
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
