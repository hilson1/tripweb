<?php
include("/frontend/session_start.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>all-trip | ThankYouNepalTrip</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="assets/css/view-trip.css">
    <style>
        .hero {
            background: url('assets/img/pk.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 80px 20px;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
        }

        .hero p {
            font-size: 1.5rem;
        }
    </style>
</head>

<body>
    <?php
    include("frontend/header.php");
    ?>
    <header class="hero">
        <h1>Explore Available Trips</h1>
    </header>
    <div class="container text-left" style="margin-top: 40px;">
        <h1>Explore popular trips in Kathmandu</h1>
    </div>

    <div class="features">
        <div class="container text-center py-5 card-container" id="card-container"
            style="row-gap:20px; background-color:transparent;">
            <?php for ($i = 0; $i < 3; $i++) { ?>
                <div class="card" style=" flex: 0 0 calc(33.33% - 20px);">
                    <div class="position-relative">
                        <div class="carousel">
                            <div class="carousel-container">
                                <a href="">
                                    <img src="assets/img/mustang.jpg" class="slide active">
                                </a>
                            </div>
                        </div>
                        <span class="badge-featured">
                            Featured
                        </span>
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
                            <div class="me-3 card-contents" style="padding:10px 0px 10px 0px;">
                                <p class="mb-1">
                                    Travel is the movement of people between relatively distant geographical...
                                </p>
                            </div>
                            <div>
                                <div class=" d-flex mb-3">
                                    <div class="me-3 card-contents" style="width:60%;">
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
                                        <div class="price"
                                            style="margin-top:50%; border-left: 1px solid gray; padding-left:20px;">
                                            <h2><?php echo "$" . number_format(3000); ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="departure-detail"
                                style="display: flex; align-items:end; justify-content: space-between;">
                                <div class="me-3 card-contents" style="padding:10px 0px 10px 0px;">
                                    <h5>Next Departure: </h5>
                                    <div class="departure">
                                        <p class="mb-1"> <i class="fas fa-check" id="check-icon"></i>Jan 2025</p>
                                        <p class="mb-1"> <i class="fas fa-check" id="check-icon"></i>Jan 2025</p>
                                        <p class="mb-1"> <i class="fas fa-check" id="check-icon"></i>Jan 2025</p>
                                    </div>
                                </div>
                                <div class="me-3 card-contents" id="view-details-link" style="padding:10px 0px 10px 0px;">
                                    <a href="view-trip">VIEW DETAILS</a>
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