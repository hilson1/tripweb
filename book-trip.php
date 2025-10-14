<?php
require 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE userid = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle case where user doesn't exist
    header('location:login.php');
    exit;
}
if (isset($_GET['tripid'])) {
    $tripid = $_GET['tripid'];
}



$stmt1 = $conn->prepare("SELECT tripid, title FROM trips where tripid = ?");
$stmt1->bind_param("i", $tripid);
$stmt1->execute();
$tripresult = $stmt1->get_result();
if ($tripresult->num_rows > 0) {
    $trip = $tripresult->fetch_assoc();
}


// Validate that the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize and get form data
    $user_id = $_SESSION['user_id'] ?? null;
    $trip_id = (int) $_POST['tripid'];
    $trip_name = $conn->real_escape_string($_POST['trip_title']);
    $full_name = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address'] ?? '');
    $phone_number = $conn->real_escape_string($_POST['phonenumber']);
    $city = $conn->real_escape_string($_POST['city'] ?? '');
    $country = $conn->real_escape_string($_POST['country'] ?? '');
    $adults = (int) $_POST['adults'];
    $children = (int) $_POST['childrens'];
    $arrival_date = $conn->real_escape_string($_POST['arrivaldate']);
    $departure_date = $conn->real_escape_string($_POST['departuredate']);
    $arrival_time = $conn->real_escape_string($_POST['arrivaltime']);
    $airport_pickup = $conn->real_escape_string($_POST['pickup']);
    $message = $conn->real_escape_string($_POST['msg']);
    $payment_mode = $conn->real_escape_string($_POST['paymentMode']);
    $payment_status = 'not paid';
    $start_date = $conn->real_escape_string($_POST['startdate']);

    $stmt3 = $conn->prepare("INSERT INTO trip_bookings (
        user_id, trip_id, trip_name, full_name, email, address, phone_number, city, country,
        adults, children, arrival_date, departure_date, arrival_time,
        airport_pickup, message, payment_mode, payment_status, start_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt3) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt3->bind_param(
        "sisssssssiissssssss",
        $user_id,
        $trip_id,
        $trip_name,
        $full_name,
        $email,
        $address,
        $phone_number,
        $city,
        $country,
        $adults,
        $children,
        $arrival_date,
        $departure_date,
        $arrival_time,
        $airport_pickup,
        $message,
        $payment_mode,
        $payment_status,
        $start_date
    );

    if ($stmt3->execute()) {
        echo "<script>alert('Booking Successful'); window.location.href = 'success.php';</script>";
    } else {
        echo "<script>alert('Error: " . addslashes($stmt3->error) . "');</script>";
    }
    $stmt3->close();
    $conn->close();
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>book-trip | ThankYouNepalTrip</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="index.css">

    <style>
        .py-5 {
            display: flex;
            gap: 2rem;
            justify-content: space-between;
        }

        .book-container {
            width: 70%;
            padding: 5px;
            border-radius: 5px;

        }

        .right-container {
            width: max-content;
        }

        .book-title {
            background-color: #008080 !important;
        }

        .error {
            color: red;
        }

        .hero {
            background: url('assets/img/pk.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 90px 20px;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
        }

        .hero p {
            font-size: 1.5rem;
        }

        .booking-right {
            width: 30%;
        }

        .right-trips-link {
            width: 100%;
            padding: 10px;
        }
    </style>
</head>

<body>
    <?php
    include("frontend/header.php");
    ?>
    <header class="hero">
        <h1>Booking Form</h1>
        <p>Your trusted partner for unforgettable journeys around the Nepal.</p>
    </header>
    <div class="features">
        <div class="container py-5 ">
            <div class="book-container">
                <div class="container bg-white">
                    <h1 class="mb-4">
                        TRIP BOOKING FORM
                    </h1>
                    <form method="post" action="">
                        <!-- Trip Details -->
                        <div class="mb-4">
                            <div class="bg-success text-white p-2 mb-2 book-title">
                                Trip Details :
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="hidden" value="<?php echo $trip['tripid']; ?>" name="tripid">
                                    <input type="hidden" value="<?php echo $trip['title']; ?>" name="trip_title">
                                    <label>
                                        Trip Name : <h1><?php echo $trip['title']; ?></h1>
                                    </label><br>

                                    <label>
                                        Start Date :
                                    </label>
                                    <select class="form-control" id="startdate" required name="startdate">
                                        <option value="not selected">
                                            Select Date
                                        </option>
                                        <option value="May 2025">
                                            May 2025
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Personal Details -->
                        <div class="mb-4">
                            <div class="bg-success text-white p-2 mb-2 book-title">
                                Personal Details :
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>
                                        Full Name :
                                    </label>
                                    <input class="form-control" id="fullName" required name="fullname" type="text"
                                        value="<?php echo $user['first_name'] . " " . $user['last_name']; ?>" />
                                    <div class="error" id="fullNameError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Email :
                                    </label>
                                    <input class="form-control" id="email" required type="email" name="email"
                                        value="<?php echo $user['email']; ?>" />
                                    <div class="error" id="emailError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Address :
                                    </label>
                                    <input class="form-control" id="address" required type="text" name="address"
                                        value="<?php echo $user['address']; ?>" />
                                    <div class="error" id="addressError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Tel / Mobile :
                                    </label>
                                    <input class="form-control" id="tel" required="" type="tel" name="phonenumber"
                                        value="<?php echo $user['phone_number']; ?>" />
                                    <div class="error" id="telError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        City :
                                    </label>
                                    <input class="form-control" id="city" required="" type="text" name="city"
                                        value="<?php echo $user['address']; ?>" />
                                    <div class="error" id="cityError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Country :
                                    </label>
                                    <input class="form-control" id="city" required type="text" name="country"
                                        value="<?php echo $user['country']; ?>" />
                                    <div class="error" id="countryError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        No. of person :
                                    </label>
                                    <select class="form-control" id="adults" required name="adults">
                                        <option value="">
                                            Select Adults (12+)
                                        </option>
                                        <option value="1">
                                            1
                                        </option>
                                        <option value="2">
                                            2
                                        </option>
                                        <option value="3">
                                            3
                                        </option>
                                        <option value="4">
                                            4
                                        </option>
                                        <option value="5">
                                            5
                                        </option>
                                        <option value="6">
                                            6
                                        </option>
                                        <option value="7">
                                            7
                                        </option>
                                        <option value="8">
                                            8
                                        </option>
                                        <option value="8">
                                            8
                                        </option>
                                        <option value="9">
                                            9
                                        </option>
                                        <option value="10">
                                            10
                                        </option>
                                        <option value="12">
                                            12
                                        </option>
                                        <option value="13">
                                            13
                                        </option>
                                        <option value="14">
                                            14
                                        </option>
                                        <option value="15">
                                            15
                                        </option>
                                        <option value="16">
                                            16
                                        </option>
                                        <option value="17">
                                            17
                                        </option>
                                    </select>
                                    <div class="error" id="adultsError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Children (&gt;12) :
                                    </label>
                                    <select class="form-control" id="children" required name="childrens">
                                        <option value="">
                                            Select Children (&gt;12)
                                        </option>
                                        <option value="0">
                                            0
                                        </option>
                                        <option value="1">
                                            1
                                        </option>
                                        <option value="2">
                                            2
                                        </option>
                                        <option value="3">
                                            3
                                        </option>
                                        <option value="4">
                                            4
                                        </option>
                                        <option value="5">
                                            5
                                        </option>
                                        <option value="6">
                                            6
                                        </option>
                                    </select>
                                    <div class="error" id="childrenError">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Arrival Details -->
                        <div class="mb-4">
                            <div class="bg-success text-white p-2 mb-2 book-title book-title">
                                Arrival Details :
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>
                                        Arrival Date :
                                    </label>
                                    <input class="form-control" id="arrivalDate" required name="arrivaldate"
                                        type="date" />
                                    <div class="error" id="arrivalDateError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Departure Date :
                                    </label>
                                    <input class="form-control" id="departureDate" required name="departuredate"
                                        type="date" />
                                    <div class="error" id="departureDateError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Arrival Time :
                                    </label>
                                    <input class="form-control" id="arrivalTime" required name="arrivaltime"
                                        type="time" />
                                    <div class="error" id="arrivalTimeError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Airport Pickup :
                                    </label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-radio-input" id="pickupYes" name="pickup" required
                                            type="radio" value="yes" />
                                        <label class="form-check-label" for="pickupYes">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-radio-input" id="pickupNo" name="pickup" required
                                            type="radio" value="no" />
                                        <label class="form-check-label" for="pickupNo">
                                            No
                                        </label>
                                    </div>
                                    <div class="error" id="pickupError">
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <label>
                                        Message :
                                    </label>
                                    <textarea class="form-control" id="message" name="msg"
                                        placeholder="Any message you want to leave?" required="" rows="4"></textarea>
                                    <div class="error" id="messageError">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Payment Method -->
                        <div class="mb-4">
                            <div class="bg-success text-white p-2 mb-2 book-title">
                                Payment Method :
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>
                                        Mode of Payment:
                                    </label>
                                    <select class="form-control" id="paymentMode" name="paymentMode" required="">
                                        <option value="">
                                            Select Payment Mode
                                        </option>
                                        <option value="Paypal">
                                            Paypal
                                        </option>
                                        <option value="Bank Transfer">
                                            Bank Transfer
                                        </option>
                                        <option value="Stripe">
                                            Stripe
                                        </option>
                                    </select>
                                    <div class="error" id="paymentModeError">
                                    </div>
                                </div>
                                <div class="form-group col-md-6 d-flex align-items-center">
                                    <input class="mr-2" id="notRobot" type="checkbox" required="" /><br>
                                    <label class="mr-2">
                                        I'm not a robot
                                    </label>
                                    <img alt="reCAPTCHA verification" class="img-fluid" height="50"
                                        src="https://storage.googleapis.com/a1aa/image/gXTOh7QaVJ0xctIwQI9PNHXYjL0ypahPF8sCScd6J-U.jpg"
                                        width="100" height="60" />
                                    <div class="error" id="notRobotError">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="text-center">
                            <input class="btn btn-warning text-white px-5" type="submit" value="Book Now">
                        </div>
                    </form>
                </div>

            </div>
            <div class="booking-right">
                <div class="container bg-white">
                    <h1 class="mb-4" style="color: white;"> I</h1>
                    <div class="mb-4">
                        <div class="mb-4">
                            <div class="bg-success text-white p-2 mb-2 book-title">
                                Best Deals
                            </div>
                            <div class="right-trips-link">
                                <div class="md-6
                                ">
                                    <i class="fas fa-hand-point-right" style="color:green; margin-right:10px;"></i><a
                                        href="">Trip to Kathmandu
                                        Valley</a>
                                </div>
                                <div class="md-6
                                ">
                                    <i class="fas fa-hand-point-right" style="color:green; margin-right:10px;"></i><a
                                        href="">Trip to Kathmandu
                                        Valley</a>
                                </div>
                                <div class="md-6
                                ">
                                    <i class="fas fa-hand-point-right" style="color:green; margin-right:10px;"></i><a
                                        href="">Trip to Kathmandu
                                        Valley</a>
                                </div>
                                <div class="md-6
                                ">
                                    <i class="fas fa-hand-point-right" style="color:green; margin-right:10px;"></i><a
                                        href="">Trip to Kathmandu
                                        Valley</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="mb-4">
                            <div class="bg-success text-white p-2 mb-2 book-title">
                                Best Deals
                            </div>
                            <div class="right-trips-link">
                                <div class="md-6">
                                    <i class="fas fa-hand-point-right" style="color:green; margin-right:10px;"></i><a
                                        href="">Trip to Kathmandu
                                        Valley</a>
                                </div>
                                <div class="md-6">
                                    <i class="fas fa-hand-point-right" style="color:green; margin-right:10px;"></i><a
                                        href="">Trip to Kathmandu
                                        Valley</a>
                                </div>
                                <div class="md-6">
                                    <i class="fas fa-hand-point-right" style="color:green; margin-right:10px;"></i><a
                                        href="">Trip to Kathmandu
                                        Valley</a>
                                </div>
                                <div class="md-6">
                                    <i class="fas fa-hand-point-right" style="color:green; margin-right:10px;"></i><a
                                        href="">Trip to Kathmandu
                                        Valley</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
    </script>
    </div>
    </div>
</body>

</html>

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