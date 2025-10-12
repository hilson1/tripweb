<div class="header-section">
    <div class="bg-teal text-white py-2 fixed-top top-header" id="topHeader">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="#" target="_blank">
                    <i class="fab fa-facebook-f" id="social-icon"></i>
                </a>
                <a href="#">
                    <i class="fab fa-twitter" id="social-icon"></i>
                </a>
                <a href="#">
                    <i class="fab fa-instagram" id="social-icon"></i>
                </a>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3">
                    <a href="tel:+977 9742847684">
                        <i class="fas fa-phone" id="social-icon"></i>
                    </a>
                    <a href="tel:+977 9742847684" class="phone-link" style="color:white; text-decoration:none;">9741847684</a>
                </span>
                <span>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@thankyounepaltrip.com" target="_blank">
                        <i class="fas fa-envelope" id="social-icon"></i>
                    </a>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@thankyounepaltrip.com" 
                        target="_blank" 
                        class="email-link" 
                        style="color:white; text-decoration:none;">
                        info@thankyounepaltrip.com
                    </a>
                    </span>

            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index">
                <img src="assets/img/logo.png" alt="Logo" width="50" height="40" class="d-inline-block align-text-top">
                <span>NepalTrip</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="destinationDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Destination</a>
                        <ul class="dropdown-menu" aria-labelledby="destinationDropdown">
                            <li><a class="dropdown-item" href="destinations?destination-is=Kathmandu">Kathmandu</a></li>
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
                            <li><a class="dropdown-item" href="activity">More</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="tripTypesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Trip Types</a>
                        <ul class="dropdown-menu" aria-labelledby="tripTypesDropdown">
                            <li><a class="dropdown-item" href="trip-types?triptype-is=Nature%20Friendly">Nature Friendly</a></li>
                            <li><a class="dropdown-item" href="trip-types?triptype-is=Cultural">Cultural</a></li>
                            <li><a class="dropdown-item" href="trip-types?triptype-is=Budget%20Friendly">Budget Friendly</a></li>
                            <li><a class="dropdown-item" href="trip-type">More</a></li>
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
</div>

<style>
    /* CSS moved to a single style block for integration */
    .bg-teal {
        background-color: #008080;
    }

    .text-white {
        color: #fff !important;
    }

    .navbar {
        min-height: 70px;
        transition: top 0.3s ease-in-out;
    }

    .navbar-brand span {
        font-weight: bold;
        color: #008080;
    }

    .nav-link {
        color: #008080 !important;
        font-size: 1.2rem;
        font-weight: 600;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .nav-link:hover {
        text-decoration: underline;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .fixed-top {
        z-index: 1030;
    }

    /* Add this to ensure the navbar is below the top header */
    .navbar.fixed-top {
        top: 40px; /* Adjust this value to match the height of the top header */
    }

    .top-header {
        transition: transform 0.3s ease-in-out;
    }

    /* Hide the top header when scrolling down */
    .top-header-hidden {
        transform: translateY(-100%);
    }

    /* Adjust navbar when top header is hidden */
    .navbar.fixed-top.show-navbar {
        top: 0;
    }

    #social-icon {
        color: white;
        margin-left: 15px;
        margin-right: 15px;
    }

    /* Responsive adjustments for mobile */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background-color: white;
            padding-left: 30px;
            border-radius: 0px 0px 10px 10px;
            margin-top: 10px;
            padding-bottom: 10px;
        }
        .nav-item {
            padding-top: 5px;
        }
    }

    @media (max-width: 768px) {
        .phone-link,
        .email-link {
            display: none !important;
        }
    }
</style>

<script>
    // JavaScript to handle header behavior on scroll
    document.addEventListener("DOMContentLoaded", function() {
        const topHeader = document.getElementById('topHeader');
        const mainNavbar = document.getElementById('mainNavbar');
        let lastScrollTop = 0;
        const topHeaderHeight = topHeader.offsetHeight;
        const mainNavbarHeight = mainNavbar.offsetHeight;

        // Set initial padding on the body to prevent content from hiding behind fixed headers
        document.body.style.paddingTop = (topHeaderHeight + mainNavbarHeight) + "px";

        window.addEventListener('scroll', () => {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > lastScrollTop && currentScroll > topHeaderHeight) {
                // Scrolling down and past the top header
                topHeader.classList.add('top-header-hidden');
                mainNavbar.style.top = '0';
            } else {
                // Scrolling up or at the top of the page
                topHeader.classList.remove('top-header-hidden');
                mainNavbar.style.top = topHeaderHeight + 'px';
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });
    });
</script>