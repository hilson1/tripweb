<?php
require 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
if (isset($_GET['booking-id'])) {
    $bookid = $_GET['booking-id'];
}
// Fetching data from trip_bookings table
$sql1 = "SELECT * FROM trip_bookings  inner join trips on trip_bookings.trip_id = trips.tripid inner join
 trip_images on trips.tripid = trip_images.tripid where trip_bookings.user_id=? AND trip_bookings.id=?";
$stmt2 = $conn->prepare($sql1);
$stmt2->bind_param("si", $user_id, $bookid);
$stmt2->execute();
$booking = $stmt2->get_result();
$booking_result = $booking->fetch_assoc();
$stmt2->close();

// Fetching data from trip_images table
$sql2 = "SELECT main_image FROM trip_images where tripid=?";
$stmt3 = $conn->prepare($sql2);
$stmt3->bind_param("i", $booking_result['trip_id']);
$stmt3->execute();
$tripimg = $stmt3->get_result();
$main_img = $tripimg->fetch_assoc();
$stmt3->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>booked-trip-view | ThankYouNepalTrip</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">

    </script>
    <style>
        .top-container {
            width: 80%;
            margin: 0 auto;
            height: max-content;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-btn {
            border: 1px solid gray;
            text-decoration: none;
            font-size: 1rem;
            padding: 5px 10px 5px 10px;
            border-radius: 10px;
            color: gray;
            min-width: 100px;
            transition: color 0.3s, border-bottom 0.3s;

        }

        .logout-btn:hover {
            box-shadow: 0px 1px 0px gray;
        }

        .navigation-menu {
            display: flex;
            width: 80%;
            margin: 0 auto;
            padding: 20px 0px 40px 0px;

        }

        .nav-btn {
            text-decoration: none;
            /* Remove underline */
            color: blue;
            /* Default text color */
            padding: 10px;
            transition: color 0.3s, border-bottom 0.3s;
            /* Smooth transition */
        }

        .nav-btn:hover {
            color: darkblue;
            /* Change color on hover */
        }

        .nav-btn.active {
            color: #008080;
            /* Change color when active */
            border-bottom: 2px solid #008080;
            /* Underline effect */

        }

        .user-contents {
            max-width: 80%;
            margin: 0 auto;

            justify-content: space-between;
            display: flex;
        }

        .user-contents img {
            border-radius: 10px;
        }

        .paynow {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .paynow-btn {
            font-weight: bolder;
            font-size: 20px;
            border-radius: 5px;
            text-decoration: none;
            padding: 20px;
            border: 1px solid gray;
            color: #008080;
        }

        .paynow-btn:first-of-type {
            color: white;
            background-color: #008080;
        }

        .booking-image {
            display: flex;
            flex-direction: column;
        }

        .booking-image a {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            font-weight: 300;
        }

        .bold-span {
            font-weight: bold;
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .profile-top {
                display: flex;
                flex-direction: column;
            }

            .user-contents {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .user-contents img {
                width: 100%;
            }

            .trip-information {
                width: 100%;
            }



        }
    </style>
</head>

<body>
    <?php
    include("frontend/header.php");
    ?>
    <div class="features">
        <div class="container py-5 ">
            <div class="top-container">
                <div class="profile-top" style="display: flex; gap: 20px; align-items: center;">
                    <img id="profilePic" alt="User profile picture" height="80"
                        src="https://storage.googleapis.com/a1aa/image/lYqBODwnaMU-b05_oodpY-_9bnJPEcMy7zRIn0c6F8k.jpg"
                        width="80" style="border-radius: 20%; cursor: pointer;" />


                    <h1 class="">
                        Welcome <?php echo $user['user_name']; ?>
                    </h1>
                </div>
                <a class="logout-btn" href="login" onclick="return confirm('Are you sure want to logout?');">
                    <i class="fas fa-sign-out-alt mr-2">
                    </i>
                    Log Out
                </a>

            </div>
            <div class="navigation-menu">
                <a href="my-account" class="nav-btn"><i class="fas fa-arrow-left"></i>
                    Go Back</a>
            </div>
            <div class="user-contents" style="margin-bottom: 40px;">
                <h1>Your Bookings Details</h1>
            </div>
            <div class="user-contents">
                <div class="booking-image">
                    <img src="<?php echo $main_img['main_image']; ?>" alt="" height="200" width="200">
                    <a href="view-trip?tripid=<?php echo $booking_result['trip_id']; ?>" class="paynow-btn">View
                        Trip</a>
                </div>
                <div class="billing-details">
                    <div class=" trip-information">
                        <div class="bg-success text-white p-2 mb-2 book-title" style="margin-top: 20px;">
                            <?php echo $booking_result['trip_name']; ?>
                        </div>
                        <table>
                            <tr>
                                <td><span>Trip Code : </span></td>
                                <td> <span class="bold-span"><?php echo $booking_result['trip_id']; ?></span></td>
                            </tr>
                            <tr>
                                <td><span>Trip Start Date: </span></td>
                                <td><span class="bold-span"><?php echo $booking_result['start_date']; ?></span><br></td>
                            </tr>

                            <!-- <tr>
                                <td> <span>Trip End Date: </span></td>
                                <td> <span class="bold-span">Feb 1, 2026</span><br></td>
                            </tr> -->
                            <tr>
                                <td><span>Duration: </span></td>
                                <td><span class="bold-span"><?php echo $booking_result['duration']; ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="booking-details">
                    <div class="bg-success text-white p-2 mb-2 book-title" style="margin-top: 20px;">
                        Billing Information
                    </div>
                    <table>
                        <tr>
                            <td>Name : </td>
                            <td class="bold-span"> <?php echo $booking_result['full_name']; ?></td>
                        </tr>
                        <tr>
                            <td>Email: </td>
                            <td class="bold-span"><?php echo $booking_result['email']; ?></td>
                        </tr>
                        <tr>
                            <td>Address: </td>
                            <td class="bold-span"><?php echo $booking_result['address']; ?></td>
                        </tr>
                        <tr>
                            <td>Country: </td>
                            <td class="bold-span"><?php echo $booking_result['country']; ?></td>
                        </tr>
                        <tr>
                            <td>Contact : </td>
                            <td class="bold-span"><?php echo $booking_result['phone_number']; ?></td>
                        </tr>
                    </table>

                    <div>
                        <span>No. of person: </span><span
                            class="bold-span"><?php echo $booking_result['adults']; ?></span>
                    </div>
                    <h4 style="margin-top: 20px;">Extra Services</h4>
                    <div>
                        <span>Pickup from Kathmandu Airport: </span> <span
                            class="bold-span"><?php echo $booking_result['airport_pickup']; ?></span>
                    </div>

                </div>
                <div class="payment-details">
                    <div class="bg-success text-white p-2 mb-2 book-title" style="margin-top: 20px;">
                        Payment Details
                    </div>
                    <div><span>Total: </span> <span class="bold-span">$ 400</span> </div>
                    <div><span>Status: </span> <span
                            class="bold-span"><?php echo $booking_result['payment_status']; ?></span></div>
                    <div>
                        <div class="mb-4">
                            <div class="bg-success text-white p-2 mb-2 book-title" style="margin-top: 20px;">
                                Payment Method :
                            </div>

                            <select class="form-control" id="paymentMode" required="">
                                tion value="">
                                Select Payment Mode
                                </option>
                                <option value="Paypal">
                                    Paypal
                                </option>
                                <option value="HBL Bank">
                                    HBL Bank
                                </option>
                                <option value="Stripe">
                                    Stripe
                                </option>
                            </select>
                            <div
                                style="display: flex; justify-content:space-between; align-items:center; padding: 20px 0px 20px 0px;">
                                <input class="mr-2" id="notRobot" required type="checkbox" />
                                <label class="mr-2">
                                    I'm not a robot
                                </label>
                                <img alt="reCAPTCHA verification" class="img-fluid" height="50"
                                    src="https://storage.googleapis.com/a1aa/image/gXTOh7QaVJ0xctIwQI9PNHXYjL0ypahPF8sCScd6J-U.jpg"
                                    width="50" />
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="text-center">
                        <button class="btn btn-warning text-white px-5" type="submit">
                            Pay Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-success text-white p-2 mb-2 book-title"></div>

    <?php
    include("frontend/footer.php");
    ?>
    <div class=" scroll-up" id="scrollUpButton" onclick="scrollToTop()">
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