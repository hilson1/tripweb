<?php
require 'connection.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data and sanitize input
    $fname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);
    $zip_postal_code = trim($_POST['zip_postal_code']);
    $country = trim($_POST['country']);

    if (!$user_id) {
        echo "<script>alert('User ID is missing!');</script>";
        exit;
    }

    // Prepare and bind statement - removed extra comma before WHERE
    $stmt = $conn->prepare("UPDATE users SET phone_number = ?, address = ?, zip_postal_code = ?, country = ?, first_name=?, last_name=?, email=? WHERE userid = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssss", $phone_number, $address, $zip_postal_code, $country, $fname, $lastname, $email, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Data saved successfully!');</script>";
        // Refresh user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE userid = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data save failed: " . addslashes($stmt->error) . "');</script>";
    }
}
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
            align-items: center;
            justify-content: space-between;
            display: flex;
            flex-direction: column;
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

        .user-contents form {
            width: 100%;
            padding: 20px;
            background-color: whitesmoke;
            box-shadow:
                5px 5px 10px rgb(201, 201, 201),
                /* Bottom-right shadow */
                -5px 5px 10px rgb(201, 201, 201);
            border-radius: 5px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            row-gap: 20px;
            column-gap: 10px;
        }

        .user-contents form form-child {
            padding: 5px;

        }

        .user-contents form label {
            background-color: #008080;
            width: 100%;
            color: white;
            padding: 5px;
            border-radius: 5px 5px 0px 0px;
        }

        .user-contents form input,
        .user-contents form select {
            width: 100%;
            border: 1px solid gray;
            padding: 10px;

            border-radius: 0px 0px 5px 5px;

        }

        .user-contents form button {
            width: 100%;
            height: 100%;
            border-radius: 5px;
            background-color: #008080;
            color: white;
            border: none;
            margin: 0 auto;
        }

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            position: relative;
            background: white;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
        }

        .popup-content form input {
            border: none;
            padding: 10px;
            border-radius: 10px;
        }

        .popup-content form input:nth-of-type(2) {
            background-color: #008080;
            color: white;
        }

        .popup img {
            width: 300px;
            height: 300px;
            border-radius: 20%;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            background: #008080;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
        }


        @media (max-width: 768px) {
            .profile-top {
                display: flex;
                flex-direction: column;
            }

            .top-container,
            .navigation-menu,
            .user-contents {
                width: 95%;
            }

            .user-contents {
                width: 100%;
                display: flex;
                flex-direction: column;

            }

            .user-contents form {
                width: 100%;
                display: grid;
                grid-template-columns: repeat(1, 1fr);
                row-gap: 20px;
                column-gap: 10px;
            }

            .popup img {
                width: 200px;
                height: 200px;
                border-radius: 20%;
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
                    <div class="image">
                        <img id="profilePic" alt="User profile picture" height="80"
                            src="https://storage.googleapis.com/a1aa/image/lYqBODwnaMU-b05_oodpY-_9bnJPEcMy7zRIn0c6F8k.jpg"
                            width="80" style="border-radius: 20%; cursor: pointer;" />
                        <p style="text-align: center; padding-top: 5px;" id="profilePic">Edit</p>
                    </div>
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
                    Bookings</a>

                <a href="my-account-setting" class="nav-btn"><i class="fas fa-cog"></i>

                    Account</a>
            </div>
            <script>
                // Select all the links with the class 'nav-btn'
                const links = document.querySelectorAll('.nav-btn');

                // Set the first link as active if there are any links
                if (links.length > 0) {
                    links[2].classList.add('active'); // Changed to 0 to set the first link as active
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
            <div class="user-contents">
                <h1 style="margin-bottom: 40px;"> Account Details</h1>
                <form action="" method="post">
                    <div class="form-child">
                        <label for="">First Name: <span style="color:red">*</span></label><br>
                        <input type="text" name="firstname" id="" value="<?php echo $user['first_name']; ?>">
                    </div>
                    <div class="form-child">
                        <label for="">Last Name <span style="color:red">*</span></label><br>
                        <input type="text" name="lastname" id="" value="<?php echo $user['last_name']; ?>">
                    </div>
                    <div class="form-child">
                        <label for="">Email: <span style="color:red">*</span></label><br>
                        <input type="email" name="email" id="" value="<?php echo $user['email']; ?>">
                    </div>
                    <div class="form-child">
                        <label for="">Phone Number <span style="color:red">*</span></label><br>
                        <input type="number" name="phone_number" placeholder="6763647234"
                            value="<?php echo $user['phone_number']; ?>" required>
                    </div>
                    <div class="form-child">
                        <label for="">Address: <span style="color:red">*</span></label><br>
                        <input type="text" name="address" placeholder="Down town"
                            value="<?php echo $user['address']; ?>" required>
                    </div>
                    <div class="form-child">
                        <label for="">Zip/Postal Code: <span style="color:red">*</span></label><br>
                        <input type="number" name="zip_postal_code" placeholder="44788"
                            value="<?php echo $user['zip_postal_code']; ?>" required>
                    </div>
                    <div class="form-child">
                        <label for="">Country: <span style="color:red">*</span></label><br>
                        <input type="text" name="country" placeholder="United Kingdom"
                            value="<?php echo $user['country']; ?>" required>
                    </div>

                    <div class="form-child">
                        <label for="">change Password: Yes/No</label><br>

                        <select name="chpass" id="chpass">
                            <option value="No" selected>No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                    <div class="form-child">
                        <label for="">New Password:</label><br>
                        <input type="password" name="password" id="" placeholder="--blank if no change--">
                    </div>
                    <div class="form-child">
                        <label for="countries">Confirm Password:</label>
                        <br>
                        <input type="password" name="cpassword" id="" placeholder="--blank if no change--">
                    </div>
                    <div class="form-child">
                        <button type="submit">SAVE</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
    <?php
    include("frontend/footer.php");
    ?>
    <div class="scroll-up" id="scrollUpButton" onclick="scrollToTop()">
        <i class="fas fa-chevron-up"></i>
    </div>
    <div id="popup" class="popup">
        <div class="popup-content">
            <button class="close-btn" onclick="closePopup()">X</button>
            <img id="popupImg" src="" alt="Profile Picture">
            <form action="">
                <input type="file" id="fileInput" accept="image/*" style="margin-top: 10px;">
                <p id="errorMessage" style="color: red; display: none;">Invalid file type. Please upload an image.</p>
                <input type="submit" value="Save Change">
            </form>
        </div>
    </div>
    <script>
        const profilePic = document.getElementById('profilePic');
        const popup = document.getElementById('popup');
        const popupImg = document.getElementById('popupImg');
        const fileInput = document.getElementById('fileInput');
        const errorMessage = document.getElementById('errorMessage');

        profilePic.addEventListener('click', function () {
            popup.style.display = 'flex';
            popupImg.src = profilePic.src;
        });

        function closePopup() {
            popup.style.display = 'none';
        }

        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                if (!file.type.startsWith('image/')) {
                    errorMessage.style.display = 'block';
                    fileInput.value = ""; // Clear the invalid file
                    return;
                }
                errorMessage.style.display = 'none';
                const reader = new FileReader();
                reader.onload = function (e) {
                    profilePic.src = e.target.result;
                    popupImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
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