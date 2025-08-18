<?php
// Start the session and check authentication
session_start();

// Redirect to login page if not authenticated
if (!isset($_SESSION['user_id'])) {
    header(header: "Location: login");

}

// Include database connection
require 'connection.php';

// Get current user's data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
// Close connections
$stmt->close();

// Fetching data from trip_bookings table
$sql1 = "SELECT * FROM trip_bookings  inner join trips on trip_bookings.trip_id = trips.tripid inner join trip_images on trips.tripid = trip_images.tripid where user_id=?";
$stmt2 = $conn->prepare($sql1);
$stmt2->bind_param("s", $user_id);
$stmt2->execute();
$booking_result = $stmt2->get_result();




$conn->close();
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
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            padding: 10px;
            border: 1px solid gray;
            color: #008080;
        }

        .paynow-btn:first-of-type {
            color: white;
            background-color: #008080;
        }

        .bold-span {
            font-weight: bold;
        }

        .trip-information {
            width: 60%;
        }

        @media (max-width: 768px) {
            .profile-top {
                display: flex;
                flex-direction: column;
            }

            .user-contents img {
                width: 100%;
            }

            .user-contents {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 20px;
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
                <a class="logout-btn" href="logout" onclick="return confirm('Are you sure want to logout?');">
                    <i class="fas fa-sign-out-alt mr-2">
                    </i>
                    Log Out
                </a>
            </div>
            <div class="navigation-menu">
                <a href="my-account" class="nav-btn"><i class="fas fa-calendar-alt"></i>
                    Booking</a>
                <a href="my-account-setting" class="nav-btn"><i class="fas fa-cog"></i>
                    Account</a>
            </div>
            <script>
                // Select all the links with the class 'nav-btn'
                const links = document.querySelectorAll('.nav-btn');

                // Set the first link as active if there are any links
                if (links.length > 0) {
                    links[0].classList.add('active'); // Changed to 0 to set the first link as active
                }
                // Add click event listener to each link
                links.forEach(link => {
                    link.addEventListener('click', function (event) {
                        // Prevent the default action of the link
                        event.preventDefault();
                        // Remove the 'active' class from all links
                        links.forEach(l => l.classList.remove('active'));
                        // Add the 'active' class to the clicked link
                        this.classList.add('active');
                        // Optionally, navigate to the link's href
                        window.location.href = this.getAttribute('href');
                    });
                });
            </script>
            <div class="user-contents" style="margin-bottom: 50px;">
                <h1>Booking Details</h1>
            </div>
            <?php if ($booking_result->num_rows > 0) {
                while ($booking = $booking_result->fetch_assoc()) { ?>
                    <div class="user-contents" style="margin-bottom:2rem;">
                        <div class="booking-image">
                            <img src="<?php echo $booking['main_image']; ?>" height="200" width="200">
                        </div>
                        <div class=" trip-information">
                            <div class="bg-success text-white p-2 mb-2 book-title">
                                <?php echo $booking['trip_name']; ?>
                            </div>
                            <table>
                                <tr>
                                    <td><span>Trip Code : </span></td>
                                    <td> <span class="bold-span"><?php echo $booking['trip_id']; ?></span></td>
                                </tr>
                                <tr>
                                    <td><span>Trip Start Date: </span></td>
                                    <td><span class="bold-span"><?php echo $booking['start_date']; ?></span><br></td>
                                </tr>
                                <!-- <tr>
                                    <td> <span>Trip End Date: </span></td>
                                    <td> <span class="bold-span"></span><br></td>
                                </tr> -->
                                <tr>
                                    <td><span>Duration: </span></td>
                                    <td><span class="bold-span"><?php echo $booking['duration']; ?></span></td>
                                </tr>
                                <tr>
                                    <td><span>Payment: </span></td>
                                    <td><span class="bold-span"><?php echo $booking['payment_status']; ?></span></td>
                                </tr>
                            </table>
                        </div>

                        <div class=" view-detail">
                            <div class="paynow">
                                <a href="" class="paynow-btn">Pay Now</a>
                                <a href="booked-trip-view?booking-id=<?php echo $booking['id']; ?>" class="paynow-btn">View
                                    Details</a>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
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