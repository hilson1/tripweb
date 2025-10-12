<?php
include("frontend/session_start.php");
include("connection.php");

// Fetch up to 6 trips data
$stmt_trips = $conn->prepare("SELECT trips.*, trip_images.main_image FROM trips INNER JOIN trip_images ON trips.tripid = trip_images.tripid LIMIT 6");
$stmt_trips->execute();
$trip_result = $stmt_trips->get_result();

// Fetch all activities for the search dropdown
$stmt_activities = $conn->prepare("SELECT DISTINCT activity FROM trips");
$stmt_activities->execute();
$activities_result = $stmt_activities->get_result();

// Fetch all destinations for the search dropdown
$stmt_destinations_search = $conn->prepare("SELECT DISTINCT location FROM trips");
$stmt_destinations_search->execute();
$destinations_search_result = $stmt_destinations_search->get_result();

// Corrected query: Fetch destinations using 'distination' for ordering
$sql_destinations = "SELECT * FROM destinations ORDER BY distination ASC LIMIT 6";
$result_destinations = $conn->query($sql_destinations);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | ThankYouNepalTrip</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css" />
</head>

<body>
    <?php
    include("frontend/header.php");
    ?>
    <!-- Hero section -->
    <section class="hero-section">
        <div class="bg" style="margin-top: 70px;">
            <div class="content">
                <h1>Escape Your Comfort Zone.</h1>
                <p>Grab your stuff and let’s get lost.</p>
            </div>
            <div class="search-box">
                <form action="search_results.php" method="GET">
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-walking" id="search-icon"></i></span>
                        <select class="form-select" aria-label="Activity" name="activity">
                            <option selected>Activity</option>
                            <?php
                            if ($activities_result->num_rows > 0) {
                                while ($row = $activities_result->fetch_assoc()) {
                                    echo "<option value='{$row['activity']}'>{$row['activity']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-dollar-sign" id="search-icon"></i></span>
                        <input type="number" class="form-control" placeholder="Max Price" name="max_price" id="max_price_input">
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt" id="search-icon"></i></span>
                        <select class="form-select" aria-label="Destination" name="destination">
                            <option selected>Destination</option>
                            <?php
                            if ($destinations_search_result->num_rows > 0) {
                                while ($row = $destinations_search_result->fetch_assoc()) {
                                    echo "<option value='{$row['location']}'>{$row['location']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-clock" id="search-icon"></i></span>
                        <select class="form-select" aria-label="Duration" name="duration">
                            <option selected>0 Days - 11 Days</option>
                            <option value="1-3">1 Day - 3 Days</option>
                            <option value="4-7">4 Days - 7 Days</option>
                            <option value="8-11">8 Days - 11 Days</option>
                        </select>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt" id="search-icon"></i></span>
                        <input type="date" class="form-control" aria-label="Date" name="date">
                    </div>
                    <button type="submit" name="search" class="btn btn-warning w-100">Search</button>
                </form>
            </div>
        </div>
    </section>

    <!-- description  -->
    <div class="features">
        <div class="container text-center py-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-map-marker-alt fa-2x" style="color: #00bcd4;"></i>
                    </div>
                    <div class="feature-title">Handpicked Destination</div>
                    <div class="feature-description">Our strict screening process means you're only seeing the best quality
                        treks.</div>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-tags fa-2x" style="color: #00bcd4;"></i>
                    </div>
                    <div class="feature-title">Best Price Guaranteed</div>
                    <div class="feature-description">Our Best Price Guarantee means that you can be sure of booking at the
                        best rate.</div>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-headset fa-2x" style="color: #00bcd4;"></i>
                    </div>
                    <div class="feature-title">24/7 Customer Service</div>
                    <div class="feature-description">Our customer are standing by 24/7 to make your experience incredible.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- popular trips -->
   <div class="features">
        <div class="container text-center py-5">
            <h1>Explore popular trips</h1>
            <p>Get started with handpicked top-rated trips designed to inspire your next adventure.</p>
            <div class="divider"></div>
        </div>

        <div class="features" style="margin-top:-50px;">
            <div class="container text-center py-5">
            <div class="row g-4 justify-content-center">
                <?php
                if ($trip_result && $trip_result->num_rows > 0) {
                while ($trip = $trip_result->fetch_assoc()) {
                    $tripid = htmlspecialchars($trip['tripid']);
                    $title = htmlspecialchars($trip['title']);
                    $location = htmlspecialchars($trip['location']);
                    $duration = htmlspecialchars($trip['duration']);
                    $groupsize = htmlspecialchars($trip['groupsize']);
                    $price = "$" . number_format($trip['price']);
                    $image = htmlspecialchars($trip['main_image']);
                    $description = htmlspecialchars($trip['description']);

                    $desc_words = explode(" ", $description);
                    $shortDesc = implode(" ", array_slice($desc_words, 0, 20));
                    if (count($desc_words) > 20) $shortDesc .= "...";
                ?>
                <div class="col-md-4 d-flex align-items-stretch">
                    <div class="card border-0 shadow h-100 rounded-3 overflow-hidden transition-all"
                    style="transition: transform 0.3s ease;">
                    <a href="view-trip?tripid=<?php echo $tripid; ?>" class="text-decoration-none text-dark">
                        <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>"
                        class="card-img-top w-100"
                        style="height: 220px; object-fit: cover; cursor: pointer;">
                    </a>
                    <div class="card-body text-start d-flex flex-column p-4">
                        <h5 class="fw-bold text-dark mb-2" style="font-size: 1.4rem;">
                        <?php echo $title; ?>
                        </h5>
                        <p class="text-muted flex-grow-1 mb-3" style="min-height: 70px;"><?php echo $shortDesc; ?></p>
                        <div class="mb-3">
                        <p class="mb-1"><i class="fas fa-map-marker-alt text-success me-2"></i><?php echo $location; ?></p>
                        <p class="mb-1"><i class="fas fa-clock text-success me-2"></i><?php echo $duration; ?></p>
                        <p class="mb-1"><i class="fas fa-users text-success me-2"></i><?php echo $groupsize; ?> People</p>
                        </div>
                        <div class="d-flex flex-column align-items-start">
                            <span class="fw-bold text-dark mb-2" style="font-size: 1.3rem;"><?php echo $price; ?></span>
                            <a class="btn btn-warning w-100" 
                                href="view-trip.php?tripid=<?php echo htmlspecialchars($trip['tripid']); ?>">
                                View Details
                            </a>
                        </div>

                    </div>
                    </div>
                </div>
                <?php
                }
                } else {
                echo "<p class='text-center'>No popular trips found.</p>";
                }
                ?>
            </div>
            </div>
        </div>

        <div class="features" style="margin-top:-50px;">
            <div class="container text-center py-5 card-container">
            <div class="container-custom" style="margin:0 auto;">
                <a href="trips" class="btn btn-custom">VIEW ALL TRIPS</a>
            </div>
            </div>
        </div>
    </div>




    <!-- popular destinations -->
    <div class="features">
        <div class="container text-center py-5 ">
            <h1>Explore popular destinations</h1>
            <p>A new journey begins here. Find a destination that suits you and start traveling. We offer the best travel packages.</p>
            <div class="divider"></div>
        </div>

        <div class="features" style="margin-top:-50px;">
        <div class="container text-center py-5">
            <div class="row g-4 justify-content-center">
            <?php
            if ($result_destinations && $result_destinations->num_rows > 0) {
                while ($destination = $result_destinations->fetch_assoc()) {
                $name = htmlspecialchars($destination['distination']);
                $image = htmlspecialchars($destination['main_image']);
                $description = htmlspecialchars($destination['description']);

                // limit description to 20 words
                $words = explode(" ", $description);
                $shortDesc = implode(" ", array_slice($words, 0, 20));
                if (count($words) > 20) $shortDesc .= "...";
            ?>
                <div class="col-md-4 d-flex align-items-stretch">
                    <div class="card border-0 shadow h-100 rounded-3 overflow-hidden transition-all" 
                        style="transition: transform 0.3s ease;">
                    <a href="destinations.php?destination-is=<?php echo urlencode($name); ?>" 
                        class="text-decoration-none text-dark">
                        <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>"
                            class="card-img-top w-100" 
                            style="height: 220px; object-fit: cover; cursor: pointer;">
                    </a>
                    <div class="card-body text-start d-flex flex-column p-4">
                        <h5 class="fw-bold text-dark mb-2" style="font-size: 1.5rem;"><?php echo $name; ?></h5>
                        <p class="text-muted flex-grow-1 mb-3" style="min-height: 70px;"><?php echo $shortDesc; ?></p>
                        <a href="destinations.php?destination-is=<?php echo urlencode($name); ?>" 
                        class="fw-semibold text-decoration-none" style="color: #3aafa9;">
                        Learn more
                        </a>
                    </div>
                    </div>
                </div>
                <script>
                    document.querySelectorAll('.card').forEach(card => {
                    card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-8px)');
                    card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
                    });
                </script>
            <?php
                }
            } else {
                echo "<p class='text-center'>No popular destinations found.</p>";
            }
            ?>
            </div>
        </div>
        </div>

        <div class="features" style="margin-top:-50px;">
            <div class="container text-center py-5 card-container">
                <div class="container-custom" style="margin:0 auto;">
                    <a href="destination" class="btn btn-custom">VIEW ALL DESTINATIONS</a>
                </div>
            </div>
        </div>
    </div>

    <!-- popular activities -->
     <div class="features">
        <div class="container text-center py-3">
            <h1>Explore exciting activities</h1>
            <p>A new adventure begins here. Find an activity that suits you and start exploring. We offer the best adventure packages.</p>
            <div class="divider"></div>
        </div>

        <div class="container text-center py-5">
        <div class="row g-4 justify-content-center">
            <?php
            $stmt_activities = $conn->prepare("SELECT * FROM activities");
            $stmt_activities->execute();
            $result_activities = $stmt_activities->get_result();

            if ($result_activities && $result_activities->num_rows > 0) {
            while ($activity = $result_activities->fetch_assoc()) {
                $name = htmlspecialchars($activity['activity']);
                $image = htmlspecialchars($activity['main_image']);
                $description = htmlspecialchars($activity['description']);

                $words = explode(" ", $description);
                $shortDesc = implode(" ", array_slice($words, 0, 20));
                if (count($words) > 20) $shortDesc .= "...";
            ?>
                <div class="col-md-4 d-flex align-items-stretch">
                <div class="card border-0 shadow h-100 rounded-3 overflow-hidden transition-all"
                    style="transition: transform 0.3s ease;">
                    <div class="position-relative">
                    <a href="activities.php?activity-is=<?php echo htmlspecialchars(urlencode($activity['activity'])); ?>" class="stretched-link">
                        <img src="<?php echo $image; ?>" 
                            alt="<?php echo $name; ?>" 
                            class="card-img-top w-100"
                            style="height: 220px; object-fit: cover;">
                    </a>
                    </div>
                    <div class="card-body text-start d-flex flex-column p-4">
                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.5rem;"><?php echo $name; ?></h5>
                    <p class="text-muted flex-grow-1 mb-3" style="min-height: 70px;"><?php echo $shortDesc; ?></p>
                    <a href="activities.php?activity-is=<?php echo htmlspecialchars(urlencode($activity['activity'])); ?>" 
                        class="fw-semibold text-decoration-none" style="color: #3aafa9;">
                        Learn More
                    </a>
                    </div>
                </div>
                </div>
            <?php
            }
            } else {
            echo "<p class='text-center'>No exciting activities found.</p>";
            }
            $stmt_activities->close();
            ?>
        </div>
        </div>

        <script>
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-8px)');
            card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
        });
        </script>

        <div class="features" style="margin-top:-50px;">
            <div class="container text-center py-5 card-container">
                <div class="container-custom" style="margin:0 auto;">
                    <a href="activity" class="btn btn-custom">VIEW ALL ACTIVITIES</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Triptypes -->
    <div class="features">
        <div class="container text-center py-3">
            <h1>Discover unique trip types</h1>
            <p>Choose the perfect trip type for your next journey. From relaxing getaways to thrilling adventures, we’ve got you covered.</p>
            <div class="divider"></div>
        </div>

        <div class="container text-center py-5">
            <div class="row g-4 justify-content-center">
            <?php
            $stmt_triptypes = $conn->prepare("SELECT * FROM triptypes");
            $stmt_triptypes->execute();
            $result_triptypes = $stmt_triptypes->get_result();

            if ($result_triptypes && $result_triptypes->num_rows > 0) {
                while ($triptype = $result_triptypes->fetch_assoc()) {
                $name = htmlspecialchars($triptype['triptype']);
                $image = htmlspecialchars($triptype['main_image']);
                $description = htmlspecialchars($triptype['description']);

                $words = explode(" ", $description);
                $shortDesc = implode(" ", array_slice($words, 0, 20));
                if (count($words) > 20) $shortDesc .= "...";
            ?>
                <div class="col-md-4 d-flex align-items-stretch">
                    <div class="card border-0 shadow h-100 rounded-3 overflow-hidden transition-all"
                        style="transition: transform 0.3s ease;">
                    <div class="position-relative">
                        <a href="trip-types.php?triptype-is=<?php echo htmlspecialchars(urlencode($triptype['triptype'])); ?>" class="stretched-link">
                        <img src="<?php echo $image; ?>" 
                            alt="<?php echo $name; ?>" 
                            class="card-img-top w-100"
                            style="height: 220px; object-fit: cover;">
                        </a>
                    </div>
                    <div class="card-body text-start d-flex flex-column p-4">
                        <h5 class="fw-bold text-dark mb-2" style="font-size: 1.5rem;"><?php echo $name; ?></h5>
                        <p class="text-muted flex-grow-1 mb-3" style="min-height: 70px;"><?php echo $shortDesc; ?></p>
                        <a href="trip-types.php?triptype-is=<?php echo htmlspecialchars(urlencode($triptype['triptype'])); ?>" 
                        class="fw-semibold text-decoration-none" style="color: #3aafa9;">
                        Learn More
                        </a>
                    </div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>No trip types available.</p>";
            }
            $stmt_triptypes->close();
            ?>
            </div>
        </div>

        <script>
            document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-8px)');
            card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
            });
        </script>

        <div class="features" style="margin-top:-50px;">
            <div class="container text-center py-5 card-container">
            <div class="container-custom" style="margin:0 auto;">
                <a href="triptype" class="btn btn-custom">VIEW ALL TRIP TYPES</a>
            </div>
            </div>
        </div>
    </div>

    <!-- testimonials -->
    <div class="features">
        <div class="container text-center py-5">
            <h1>Client Reviews</h1>
            <p>Get started with Reviews</p>
            <div class="divider"></div>
        </div>
 
        <div class="features" style="margin-top:-40px;">
            <div class="container text-center py-5 card-container">
                <div class="carousel-testi">
                    <div class="reviews" id="reviews">
                        <div class="review" alt="1">
                            <div class="container-testi" id="review-container">
                                <div class="image-container">
                                    <img src="assets/img/client.jpeg"
                                        alt="A young woman with a backpack and headphones, ready for travel">
                                </div>
                                <div class="text-container">
                                    <h2 class="title">Get Ahead in Travel with Booking</h2>
                                    <p class="description">Inquietude simplicity terminated she compliment remarkably few her nay.
                                        The weeks are ham asked jokes. Neglected perceived shy nay concluded.</p>
                                    <p class="author">Selvetica Forez</p>
                                </div>
                            </div>
                        </div>
                        <div class="review"  alt="2">
                            <div class="container-testi" id="review-container">
                                <div class="image-container">
                                    <img src="assets/img/client.jpeg"
                                        alt="A young woman with a backpack and headphones, ready for travel">
                                </div>
                                <div class="text-container">                
                                    <h2 class="title">Get Ahead in Travel with Booking</h2>
                                    <p class="description">Inquietude simplicity terminated she compliment remarkably few her nay.
                                        The weeks are ham asked jokes. Neglected perceived shy nay concluded.</p>
                                    <p class="author">Selvetica Forez</p>
                                </div>
                            </div>
                        </div>
                        <div class="review" alt="3">
                            <div class="container-testi" id="review-container">
                                <div class="image-container">
                                    <img src="assets/img/client.jpeg"
                                        alt="A young woman with a backpack and headphones, ready for travel">
                                </div>
                                <div class="text-container">
                                    <h2 class="title">Get Ahead in Travel with Booking</h2>
                                    <p class="description">Inquietude simplicity terminated she compliment remarkably few her nay.
                                        The weeks are ham asked jokes. Neglected perceived shy nay concluded.</p>
                                    <p class="author">Selvetica Forez</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="buttons">
                        <button onclick="prevReview()">&#10094; Prev</button>
                        <button onclick="nextReview()">Next &#10095;</button>
                    </div>
                </div>
                <script>
                    let div = document.getElementById("review-container");
                    let width = div.offsetWidth;
                    let index = 0;
                    const reviews = document.getElementById("reviews");
                    const totalReviews = document.querySelectorAll(".review").length;

                    function showReview() {
                        reviews.style.transition = "transform 0.5s ease-in-out";
                        reviews.style.transform = `translateX(${-index * width}px)`;
                    }

                    function nextReview() {
                        if (index >= totalReviews - 1) {
                            index = 0;
                            reviews.style.transition = "none";
                            reviews.style.transform = `translateX(0px)`;
                            setTimeout(() => {
                                reviews.style.transition = "transform 0.5s ease-in-out";
                            }, 50);
                        } else {
                            index++;
                        }
                        showReview();
                    }

                    function prevReview() {
                        if (index <= 0) {
                            index = totalReviews - 1;
                            reviews.style.transition = "none";
                            reviews.style.transform = `translateX(${-index * width}px)`;
                            setTimeout(() => {
                                reviews.style.transition = "transform 0.5s ease-in-out";
                            }, 50);
                        } else {
                            index--;
                        }
                        showReview();
                    }

                    function autoSlide() {
                        nextReview();
                    }
                </script>
            </div>
        </div>
    </div>

    <!-- footer -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>