<?php
include("frontend/session_start.php");
?>
<?php
require 'connection.php'; // Include your database connection file

// Assume we are fetching the 'description' column based on a 'destination' value
if (isset($_GET['activity-is'])) {
    $activity = $_GET['activity-is'];

    // Prepare the SQL query using a prepared statement to avoid SQL injection
    $sql = "SELECT description FROM activities WHERE activity = ?";
    $stmt = $conn->prepare($sql);

    // Bind the destination parameter
    $stmt->bind_param("s", $activity);

    // Execute the query
    $stmt->execute();

    // Bind the result to a variable
    $stmt->bind_result($description);

    // Fetch the result and store it in the variable
    if ($stmt->fetch()) {
        // Now $description contains the value fetched from the database
    } else {
        $description = "No description found for the activity.";
    }

    // Close the statement
    $stmt->close();
} else {
    $description = "No activity specified.";
}
// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">
    <style>
        .destination-hero-head {
            display: flex;
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
            position: relative;
            /* Position relative to allow absolute positioning of child elements if needed */
            height: 350px;
            /* Set height as needed */
        }

        .destination-header-title {
            position: absolute;
            /* Position absolute to allow centering */
            text-align: left;
            color: white;
            /* Center text inside the span */
        }

        .destination-header-title h1 {
            font-size: 4em;
            /* Adjust the size as needed */
            color: white;
            font-weight: 600;
            /* Optional: Change text color for better visibility */
            text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5);

        }

        .background-destination-img {
            width: 100vw;
            height: 350px;
            overflow: hidden;
        }


        .feature-icon {
            background-color: #e0f7fa;
            border-radius: 50%;
            padding: 20px;
            margin-bottom: 20px;
        }

        .feature-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .feature-description {
            color: #6c757d;
        }

        /* explore trip */
        .section-title {
            text-align: center;
            padding: 50px 0;
        }

        .section-title h2 {
            font-family: "Pacifico", cursive;
            color: #00a676;
            font-size: 24px;
        }

        .section-title h1 {
            font-weight: 700;
            font-size: 36px;
            color: #000;
        }

        .section-title p {
            color: #666;
            font-size: 16px;
        }

        .section-title .divider {
            margin: 20px auto;
            width: 50px;
            height: 2px;
            background-color: #f4a261;
        }

        /* card */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            background-color: none;
        }

        .card {
            border: none;
            border-radius: 10px;
            max-width: 400px;
            max-height: fit-content;

            margin: auto;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        }

        .price {
            font-size: 24px;
            font-weight: bold;
            color: black;

        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
        }

        .btn-view-details {
            background-color: #fd7e14;
            color: #fff;
            font-weight: bold;
            border-radius: 5px;
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
        }


        .carousel {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 5px 5px 0px 0px;

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

        #card-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            /* 3 columns */
            gap: 20px;
            /* Spacing between grid items */
            background-color: #f4f4f4;
        }

        /* view more trips */
        .btn-custom {
            border: 1px solid #F4A261;
            color: #F4A261;
            background-color: transparent;
            padding: 10px 20px;
            font-size: 14px;
        }

        .btn-custom:hover {
            background-color: #F4A261;
            color: white;
        }



        @media (max-width: 768px) {
            .card-container {
                grid-template-columns: repeat(1, 1fr);
                /* 1 column for small screens */
            }

            .trips-section {
                padding: none;
                align-items: center;
            }

            #card-container {
                grid-template-columns: 1fr;
            }
        }

        .card-contents {
            text-align: left;
        }

        .trip-card {
            background-color: #fff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .trip-card:hover {
            transform: translateY(-10px);
        }

        .trip-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .trip-card-body {
            padding: 20px;
        }

        .trip-card-title {
            font-size: 1.5rem;
            color: #17252a;
            margin-bottom: 10px;
        }

        .trip-card-text {
            color: #555;
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .trip-card a {
            color: #3aafa9;
            text-decoration: none;
            font-weight: bold;
        }

        .trip-card a:hover {
            text-decoration: underline;
        }

        /* testimonials */
        .carousel-testi {
            margin: 0 auto;
            width: 1200px;
            overflow: hidden;
            position: relative;
            background: white;
            border-radius: 5px;
            text-align: center;
        }

        .reviews {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: max-content;
        }

        .review {
            min-width: 1200px;
        }

        .buttons {
            margin-top: 10px;
        }

        .buttons button {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            background: rgb(27, 184, 74);
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        .buttons button:hover {
            background: rgb(1, 119, 17);
        }

        .container-testi {
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .image-container {
            position: relative;
        }

        .image-container img {
            border-radius: 50%;
            width: 128px;
            height: 128px;
            object-fit: cover;
        }


        .text-container {
            max-width: 800px;
        }

        .quote-icon {
            color: #38b2ac;
            font-size: 24px;

        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .description {
            color: #718096;
            margin-bottom: 16px;
        }

        .author {
            color: #2d3748;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .container-testi {
                display: flex;
                flex-direction: column;
                width: 350px;
            }

            .carousel-testi,
            .review {
                max-width: 350px;
            }
        }
    </style>
</head>

<body>
    <?php
    include("frontend/header.php");
    ?>
    <div class="destination-hero-head">
        <div class="destination-hero-img-container">
            <img src="assets/img/trekking.jpg" alt="" class="background-destination-img">
        </div>
        <span class="destination-header-title">
            <h1><?php echo $activity; ?> </h1>
        </span>
    </div>
    <div class="features">
        <div class="container text-left py-1">
            <h1 class="mt-4"><?php echo $activity; ?></h1>
            <p style="font-size: 1.5rem;"><?php echo htmlspecialchars($description); ?></p>
        </div>
    </div>
    <div class="features">
        <div class="container text-left py-1">
            <h1 class="mt-4">Popular trips related to <?php echo $activity; ?></h1>
        </div>
    </div>

    <div class="features">
        <div class="container text-center py-3 card-container" id="card-container"
            style="row-gap:20px; background-color:transparent;">
            <?php for ($i = 0; $i < 4; $i++) { ?>
                <div class="card" style=" flex: 0 0 calc(33.33% - 20px);">
                    <div class="position-relative">
                        <div class="carousel">
                            <div class="carousel-container">
                                <a href="view-tripll">
                                    <img src="assets/img/mustang.jpg" class="slide active">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-top">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center"
                                style="padding:10px 0px 10px 0px">
                                <a href="" style="text-decoration:none; color:black;"
                                    onmouseover="this.style.color='#008080'" onmouseout="this.style.color='black'">
                                    <h5 class=" card-title mb-0">
                                        Paris Effiel Tower Tour 1 Day Tour
                                    </h5>
                                </a>

                            </div>
                            <div class="me-3 card-contents"
                                style="padding:10px 0px 10px 0px; border-bottom:1px solid gray;">
                                <p class="mb-1">
                                    Travel is the movement of people between relatively distant geographical...
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class=" d-flex mb-3">
                            <div class="me-3 card-contents" style="padding-left:15px;">
                                <p class="mb-1">
                                    <i class="fas fa-map-marker-alt" style="color:green; margin-right:10px;">
                                    </i>
                                    France, India, Nepal, Srilanka
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-clock" style="color:green; margin-right:5px;">
                                    </i>
                                    5 Hours
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-users" style="color:green; margin-right:2px;">
                                    </i>
                                    1-10 People
                                </p>
                            </div>
                            <div class="me-3 card-contents">
                                <div class="price" style="margin-top:50%;">
                                    <h2><?php echo "$" . number_format(3000); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
    include("frontend/footer.php");
    ?>
    <div class="scroll-up" id="scrollUpButton" onclick="scrollToTop()">
        <i class="fas fa-chevron-up"></i>
    </div>
    <script>
        window.onscroll = function () {
            var scrollUpButton = document.getElementById("scrollUpButton");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                scrollUpButton.style.display = "flex";
            } else {
                scrollUpButton.style.display = "none";
            }
        };

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>