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
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .heros {
      background: url('assets/img/contact.jpg') no-repeat center center/cover;
      color: white; text-align: center; padding: 150px 20px;
    }
    .heros h1 {font-size: 3.5rem; margin-bottom: 20px; font-family: 'Pacifico', cursive;}
    .contact-section {padding: 60px 0;}
    .cards {border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);}
    .btn-warning {background-color: #f4a261; border: none;}
    .btn-warning:hover {background-color: #e76f51;}
    .contact-info {background-color: #008080; color: white; border-radius: 10px; padding: 30px;}
  </style>
</head>
<body>
<?php include("frontend/header.php"); ?>

<header class="heros">
  <h1>CONTACT US</h1>
  <p>We’d love to hear from you — reach out anytime!</p>
</header>

<section class="contact-section">
  <div class="container">
    <div class="row g-4">

      <div class="col-md-7">
        <div class="cards p-4">
          <h3 class="mb-3 text-center">Get in Touch</h3>
            <form id="contactForm" action="https://api.web3forms.com/submit" method="POST">
              <input type="hidden" name="access_key" value="YOUR_ACCESS_KEY_HERE">
              <input type="hidden" name="from_name" value="ThankYouNepalTrip Contact Form">
              <input type="hidden" name="replyto" value="info@thankyounepaltrip.com">

              <div class="mb-3">
                <label for="name" class="form-label">Full Name*</label>
                <input type="text" class="form-control" id="name" name="name" required>
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

              <div id="form-status" class="mt-3 text-center fw-bold"></div>
            </form>
        </div>
      </div>

      <div class="col-md-5">
        <div class="contact-info">
          <h4>Contact Information</h4>
          <p>Kathmandu, Bagmati, Nepal</p>
          <p><i class="bi bi-telephone"></i> (+977) 123-456789<br>(+977) 123-856475</p>
          <p><i class="bi bi-envelope"></i> info@thankyounepaltrip.com</p>
          <h5 class="mt-4">Follow us</h5>
          <a href="https://www.facebook.com" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include("frontend/footer.php"); ?>
<?php include("frontend/scrollup.html"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("contactForm").addEventListener("submit", async function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const status = document.getElementById("form-status");

  const response = await fetch(form.action, {
    method: form.method,
    body: formData,
    headers: { Accept: "application/json" }
  });

  if (response.ok) {
    status.innerHTML = "<span class='text-success'>Message sent successfully!</span>";
    form.reset();
  } else {
    status.innerHTML = "<span class='text-danger'>Failed to send message. Try again.</span>";
  }
});
</script>
</body>
</html>
