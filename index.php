<?php
include("frontend/session_start.php");
include("connection.php");


$stmt = $conn->prepare("SELECT trips.*, trip_images.main_image
FROM trips 
INNER JOIN trip_images ON trips.tripid = trip_images.tripid");
$stmt->execute();
$trip_result = $stmt->get_result();

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
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="index.css">
   <!-- <link rel="stylesheet" href="testimonial.css"> -->
   <style>
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
   <section class="hero-section">
      <div class="bg" style="margin-top: 70px;">
         <div class="content">
            <h1>Escape Your Comfort Zone.</h1>
            <p>Grab your stuff and let’s get lost.</p>
         </div>
         <div class="search-box">
            <form>
               <div class="mb-3 input-group">
                  <span class="input-group-text"><i class="fas fa-walking" id="search-icon"></i></span>
                  <select class="form-select" aria-label="Activity">
                     <option selected>Activity</option>
                     <option value="1">Activity 1</option>
                     <option value="2">Activity 2</option>
                     <option value="3">Activity 3</option>
                  </select>
               </div>
               <div class="mb-3 input-group">
                  <span class="input-group-text"><i class="fas fa-dollar-sign" id="search-icon"></i></span>
                  <select class="form-select" aria-label="Price">
                     <option selected>$0 - $0</option>
                     <option value="1">$0 - $50</option>
                     <option value="2">$50 - $100</option>
                     <option value="3">$100 - $200</option>
                  </select>
               </div>
               <div class="mb-3 input-group">
                  <span class="input-group-text"><i class="fas fa-map-marker-alt" id="search-icon"></i></span>
                  <select class="form-select" aria-label="Destination">
                     <option selected>Destination</option>
                     <option value="1">Destination 1</option>
                     <option value="2">Destination 2</option>
                     <option value="3">Destination 3</option>
                  </select>
               </div>
               <div class="mb-3 input-group">
                  <span class="input-group-text"><i class="fas fa-clock" id="search-icon"></i></span>
                  <select class="form-select" aria-label="Duration">
                     <option selected>0 Days - 11 Days</option>
                     <option value="1">1 Day - 3 Days</option>
                     <option value="2">4 Days - 7 Days</option>
                     <option value="3">8 Days - 11 Days</option>
                  </select>
               </div>
               <div class="mb-3 input-group">
                  <span class="input-group-text"><i class="fas fa-calendar-alt" id="search-icon"></i></span>
                  <select class="form-select" aria-label="Date">
                     <option selected>Date</option>
                     <option value="1">Date 1</option>
                     <option value="2">Date 2</option>
                     <option value="3">Date 3</option>
                  </select>
               </div>
               <button type="submit" class="btn btn-warning w-100">Search</button>
            </form>
         </div>
      </div>
   </section>
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
   <div class="features">
      <div class="container text-center py-5">
         <h1>Explore popular trips</h1>
         <p>Get started with handpicked top rated trips.</p>
         <div class="divider"></div>
      </div>
   </div>
   <h1></h1>
   </h1>

   <div class="features">
      <div class="container text-center py-5 card-container" id="card-container"
         style="row-gap:20px; background-color:transparent;">

         <?php if ($trip_result->num_rows > 0) {
            while ($trip = $trip_result->fetch_assoc()) { ?>
               <div class="card" style=" flex: 0 0 calc(33.33% - 20px);" style="height:600px;">
                  <div class="position-relative">
                     <div class="carousel">
                        <div class="carousel-container">
                           <a href="view-trip?tripid=<?php echo $trip['tripid']; ?>">
                              <img src="<?php echo $trip['main_image']; ?>" style="height: 250px; width: 400px;"
                                 class="slide active">
                           </a>
                        </div>
                     </div>
                     <span class="badge-featured">
                        Featured
                     </span>
                  </div>

                  <div class="card-top">
                     <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center" style="padding:10px 0px 10px 0px">
                           <a href="" style="text-decoration:none; color:black;" onmouseover="this.style.color='#008080'"
                              onmouseout="this.style.color='black'">
                              <h5 class=" card-title mb-0">
                                 <?php echo $trip["title"]; ?>
                              </h5>
                           </a>
                        </div>
                        <div class="me-3 card-contents" style="padding:10px 0px 10px 0px; border-bottom:1px solid gray;">
                           <p class="mb-1">
                              <?php
                              $description = $trip['description'];
                              $words = explode(" ", $description);
                              $firstTenWords = implode(" ", array_slice($words, 0, 10));

                              echo $firstTenWords . '...'; // Adds "..." to indicate there's more
                              ?>

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
                              <?php echo $trip["location"]; ?>
                           </p>
                           <p class="mb-1">
                              <i class="fas fa-clock" style="color:green; margin-right:5px;">
                              </i>
                              <?php echo $trip["duration"]; ?>
                           </p>
                           <p class="mb-1">
                              <i class="fas fa-users" style="color:green; margin-right:2px;">
                              </i>
                              <?php echo $trip["groupsize"]; ?> People
                           </p>
                        </div>
                        <div class="me-3 card-contents">
                           <div class="price" style="margin-top:50%;">
                              <h2><?php echo "$" . number_format($trip["price"]); ?></h2>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            <?php }
         } ?>
      </div>
   </div>
   <div class="features" style="margin-top:-50px;">
      <div class="container text-center py-5 card-container">
         <div class="container-custom" style="margin:0 auto;">
            <a href="#" class="btn btn-custom">VIEW ALL TRIPS</a>
         </div>
      </div>
   </div>
   <div class="features">
      <div class="container text-center py-5">

         <h1>Explore popular destinations</h1>
         <p>A new journey begin here within, find a destination that suits you and start travelling. We offer best
            travel packages.</p>
         <div class="divider"></div>
      </div>
   </div>
   <div class="features" style="margin-top:-50px;">
      <div class="container text-center py-5 card-container">
         <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-4">
               <div class="trip-card">
                  <img src="assets/img/budget.jpg" alt="Nature Friendly Trip">
                  <div class="trip-card-body">
                     <h3 class="trip-card-title">Kathmandu</h3>
                     <p class="trip-card-text">Explore ancient temples, bustling markets, and affordable stays in
                        Nepal’s vibrant capital.</p>
                     <a href="#">Learn More</a>
                  </div>
               </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4">
               <div class="trip-card">
                  <img src="assets/img/pokhara.jpg" alt="Cultural Trip">
                  <div class="trip-card-body">
                     <h3 class="trip-card-title">Pokhara</h3>
                     <p class="trip-card-text">Enjoy serene lakes, mountain views,and budget-friendly homestays in
                        this peaceful lakeside city.</p>
                     <a href="#">Learn More</a>
                  </div>
               </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4">
               <div class="trip-card">
                  <img src="assets/img/lumbini.jpg" alt="Budget Travel">
                  <div class="trip-card-body">
                     <h3 class="trip-card-title">Lumbini</h3>
                     <p class="trip-card-text">Discover Buddha’s birthplace, serene monasteries, and budget stays in
                        Lumbini, Nepal. </p>
                     <a href="#">Learn More</a>
                  </div>
               </div>
            </div>

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

   <div class="features">
      <div class="container text-center py-5">

         <h1>Explore The Best Travel Deals</h1>
         <p>A new journey begin here within, find a destination that suits you and start travelling. We offer best
            travel packages.</p>
         <div class="divider"></div>
      </div>
   </div>
   <div class="features">
      <div class="container text-center py-5">
         <h1>Client Reviews</h1>
         <p>Get started with Reviews</p>
         <div class="divider"></div>
      </div>
   </div>
   </div>
   <div class="features" style="margin-top:-40px;">
      <div class="container text-center py-5 card-container">
         <div class="carousel-testi">
            <div class="reviews" id="reviews">
               <div class="review">
                  <div class="container-testi" id="review-container">
                     <div class="image-container">
                        <img src="assets/img/client.jpeg"
                           alt="A young woman with a backpack and headphones, ready for travel">
                     </div>
                     <div class="text-container">
                        <div class="quote-icon">“</div>
                        <h2 class="title">Get Ahead in Travel with Booking</h2>
                        <p class="description">Inquietude simplicity terminated she compliment remarkably few her nay.
                           The weeks are ham asked jokes. Neglected perceived shy nay concluded.</p>
                        <p class="author">Selvetica Forez</p>
                     </div>
                  </div>
               </div>
               <div class="review">
                  <div class="container-testi" id="review-container">
                     <div class="image-container">
                        <img src="assets/img/client.jpeg"
                           alt="A young woman with a backpack and headphones, ready for travel">
                     </div>
                     <div class="text-container">
                        <div class="quote-icon">“</div>
                        <h2 class="title">Get Ahead in Travel with Booking</h2>
                        <p class="description">Inquietude simplicity terminated she compliment remarkably few her nay.
                           The weeks are ham asked jokes. Neglected perceived shy nay concluded.</p>
                        <p class="author">Selvetica Forez</p>
                     </div>
                  </div>
               </div>
               <div class="review">
                  <div class="container-testi" id="review-container">
                     <div class="image-container">
                        <img src="assets/img/client.jpeg"
                           alt="A young woman with a backpack and headphones, ready for travel">
                     </div>
                     <div class="text-container">
                        <div class="quote-icon">“</div>
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