<?php
include("frontend/session_start.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ | ThankYouNepalTrip</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">
    <style>
        .faq-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }

        .accordion-button {
            font-weight: bold;
            color: #333;
        }

        .accordion-button:not(.collapsed) {
            background-color: #e9ecef;
            color: #000;
        }

        .accordion-body {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
        }

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
    </style>
</head>
<?php
include("frontend/header.php");
?>

<header class="hero">
    <h1>FAQ's</h1>
</header>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <h6 class="text-center mb-5">
            Find answers to your questions before joining a tour so that you can find out exactly what you want to know.
            If you have more questions, please feel free to <a href="contactus.php" class="contact-link">reach us via
                the contact form</a>.
        </h6>
        <div class="accordion" id="faqAccordion">
            <!-- FAQ Item 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne">
                        How do I book a trip with ThankyouNepalTrip?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can book a trip directly through our website or by contacting our customer support team. We
                        offer flexible payment options and instant confirmations.
                    </div>
                </div>
            </div>
            <!-- FAQ Item 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo">
                        What destinations does ThankyouNepalTrip cover?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        We cover a wide range of destinations, including Kathmandu, Pokhara, Lumbini, Mustang, Chitwan,
                        and Solukhumbu. Explore our website for more details.
                    </div>
                </div>
            </div>
            <!-- FAQ Item 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree">
                        Are there budget-friendly travel options?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, we specialize in affordable travel packages, including budget accommodations, local
                        transport, and cost-effective tours.
                    </div>
                </div>
            </div>
            <!-- FAQ Item 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour">
                        Can I customize my travel itinerary?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Absolutely! We offer customizable itineraries to suit your preferences, whether you're looking
                        for adventure, relaxation, or cultural experiences.
                    </div>
                </div>
            </div>
        </div>
    </div>
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