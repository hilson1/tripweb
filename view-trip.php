<?php
include("frontend/session_start.php");
require 'connection.php'; // Database connection

$trip = [];
$images = [];
$relatedTrips = [];

if (isset($_GET['tripid'])) {
    $tripid = intval($_GET['tripid']);

    // --- Fetch trip details ---
    $sql = "SELECT * FROM trip_details_view WHERE tripid = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $tripid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $trip = $result->fetch_assoc();
    } else {
        die("Trip not found.");
    }
    $stmt->close();

    // --- Fetch all trip images ---
    $img_sql = "SELECT main_image FROM trip_images WHERE tripid = ?";
    $stmt = $conn->prepare($img_sql);
    $stmt->bind_param("i", $tripid);
    $stmt->execute();
    $img_result = $stmt->get_result();
    while ($row = $img_result->fetch_assoc()) {
        $images[] = $row['main_image'];
    }
    $stmt->close();

    // --- Fetch related trips ---
    $activity = $trip['activity'];
    $location = $trip['location'];
    $sql_related = "SELECT tripid, title, main_image, location, duration, groupsize, price, description 
                    FROM trip_details_view 
                    WHERE (activity = ? OR location = ?) 
                    AND tripid != ? 
                    ORDER BY RAND() 
                    LIMIT 6";
    $stmt = $conn->prepare($sql_related);
    $stmt->bind_param("ssi", $activity, $location, $tripid);
    $stmt->execute();
    $related_result = $stmt->get_result();
    while ($row = $related_result->fetch_assoc()) {
        $relatedTrips[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Trip | ThankYouNepalTrip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="assets/css/view-trip.css">
</head>

<body>
    <?php
    include("frontend/header.php");
    ?>
    <div class="features">
        <div class="container py-5">

        <!-- Images containers -->
            <div class="trip-image-container">

                <!-- Product Images -->
                <div class="gallery"
                    style="display: flex; flex-wrap: wrap; align-items:center; justify-content: space-around;">
                    <div class="mainimg">
                        <img src="<?php echo $trip['main_image']; ?>" class="thumbnail" onclick="openModal(0)"
                            style="height: 400px; width: 600px;">
                    </div>
                    <div class="sideimg"
                        style="display: flex; justify-content: space-between; flex-direction:column; flex-wrap: wrap;">
                        <img src="<?php echo $trip['side_image1']; ?>" class="thumbnail" onclick="openModal(1)"
                            style="height: 200px; width: 400px;">
                        <img src="<?php echo $trip['side_image2']; ?>" class="thumbnail" onclick="openModal(2)"
                            style="height: 200px; width: 400px;">
                    </div>
                </div>
                <!-- Modal Structure -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                <button class="btn btn-dark" onclick="prevImage()">❮</button>
                                <img id="modal-img" src="" class="img-fluid">
                                <button class="btn btn-dark" onclick="nextImage()"> ❯</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    let images = ["<?php echo $trip['main_image']; ?>", "<?php echo $trip['side_image1']; ?>", "<?php echo $trip['side_image2']; ?>"];
                    let currentIndex = 0;

                    function openModal(index) {
                        currentIndex = index;
                        let modalImg = document.getElementById("modal-img");
                        modalImg.src = images[currentIndex];
                        let modal = new bootstrap.Modal(document.getElementById('imageModal'));
                        modal.show();
                    }

                    function prevImage() {
                        currentIndex = (currentIndex - 1 + images.length) % images.length;
                        document.getElementById("modal-img").src = images[currentIndex];
                    }

                    function nextImage() {
                        currentIndex = (currentIndex + 1) % images.length;
                        document.getElementById("modal-img").src = images[currentIndex];
                    }
                </script>
            </div>

            
            <div class="features">
                <div class="container py-5">
                    <div class="viewtrip-container" style="width: 100%; display: flex; justify-content: space-between; font-size: 1.2rem;">
                       
                        <!-- descriptions of trip -->
                        <div class="trip-info" style="width:68%">
                            <div class="trip-heading" style="display: flex;">
                                <h1>"<?php echo $trip['title']; ?>"</h1>
                                <div class="duration" style="text-align:center; display:flex; flex-direction:column;">
                                    <h1
                                        style="background-color:rgb(83, 192, 192); border-radius:5px 5px 0px 0px; color:white;">
                                        <?php echo $trip['duration']; ?>
                                    </h1>
                                    <h1 style="margin-top:-5px;">Days</h1>
                                </div>
                            </div>
                            <div class="trip-facts" id="menu">
                                <div class="flex items-center">
                                    <div><i class=" fas fa-bus text-teal-500 mr-2" id="fact-icon"></i>
                                    <span>Transportation</span>
                                </div>
                                <div class=""><span><?php echo $trip['transportation']; ?></span></div>
                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-hotel text-teal-500 mr-2"
                                        id="fact-icon"></i><span>Accomodation</span>
                                </div>
                                <div class=""><span><?php echo $trip['accomodation']; ?> </span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-mountain text-teal-500 mr-2" id="fact-icon"></i><span>Maximum
                                        Altitude</span>
                                </div>
                                <div class=""><span><?php echo $trip['maximumaltitude']; ?> metres</span></div>
                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-plane-departure text-teal-500 mr-2"
                                        id="fact-icon"></i><span>Departure
                                        from</span></div>
                                <div class=""><span><?php echo $trip['departurefrom']; ?></span></div>
                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-calendar-alt text-teal-500 mr-2" id="fact-icon"></i><span>Best
                                        season</span></div>
                                <div><span><?php echo $trip['bestseason']; ?></span></div>
                            </div>
                            <div class="flex items-center">
                                <div> <i class="fas fa-hiking text-teal-500 mr-2" id="fact-icon"></i><span>Tour
                                        type</span></div>

                                <div class=""><span><?php echo $trip['triptype']; ?></span></div>

                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-utensils text-teal-500 mr-2" id="fact-icon"></i><span>Meals</span>
                                </div>
                                <div class=""><span><?php echo $trip['meals']; ?></span></div>
                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-language text-teal-500 mr-2"
                                        id="fact-icon"></i><span>Language</span></div>
                                <div class=""><span><?php echo $trip['language']; ?></span></div>

                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-dumbbell text-teal-500 mr-2" id="fact-icon"></i> <span>Fitness
                                        level</span>
                                </div>
                                <div class=""><span><?php echo $trip['fitnesslevel']; ?></span></div>

                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-users text-teal-500 mr-2" id="fact-icon"></i><span>Group
                                        Size</span></div>
                                <div class=""><span><?php echo $trip['groupsize']; ?></span></div>

                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-child text-teal-500 mr-2" id="fact-icon"></i><span>Minimum
                                        Age</span></div>
                                <div class=""><span><?php echo $trip['minimumage']; ?></span></div>
                            </div>
                            <div class="flex items-center">
                                <div><i class="fas fa-user-alt text-teal-500 mr-2" id="fact-icon"></i><span>Maximum
                                        Age</span>
                                </div>
                                <div class=""><span><?php echo $trip['maximumage']; ?></span></div>
                            </div>
                            <div class="flex items-center">
                                <div>
                                    <i class="fas fa-hiking text-teal-500 mr-2" id="fact-icon"></i>
                                    <span>Activity</span>
                                </div>
                                <div class="">
                                    <span><?php echo htmlspecialchars($trip['activity'] ?? 'N/A'); ?></span>
                                </div>
                            </div>
                          <div class="flex items-center">
                                <div>
                                    <i class="fas fa-map-marker-alt text-teal-500 mr-2" id="fact-icon"></i>
                                    <span>Location</span>
                                </div>
                                <div class="">
                                    <span><?php echo htmlspecialchars($trip['location'] ?? 'N/A'); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- iteration menu -->
                        <div class="itinery-menu">
                            <a href="#menu" onclick="setActiveMenuItem(0)">Overview</a>
                            <a href="#overview" onclick="setActiveMenuItem(1)">Itinerary</a>
                            <a href="#itinerary" onclick="setActiveMenuItem(2)">Cost</a>
                            <a href="#cost" onclick="setActiveMenuItem(3)">FAQs</a>
                            <a href="#faqs" onclick="setActiveMenuItem(4)">Map</a>
                        </div>

                        <!-- overview and highlight of trip -->
                        <div class="overview" id="overview">
                            <!-- overview of trip -->
                            <!-- Overview Section -->
                            <div class="itinerary-header">
                            <span><h1>Overview</h1></span>
                            </div>
                            <p><?= nl2br(htmlspecialchars($trip['description'] ?? 'No overview available.')) ?></p>

                            <!-- Highlights Section -->
                            <div class="itinerary-header">
                            <span><h1>Highlights</h1></span>
                            </div>
                            <div class="highlite" style="margin:10px 0; padding-bottom:2rem; padding-left:10px;">
                                <?php
                                // Loop through all highlight_title columns
                                for ($i = 1; $i <= 6; $i++) {
                                    $key = "highlight_title{$i}";
                                    if (!empty($trip[$key])) {
                                        echo '<i class="fas fa-check" id="check-icon"></i> ';
                                        echo '<span>' . htmlspecialchars($trip[$key]) . '</span><br>';
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <!-- itinery of trip -->
                        <div class="itinerary-section" id="itinerary">
                            <div class="itinerary-header">
                                <span><h1>Itinerary</h1></span>
                            </div>      
                            <?php if (!empty($trip)): ?>
                                <?php for ($i = 1; $i <= 6; $i++):
                                $title = $trip["itinerary_title$i"] ?? '';
                                $desc  = $trip["itinerary_desc$i"] ?? '';
                                // normalize and trim
                                $title = trim((string)$title);
                                $desc  = trim((string)$desc);
                                if ($title !== ''): ?>
                                    <div class="day">
                                    <div class="day-header" onclick="toggleDay(this)">
                                        <span><strong><?= htmlspecialchars($title) ?></strong></span>
                                        <span class="icon">⌄</span>
                                    </div>

                                    <div class="day-content">
                                        <?php if ($desc !== ''): ?>
                                        <!-- preserve line breaks -->
                                        <p><?= nl2br(htmlspecialchars($desc)) ?></p>
                                        <?php else: ?>
                                        <p><em>No description available.</em></p>
                                        <?php endif; ?>
                                    </div>
                                    </div>
                                <?php endif; endfor; ?>
                            <?php else: ?>
                                <p>No itinerary data found for this trip.</p>
                            <?php endif; ?>
                        </div>

                        <!-- cost of trip -->
                        <div class="cost" id="cost">
                            <div class="itinerary-header">
                                <span><h1>Costs</h1></span>
                            </div>
                            <?php if (!empty($trip)): ?>

                                <h3><?= htmlspecialchars($trip['cost_includes'] ?? 'The Cost Includes') ?></h3>
                                <?php for ($i = 1; $i <= 6; $i++):
                                    $include = $trip["include_title$i"] ?? '';
                                    if (!empty($include)): ?>
                                        <i class="fas fa-check include-item"></i>
                                        <span class="include-item"><?= htmlspecialchars($include) ?></span><br>
                                <?php endif; endfor; ?>

                                <br><br>

                                <h3><?= htmlspecialchars($trip['cost_excludes'] ?? 'The Cost Excludes') ?></h3>
                                <?php for ($i = 1; $i <= 6; $i++):
                                    $exclude = $trip["exclude_title$i"] ?? '';
                                    if (!empty($exclude)): ?>
                                        <i class="fas fa-times exclude-item"></i>
                                        <span class="exclude-item"><?= htmlspecialchars($exclude) ?></span><br>
                                <?php endif; endfor; ?>

                            <?php else: ?>
                                <p>No cost data found for this trip.</p>
                            <?php endif; ?>
                        </div>

                        <div class="dates" id="dates"></div>

                        <!-- faqs for trip -->
                        <div class="faqs" id="faqs">
                        <div class="faq-header">
                            <h1>FAQ's</h1>
                        </div>

                        <?php
                        $tripid = $trip['tripid'] ?? null;

                        if ($tripid) {
                            $faqQuery = $conn->prepare("SELECT question, answer FROM trip_faqs WHERE tripid = ? ORDER BY faqid ASC");
                            $faqQuery->bind_param("i", $tripid);
                            $faqQuery->execute();
                            $faqs = $faqQuery->get_result();

                            if ($faqs->num_rows > 0): ?>
                                <?php while ($faq = $faqs->fetch_assoc()): ?>
                                <div class="day">
                                    <div class="day-header" onclick="toggleDay(this)">
                                    <span><strong><?= htmlspecialchars($faq['question']) ?></strong></span>
                                    <span class="icon">⌄</span>
                                    </div>
                                    <div class="day-content">
                                    <?php if (!empty(trim($faq['answer']))): ?>
                                        <p><?= nl2br(htmlspecialchars($faq['answer'])) ?></p>
                                    <?php else: ?>
                                        <p><em>No answer available.</em></p>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No FAQ data found for this trip.</p>
                            <?php endif;
                        } else {
                            echo "<p>Invalid trip selected.</p>";
                        }
                        ?>
                        </div>
                        <script>
                            function toggleDay(header) {
                            const day = header.parentElement;
                            day.classList.toggle('expanded');
                            }
                        </script>

                        <!-- map of trip -->
                        <div class="map" id="map">
                            <h1>Map</h1><br>
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.451214129912!2d85.32396061544552!3d27.717245982788245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1901cb67d5a1%3A0x6e8daaf9ed44a6a!2sKathmandu!5e0!3m2!1sen!2snp!4v1613561252925!5m2!1sen!2snp"
                                allowfullscreen="" loading="fast" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>


                        <!--  enquiry  -->
                    <div class="enquiry-form" id="enquiry-form">
                        <div class="itinerary-header">
                            <h1>Enquiry Form</h1>
                        </div>

                        <div class="enquiry-container">
                            <h2>You can send your enquiry via the form below.</h2>

                            <form id="enquiryForm" action="https://api.web3forms.com/submit" method="POST">
                            <input type="hidden" name="access_key" value="8789583e-fd9e-44e5-a6e8-e265ceec0848">

                            <!-- remove redirect -->
                            <!-- <input type="hidden" name="redirect" value="https://thankyounepaltrip.com/thank-you.html"> -->

                            <input type="hidden" name="email" value="info@thankyounepaltrip.com">

                            <label for="trip-name">Trip name: <span style="color: red;">*</span></label>
                            <input type="text" id="trip-name" name="trip-name" placeholder="Trip Name *"
                                required value="<?= htmlspecialchars($trip['title'] ?? '') ?>">

                            <label for="name">Your name: <span style="color: red;">*</span></label>
                            <input type="text" id="name" name="name" placeholder="Enter Your Name *" required>

                            <label for="email">Your email: <span style="color: red;">*</span></label>
                            <input type="email" id="email" name="email" placeholder="Enter Your Email *" required>

                            <div class="row">
                                <div class="col-half">
                                <label for="country">Country <span style="color: red;">*</span></label>
                                <select id="country" name="country" required>
                                    <option value="">Choose a country*</option>
                                    <option>India</option>
                                    <option>China</option>
                                    <option>United States of America</option>
                                    <option>United Kingdom</option>
                                    <option>Thailand</option>
                                    <option>South Korea</option>
                                    <option>Australia</option>
                                    <option>Germany</option>
                                    <option>France</option>
                                    <option>Japan</option>
                                    <option>Sri Lanka</option>
                                    <option>Bangladesh</option>
                                    <option>Malaysia</option>
                                    <option>Singapore</option>
                                    <option>Canada</option>
                                    <option>Netherlands</option>
                                    <option>Spain</option>
                                    <option>Italy</option>
                                    <option>Norway</option>
                                    <option>Switzerland</option>
                                    <option>Israel</option>
                                    <option>Denmark</option>
                                    <option>Austria</option>
                                    <option>New Zealand</option>
                                    <option>Russia</option>
                                    <option>Sweden</option>
                                    <option>Poland</option>
                                    <option>Brazil</option>
                                    <option>Philippines</option>
                                    <option>Vietnam</option>
                                </select>
                                </div>

                                <div class="col-half">
                                <label for="contact-number">Contact number: <span style="color: red;">*</span></label>
                                <input type="tel" id="contact-number" name="contact-number" placeholder="Enter Your Contact Number*" required pattern="[0-9+\s-]+">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-half">
                                <label for="adults">No. of Adults <span style="color: red;">*</span></label>
                                <input type="number" id="adults" name="adults" placeholder="Enter Number of Adults*" min="1" required>
                                </div>

                                <div class="col-half">
                                <label for="children">No. of Children</label>
                                <input type="number" id="children" name="children" placeholder="Enter Number of Children" min="0">
                                </div>
                            </div>

                            <label for="subject">Enquiry Subject: <span style="color: red;">*</span></label>
                            <input type="text" id="subject" name="subject" placeholder="Enquiry Subject *" required>

                            <label for="message">Your Message <span style="color: red;">*</span></label>
                            <textarea id="message" name="message" rows="5" placeholder="Enter Your Message *" required></textarea>

                            <input type="submit" value="Send Email">
                            </form>

                            <!-- Success Modal -->
                            <div id="successModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); 
                            justify-content:center; align-items:center; z-index:9999;">
                            <div style="background:#fff; padding:30px; border-radius:10px; text-align:center; max-width:400px;">
                                <h2 style="color:green;">Form submitted successfully!</h2>
                                <p>Thank you! The form has been submitted successfully.<br>We will reply to you soon!</p>
                                <button id="closeModal" style="margin-top:15px; padding:8px 16px; border:none; background:green; color:white; border-radius:5px; cursor:pointer;">OK</button>
                            </div>
                            </div>

                        </div>
                    </div>
                    <script>
                        document.getElementById("enquiryForm").addEventListener("submit", async function(e) {
                        e.preventDefault();
                        const form = e.target;
                        const data = new FormData(form);
                        const modal = document.getElementById("successModal");

                        try {
                            const response = await fetch(form.action, { method: form.method, body: data });
                            if (response.ok) {
                            form.reset();
                            modal.style.display = "flex";
                            } else {
                            alert("Error sending message. Please try again.");
                            }
                        } catch (error) {
                            alert("Network error. Please check your connection.");
                        }
                        });

                        document.getElementById("closeModal").addEventListener("click", function() {
                        document.getElementById("successModal").style.display = "none";
                        });
                    </script>
                </div>

                    <!-- trip price and details -->
                    <div class="trip-pricing">
                        <div class="check-ins" style="width:330px;">
                            <div class="price-head">
                                <h3>Price</h3>
                            </div>
                            <div class="pricing"
                                style="text-align: center; display:flex; justify-content:space-evenly; padding:15px 0px 15px 0px;">
                                <div class="price1">
                                    <span style="color: black;">From </span> <span style="font-size: 2rem;">
                                        $<?php echo $trip['price']; ?></span><span style="color: black;">/Person</span>
                                </div>
                            </div>
                            <h2>Highlights</h2>
                             <div class="highlite" style="margin:10px 0; padding-bottom:2rem; padding-left:10px;">
                                <?php
                                // Loop through all highlight_title columns
                                for ($i = 1; $i <= 6; $i++) {
                                    $key = "highlight_title{$i}";
                                    if (!empty($trip[$key])) {
                                        echo '<i class="fas fa-check" id="check-icon"></i> ';
                                        echo '<span>' . htmlspecialchars($trip[$key]) . '</span><br>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="trip-fact-right" style="padding-left: 10px;">
                                <h4>Next Departure:</h4>
                                <ul style=" list-style-type: none;">
                                    <li>Jan 2025</li>
                                    <li>Feb 2025</li>
                                    <li>March 2025</li>
                                    <li>April 2025</li>
                                </ul>
                            </div>
                            <div class="action" style="width:100%;">
                            <a href="book-trip?tripid=<?php echo $trip['tripid']; ?>" class="pricing-btn" style="display:block; width:100%; text-align:center; padding:0.5rem 1rem;">
                                <i class="fas fa-ticket-alt" style="margin-right:.5rem;"></i> Book Now
                            </a>
                            </div>



                            <div style="text-align: center;padding:20px 0px 10px 0px;">
                                <p>Need help in booking ? <a href="#enquiry-form" style="text-decoration: none;">Enquiry
                                        Now</a></p>
                            </div>
                        </div>

                        <div class="trip-fact-right" style="height: 100px;">

                            <div class="side-trip-facts"
                                style="display: flex; flex-direction: column; grid-row-gap:30px; padding:30px 0px 0px 30px;">
                                <div class="flex items-center">
                                    <div><i class=" fas fa-bus text-teal-500 mr-2"
                                            id="fact-icon"></i><span>Transportation</span>
                                    </div>
                                    <div class=""><span><?php echo $trip['transportation']; ?></span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-hotel text-teal-500 mr-2"
                                            id="fact-icon"></i><span>Accomodation</span>
                                    </div>
                                    <div class=""><span>3 <?php echo $trip['accomodation']; ?></span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-mountain text-teal-500 mr-2" id="fact-icon"></i><span>Maximum
                                            Altitude</span>
                                    </div>
                                    <div class=""><span><?php echo $trip['maximumaltitude']; ?></span></div>
                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-plane-departure text-teal-500 mr-2"
                                            id="fact-icon"></i><span>Departure
                                            from</span></div>
                                    <div class=""><span><?php echo $trip['departurefrom']; ?></span></div>
                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-calendar-alt text-teal-500 mr-2" id="fact-icon"></i><span>Best
                                            season</span></div>
                                    <div><span><?php echo $trip['bestseason']; ?></span></div>
                                </div>
                                <div class="flex items-center">
                                    <div> <i class="fas fa-hiking text-teal-500 mr-2" id="fact-icon"></i><span>Tour
                                            type</span></div>

                                    <div class=""><span><?php echo $trip['triptype']; ?></span></div>

                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-utensils text-teal-500 mr-2"
                                            id="fact-icon"></i><span>Meals</span>
                                    </div>
                                    <div class=""><span><?php echo $trip['meals']; ?></span></div>
                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-language text-teal-500 mr-2"
                                            id="fact-icon"></i><span>Language</span></div>
                                    <div class=""><span><?php echo $trip['language']; ?></span></div>

                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-dumbbell text-teal-500 mr-2" id="fact-icon"></i>
                                        <span>Fitness
                                            level</span>
                                    </div>
                                    <div class=""><span><?php echo $trip['fitnesslevel']; ?></span></div>

                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-users text-teal-500 mr-2" id="fact-icon"></i><span>Group
                                            Size</span></div>
                                    <div class=""><span><?php echo $trip['groupsize']; ?></span></div>

                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-child text-teal-500 mr-2" id="fact-icon"></i><span>Minimum
                                            Age</span></div>
                                    <div class=""><span><?php echo $trip['minimumage']; ?></span></div>
                                </div>
                                <div class="flex items-center">
                                    <div><i class="fas fa-user-alt text-teal-500 mr-2" id="fact-icon"></i><span>Maximum
                                            Age</span>
                                    </div>
                                    <div class=""><span><?php echo $trip['maximumage']; ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- related trips -->
        <div class="features">
            <div class="container text-left py-5">
                <h1>Explore Related Trips</h1>
            </div>
        </div>

        <div class="features">
            <div class="container text-center py-5 card-container" id="card-container"
                style="row-gap:20px; background-color:transparent;">
                <?php
                // current trip info
                $activity = $trip['activity'];
                $location = $trip['location'];
                $currentTripId = $trip['tripid'];

                // secure query: fetch trips with same activity OR same location, exclude current trip
                $sql = "SELECT * FROM trip_details_view WHERE (activity = ? OR location = ?) AND tripid != ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $activity, $location, $currentTripId);
                $stmt->execute();
                $trip_result = $stmt->get_result();

                if ($trip_result->num_rows > 0) {
                    while ($trips = $trip_result->fetch_assoc()) { ?>
                        <div class="card" 
                            style="flex: 0 0 calc(33.33% - 20px); 
                                    box-sizing: border-box; 
                                    height: 520px; 
                                    display: flex; 
                                    flex-direction: column; 
                                    justify-content: space-between; 
                                    border: 1px solid #ddd; 
                                    border-radius: 10px; 
                                    overflow: hidden; 
                                    background-color: #fff;">
                            <div class="position-relative" style="height: 220px; overflow: hidden;">
                                <a href="view-trip?tripid=<?php echo $trips['tripid']; ?>">
                                    <img src="<?php echo htmlspecialchars($trips['main_image']); ?>" 
                                        alt="Trip Image"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </a>
                                <span class="badge-featured" 
                                    style="position: absolute; top: 10px; left: 10px; background: #008080; color: #fff; padding: 5px 10px; border-radius: 5px;">
                                    Featured
                                </span>
                            </div>

                            <div class="card-body" style="padding: 15px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <h5 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($trips['title']); ?></h5>
                                    <p style="margin: 0 0 5px 0;">
                                        <i class="fas fa-map-marker-alt" style="color: green; margin-right: 8px;"></i>
                                        <?php echo htmlspecialchars($trips['location']); ?>
                                    </p>
                                    <p style="margin: 0 0 5px 0;">
                                        <i class="fas fa-clock" style="color: green; margin-right: 8px;"></i>
                                        <?php echo htmlspecialchars($trips['duration']); ?>
                                    </p>
                                    <p style="margin: 0 0 10px 0;">
                                        <i class="fas fa-users" style="color: green; margin-right: 8px;"></i>
                                        <?php echo htmlspecialchars($trips['groupsize']); ?>
                                    </p>
                                </div>

                                <div style="border-top: 1px solid #ddd; padding-top: 10px;">
                                    <h3 style="margin: 0; color: #008080;">$<?php echo number_format($trips["price"]); ?></h3>
                                </div>

                                <div style="margin-top: 10px;">
                                    <p style="margin: 0 0 10px 0;">
                                        <?php
                                        $description = htmlspecialchars($trips['description']);
                                        $words = explode(" ", $description);
                                        $firstTenWords = implode(" ", array_slice($words, 0, 10));
                                        echo $firstTenWords . '...';
                                        ?>
                                    </p>
                                    <a href="view-trip?tripid=<?php echo $trips['tripid']; ?>" 
                                    style="text-decoration: none; color: #008080; font-weight: bold;">
                                    VIEW DETAILS
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php }
                    } else {
                        echo "<p>No related trips found.</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="features">
                <div class="container py-5">
                    <div class="related-trips">

                    </div>
            </div>
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
        document.addEventListener("DOMContentLoaded", function () {
            const menu = document.querySelector(".itinery-menu");
            const menuOffset = menu.offsetTop; // Get original position of menu

            window.addEventListener("scroll", function () {
                if (window.pageYOffset >= menuOffset) {
                    menu.classList.add("sticky-menu"); // Stick to the top
                } else {
                    menu.classList.remove("sticky-menu"); // Remove sticky effect
                }
            });
        });

        //Function to set the active menu item
        function setActiveMenuItem(activeIndex) {
            const menuItems = document.querySelectorAll('.itinery-menu a'); // Select all menu links

            menuItems.forEach((item, index) => {
                if (index === activeIndex) {
                    item.classList.add('active-menu'); // Add active class
                } else {
                    item.classList.remove('active-menu'); // Remove active class
                }
            });
        }

        // Set the first menu item (Overview) as active by default
        setActiveMenuItem(0);

        // Example: Set the first menu item (Overview) as active by default
        setActiveMenuItem();
    </script>
    
    <!-- for itinerary -->
    <script>
        function toggleDay(header) {
        const day = header.parentElement;
        const content = day.querySelector('.day-content');

        if (day.classList.contains('expanded')) {
            // collapse
            content.style.maxHeight = null;
            day.classList.remove('expanded');
        } else {
            // collapse any other open section (optional)
            document.querySelectorAll('.day.expanded').forEach(d => {
            d.classList.remove('expanded');
            d.querySelector('.day-content').style.maxHeight = null;
            });

            // expand this one
            content.style.maxHeight = content.scrollHeight + 'px';
            day.classList.add('expanded');
        }
        }  
    </script>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>