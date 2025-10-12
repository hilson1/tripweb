<?php
include("frontend/session_start.php");
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | ThankYouNepalTrip</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="index.css">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
    }

    .hero {
      background: url('assets/img/pk.jpg') no-repeat center center/cover;
      color: white;
      text-align: center;
      padding: 150px 20px;
    }

    .hero h1 {
      font-size: 3.5rem;
      margin-bottom: 20px;
      font-weight: bold;
      font-family: 'Pacifico', cursive;
    }

    .hero p {
      font-size: 1.5rem;
      font-family: 'Pacifico', cursive;
    }

    .contact-section {
      padding: 60px 0;
    }

    .card {
      border: none;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-10px);
    }

    .btn-warning {
      background-color: #f4a261;
      border: none;
      font-weight: bold;
    }

    .btn-warning:hover {
      background-color: #e76f51;
    }

    .contact-info {
      background-color: #008080;
      color: white;
      border-radius: 10px;
      padding: 30px;
      height: 60%;
    }

    /* Responsive for mobile view */
    @media (max-width: 768px) {
      .contact-info {
        padding: 20px;
        height: auto;
        text-align: center;
      }
      .contact-info h4, 
      .contact-info h5 {
        font-size: 1.2rem;
      }
      .contact-info p {
        font-size: 0.95rem;
      }
    }

    iframe {
      width: 100%;
      height: 400px;
      border: none;
      border-radius: 10px;
    }
  </style>
</head>

<body>
  <?php include("frontend/header.php"); ?>

  <header class="hero">
    <h1>Contact Us</h1>
    <p>We’d love to hear from you — reach out anytime!</p>
  </header>

  <section class="contact-section">
    <div class="container">
      <div class="row g-4">

        <!-- Contact Form -->
        <div class="col-md-7">
          <div class="card p-4">
            <h3 class="mb-3 text-center">Get in Touch</h3>
            <form action="process_contact.php" method="POST">
              <div class="mb-3">
                <label for="name" class="form-label">Full Name*</label>
                <input type="text" class="form-control" id="name" name="full-name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="subject" class="form-label">Subject*</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
              </div>
              <div class="mb-3">
                <label for="message" class="form-label">Message*</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
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

        <!-- Contact Info -->
        <div class="col-md-5">
          <div class="contact-info">
            <h4>Contact Information</h4>
            <p>Kathmandu<br>Bagmati, Nepal</p>
            <p><i class="bi bi-telephone"></i> (+977) 123-456789<br>(+977) 123-856475</p>
            <p><i class="bi bi-envelope"></i> info@thankyounepaltrip.com</p>
            <h5 class="mt-4">Follow us</h5>
            <div>
            <a href="https://www.facebook.com" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
            </div>

          </div>
        </div>

      </div>
      <div class="mt-5">
        <?php include("frontend/location.php"); ?>
      </div>
    </div>
  </section>

  <?php include("frontend/footer.php"); ?>
  <?php include("frontend/scrollup.html"); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
