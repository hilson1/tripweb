<?php
include("frontend/session_start.php");
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
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
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
            border-radius: 3px;

        }

        .carousel img {
            height: 300px;
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

        .prev,
        .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 50%;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
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
        }
    </style>
</head>
<?php
include("frontend/header.php");
?>
<section class="trips-section">
    <div class="sort-bar">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Destinations</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="trips-section">
    <div class="sort-bar">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sort-container">
                        <span class="sort-text">Sort:</span>
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="" role="button" id="dropdownMenuLink"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Latest
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="#">Latest</a></li>
                                <li><a class="dropdown-item" href="#">Oldest</a></li>
                                <li><a class="dropdown-item" href="#">Popular</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<section class="trips-section">
    <div class="container mt-5 card-container">

        <?php for ($i = 0; $i < 3; $i++) { ?>
            <div class="card">
                <div class="position-relative">
                    <div class="carousel">
                        <div class="carousel-container">
                            <img src="assets/img/mustang.jpg" class="slide active">
                            <img src="assets/img/Manaslu.jpg" class="slide">
                            <img src="assets/img/nature.jpg" class="slide">
                        </div>
                        <button class="prev" onclick="prevSlide()">&#10094;</button>
                        <button class="next" onclick="nextSlide()">&#10095;</button>
                    </div>
                    <span class="badge-featured">
                        Featured
                    </span>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            Paris Effiel Tower Tour 1 Day Tour
                        </h5>
                        <i class="fas fa-heart wishlist-icon">
                        </i>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <p class="mb-1">
                                <i class="fas fa-map-marker-alt">
                                </i>
                                France, India, Nepal, Srilanka
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-clock">
                                </i>
                                5 Hours
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-users">
                                </i>
                                1-10 People
                            </p>
                            <p class="mb-1">
                                Travel is the movement of people between relatively distant geographical...
                            </p>
                        </div>
                        <div class="ms-auto text-center">
                            <div class="badge bg-success mb-2">
                                40% Off
                            </div>
                            <div class="price">
                                $30
                                <span class="original-price">
                                    $50
                                </span>
                            </div>

                        </div>
                    </div>
                    <a class="btn btn-view-details w-100" href="#">
                        VIEW DETAILS
                    </a>
                    <p class="next-departure mt-3">
                        Next Departure: February 3, 2025 | February 4, 2025 | February 5, 2025
                    </p>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<?php
include("frontend/footer.php");
?>
<?php
include("frontend/scrollup.html");
?>/
<script>
    document.querySelectorAll(".card").forEach(card => {
        let currentIndex = 0; // Index for each card
        const slides = card.querySelectorAll(".slide");

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove("active"));
            slides[index].classList.add("active");
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            showSlide(currentIndex);
        }

        // Attach event listeners to next & prev buttons inside the card
        card.querySelector(".next").addEventListener("click", nextSlide);
        card.querySelector(".prev").addEventListener("click", prevSlide);
    });

</script>
<script src=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>