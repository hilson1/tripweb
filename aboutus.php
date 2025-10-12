<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About us | ThankYouNepalTrip</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <style>
        .hero {
            background: url('assets/img/faqs.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 150px 20px;
        }
        .hero h1{
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: bold;
            font-family: 'Pacifico', cursive;
        }
        .hero p{
            font-size: 1.5rem;
            margin-bottom: 20px;
            font-family: 'Pacifico', cursive;
        }

        .icon-box {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    padding: 30px 20px;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.icon-box i {
    font-size: 2.5rem;
    color: #3aafa9;
    margin-bottom: 15px;
}

.icon-box h5 {
    font-size: 1.3rem;
    font-weight: bold;
    color: #17252a;
    margin-bottom: 10px;
}

.icon-box p {
    color: #555;
    flex-grow: 1;
}

.icon-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

    </style>
</head>
<body>
    <?php
    include("frontend/header.php");
    ?>

    <header class="hero">
        <h1>Welcome</h1>
        <p>Your trusted partner for unforgettable journeys around the globe.</p>
    </header>

    <section class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <img src="assets/img/Historical-Places-in-Nepal.jpg" alt="About Us" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h2>About Us</h2>
                <p>At ThankyouNepalTrip, we are passionate about crafting extraordinary travel experiences. Whether you're
                    seeking a peaceful retreat, a thrilling adventure, or a deep dive into cultural wonders, we are here to
                    make it happen. Our team of travel experts ensures every journey is tailored to your preferences,
                    offering personalized itineraries, insider tips, and unparalleled service.</p>
                <p>We believe that travel is not just about visiting new places; it's about creating memories that last a
                    lifetime. Join us to explore the Nepal like never before!</p>
            </div>
        </div>
    </section>

  <section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Our Values</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="icon-box">
                    <i class="fas fa-globe"></i>
                    <h5>Nepal Base</h5>
                    <p>We offer destinations across different cities and districts of Nepal, ensuring a Nepal travel
                        experience for everyone.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="icon-box">
                    <i class="fas fa-heart"></i>
                    <h5>Customer Focus</h5>
                    <p>Our travelers are at the heart of everything we do, and your satisfaction is our priority.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="icon-box">
                    <i class="fas fa-umbrella-beach"></i>
                    <h5>Unmatched Experiences</h5>
                    <p>From exotic escapes to local adventures, we curate experiences that are truly unique.</p>
                </div>
            </div>
        </div>
    </div>
</section>


    <section class="text-center py-5" style="background-color: #008080; color: white;">
        <h3>Ready to start your journey?</h3>
        <p>Contact us today to plan your dream vacation!</p>
        <a href="contactus.php" class="btn btn-light btn-lg">Get in Touch</a>
    </section>

    <?php
    include("frontend/footer.php");
    ?>
    <?php
    include("frontend/scrollup.html");
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>