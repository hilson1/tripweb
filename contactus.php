<?php
// Include necessary files
include("frontend/session_start.php");
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Thank You Nepal Trip</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .card {
            border-radius: 8px;
        }

        .bg-teal1 {
            background-color: #008080;
            height: auto;
        }

        .text-white {
            color: white !important;
        }

        .container {
            max-width: 1200px;
        }

        .heroo {
            background: url('assets/img/pk.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 80px 20px;
        }

        .heroo h1 {
            font-size: 3.5rem;
            font-weight: bold;
        }

        .heroo p {
            font-size: 1.5rem;
        }

        iframe {
            width: 100%;
            height: 500px;
            border: none;
        }
    </style>
</head>

<body>
    <?php
    include("frontend/header.php");
    ?>
    <header class="heroo">
        <h1>Contact Us</h1>
    </header>
    <?php
    // Check for and display status messages from the URL
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success') {
            echo '<div class="alert alert-success text-center">Your message has been sent successfully!</div>';
        } elseif ($_GET['status'] == 'error') {
            $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Something went wrong.';
            echo '<div class="alert alert-danger text-center">' . $message . '</div>';
        }
    }
    ?>
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mb-4">
                    <div class="card p-4 shadow-sm">
                        <h3 class="mb-3">Get in touch</h3>
                        <form action="process_contact.php" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name*</label>
                                <input type="text" class="form-control" id="name" name="full-name" placeholder="Full name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email*</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject*</label>
                                <input type="text" class="form-control" id="subject" name="subject"
                                    placeholder="Write subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message*</label>
                                <textarea class="form-control" id="message" name="message" rows="5"
                                    placeholder="Write your message" required></textarea>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="policy" required>
                                <label class="form-check-label" for="policy">
                                    By contacting us, you agree to our <a href="#" class="text-decoration-none">Privacy Policy</a>.
                                </label>
                            </div>
                            <input type="submit" class="btn btn-warning w-100" value="SEND MESSAGE">
                        </form>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card p-4 bg-teal1 text-white shadow-sm">
                        <h4>Contact Information</h4>
                        <p>Kathmandu<br>Bagmati, Nepal</p>
                        <p><i class="bi bi-telephone"></i> (+977) 123-456789<br>(+977) 123-856475</p>
                        <p><i class="bi bi-envelope"></i> thankyounepaltrip.com<br>thankyounepaltrip@gmail.com</p>
                        <h5 class="mt-4">Follow us on</h5>
                        <div>
                            <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("frontend/location.php");
    ?>
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