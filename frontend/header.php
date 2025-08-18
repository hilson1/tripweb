<div class="header-section">
    <div class="bg-teal text-white py-2 fixed-top top-header" id="topHeader">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="#">
                    <i class="fab fa-facebook-f" id="social-icon">
                    </i>
                </a>
                <a href="#">
                    <i class="fab fa-twitter" id="social-icon">
                    </i>
                </a>
                <a href="#">
                    <i class="fab fa-instagram" id="social-icon">
                    </i>
                </a>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3"> <a href="tel:+977 9742847684"><i class="fas fa-phone" id="social-icon"></i></a> <a
                        href="tel:+977 9742847684" class="phone-link"
                        style="color:white; text-decoration:none;">9741847684</a></span>
                <span><a href="mailto:contact@gmail.com"><i class="fas fa-envelope" id="social-icon"></i></a>
                    <a href="mailto:contact@gmail.com" class="email-link"
                        style="color:white; text-decoration:none;">contact@gmail.com</a></span>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" id="mainNavbar"
        style="padding:0px; height:50px; font-size:1.2rem; font-weight:600;">
        <div class="container" style>
            <a class="navbar-brand" href="index" style="align-items: center;">
                <img src="assets/img/logo.jpg" alt="Logo" width="50" height="40" class="d-inline-block align-text-top">
                <span>NepalTrip</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse ml-3" id="navbarNav"
                style="background-color: white; padding-left:30px; border-radius:0px 0px 10px 10px; margin-top:10px; padding-bottom:10px;">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" id="destinationDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Destination</a>
                        <ul class="dropdown-menu" aria-labelledby="destinationDropdown">
                            <li><a class="dropdown-item" href="destinations?destination-is=Kathmandu">Kathmandu</a>
                            </li>
                            <li><a class="dropdown-item" href="destinations?destination-is=Pokhara">Pokhara</a></li>
                            <li><a class="dropdown-item" href="destinations?destination-is=Mustang">Mustang</a></li>
                            <li><a class="dropdown-item" href="destination">More</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="activitiesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Activities</a>
                        <ul class="dropdown-menu" aria-labelledby="activitiesDropdown">
                            <li><a class="dropdown-item" href="activities?activity-is=Trekking">Trekking</a></li>
                            <li><a class="dropdown-item" href="activities?activity-is=Tour">Tour </a></li>
                            <li><a class="dropdown-item" href="activities?activity-is=Hiking">Hiking </a></li>
                            <li><a class="dropdown-item" href="more-activity">More</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="tripTypesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Trip Types</a>
                        <ul class="dropdown-menu" aria-labelledby="tripTypesDropdown">
                            <li><a class="dropdown-item" href="triptypes?triptype-is=Nature%20Friendly">Nature
                                    Friendly</a></li>
                            <li><a class="dropdown-item" href="triptypes?triptype-is=Cultural">Cultural</a></li>
                            <li><a class="dropdown-item" href="triptypes?triptype-is=Budget%20Friendly">Budget
                                    Friendly</a>
                            </li>
                            <li><a class="dropdown-item" href="trip-types">More</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Pages</a>
                        <ul class="dropdown-menu" aria-labelledby="pagesDropdown">
                            <li><a class="dropdown-item" href="aboutus">About Us</a></li>
                            <li><a class="dropdown-item" href="our-team">Our Team</a></li>
                            <li><a class="dropdown-item" href="faqs">FAQ'S</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="contactus">Contact</a></li>
                </ul>
                <div class="d-flex">
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <a href="my-account.php" class="nav-link">My Account</a>

                    <?php } else { ?>
                        <a href="login.php" class="nav-link">Login</a> |
                        <a href="signup.php" class="nav-link">Sign Up</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
    <script>
        // JavaScript to hide top header on scroll
        const topHeader = document.getElementById('topHeader');
        const mainNavbar = document.getElementById('mainNavbar');
        let lastScrollTop = 0;

        window.addEventListener('scroll', () => {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > lastScrollTop) {
                // Scrolling down
                topHeader.classList.add('top-header-hidden');
                mainNavbar.classList.add('show-navbar');
            } else {
                // Scrolling up
                topHeader.classList.remove('top-header-hidden');
                mainNavbar.classList.remove('show-navbar');
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });
        document.addEventListener("DOMContentLoaded", function () {
            let topHeaderHeight = document.getElementById("topHeader").offsetHeight;
            let mainNavbarHeight = document.getElementById("mainNavbar").offsetHeight;

            document.body.style.paddingTop = (topHeaderHeight + mainNavbarHeight) + "px";
        });
    </script>
</div>