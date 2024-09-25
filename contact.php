<?php
// Include database configuration and essential functions
require('./Admin/inc/db_config.php');
require('./Admin/inc/essentials.php');

// Define the insert function
function insert($query, $params, $types) {
    global $con;  // Use the global connection from db_config.php

    $stmt = $con->prepare($query);
    if ($stmt === false) {
        die('Error preparing statement: ' . $con->error);
    }

    $stmt->bind_param($types, ...$params);
    $result = $stmt->execute();

    if ($result) {
        return 1; // Success
    } else {
        return 0; // Failure
    }
}

// Initialize a variable to store the alert message
$alert_message = '';

// Check if the form has been submitted
if (isset($_POST['send'])) {
    $frm_data = filteration($_POST);  // Sanitize user input

    // Prepare the query
    $q = "INSERT INTO `user_query` (`name`, `email`, `subject`, `message`) VALUES (?, ?, ?, ?)";
    $values = [$frm_data['name'], $frm_data['email'], $frm_data['subject'], $frm_data['message']];

    // Execute the insert function
    $res = insert($q, $values, 'ssss');

    if ($res == 1) {
        $alert_message = '<div class="alert alert-success" role="alert">Mail sent successfully!</div>';
    } else {
        $alert_message = '<div class="alert alert-danger" role="alert">Server down. Try again later.</div>';
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    
    <!--====== Title ======-->
    <title>Metro Empire</title>
    
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
        
    <!--====== Magnific Popup CSS ======-->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
        
    <!--====== Slick CSS ======-->
    <link rel="stylesheet" href="assets/css/slick.css">
        
    <!--====== Line Icons CSS ======-->
    <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
        
    <!--====== Bootstrap CSS ======-->
    <link rel="stylesheet" href="assets/css/bootstrap.4.5.2.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">

    
    <!--====== Default CSS ======-->
    <link rel="stylesheet" href="assets/css/default.css">
    
    <!--====== Style CSS ======-->
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>

<body>
    <!--[if IE]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

    <!--====== PRELOADER PART START ======-->

    <div class="preloader">
        <div class="loader">
            <div class="ytp-spinner">
                <div class="ytp-spinner-container">
                    <div class="ytp-spinner-rotator">
                        <div class="ytp-spinner-left">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                        <div class="ytp-spinner-right">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--====== PRELOADER PART ENDS ======-->

    <!--====== HEADER PART START ======-->

    <header class="header-area">
        <div class="navbar-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand" href="index.html">
                                <img src="assets/img/logo.svg" alt="Logo" height="40px">
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ml-auto">
                                    <li class="nav-item active">
                                        <a class="page-scroll" href="index.html">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="about.php">About</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="room.php">Room</a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="page-scroll" href="#schedules">Schedules</a>
                                    </li> -->
                                    <!-- <li class="nav-item">
                                        <a class="page-scroll" href="#pricing">Pricing</a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a class="page-scroll" href="contact.php">Contact</a>
                                    </li>
                                </ul>
                                <!--login button-->
                                <div class="d-flex">
                                    <button class="btn" id="loginBtn">Login</button>
                                    <button class="btn" id="signupBtn">Sign Up</button>

                                      <!--login form-->
                                      <div id="loginPopup" class="popup">
                                        <div class="popup-content">
                                            <span class="close">&times;</span>
                                            <h2><i class="bi bi-person-circle"></i> User login</h2><br><br>
                                            <form action="login.php" method="post">
                                                <label for="username">Username</label>
                                                <input type="text" id="username" name="username" required>
                                                <label for="password">Password</label>
                                                <input type="password" id="password" name="password" required>
                                                <button type="submit">Login</button>
                                                <a href="javascript: void(0)" class="text-secondary text-decoration-none">Forgotten password?</a>
                                            </form>
                                        </div>
                                    </div>
                                <!--signup form-->
                                <div id="popupForm" class="popup">
                                    <div class="popup-content">
                                        <span class="close-btn" id="closeBtn">&times;</span>
                                        <h2> <i class="bi bi-person-circle"></i>  Sign Up</h2><br><br>
                                        <form id="signupForm">
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="username">Username*</label>
                                                    <input type="text" id="username" name="username" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password*</label>
                                                    <input type="password" id="password" name="password" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="confirmPassword">Confirm Password*</label>
                                                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="firstName">First Name*</label>
                                                    <input type="text" id="firstName" name="firstName" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="lastName">Last Name*</label>
                                                    <input type="text" id="lastName" name="lastName" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="birthDate">Birth Date*</label>
                                                    <input type="date" id="birthDate" name="birthDate" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="email">Email*</label>
                                                    <input type="email" id="email" name="email" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="country">Country*</label>
                                                    <input type="text" id="country" name="country" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="phone">Phone*</label>
                                                    <input type="tel" id="phone" name="phone" required>
                                                </div>
                                            </div>
                                            <input type="submit" value="Submit">
                                        </form>
                                        <p>Already a member? <a href="#">Login</a></p>
                                    </div>
                                </div>
                                
                                
                                <!--end form-->
                                
                                </div>
                                </div>
                                      
                                </div>
                            </div> <!-- navbar collapse -->
                        </nav> <!-- navbar -->
  

                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </div> <!-- navbar area -->

        <div id="home" class="header-hero bg_cover d-flex align-items-center" style="background-image: url(assets/img/metro_empires_01.jpg)">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="header-hero-content text-center">
                            <h3 class="header-title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s">Contact Us</h3>
                            <!-- <ul>
                                <li><a href="https://rebrand.ly/fintess-ud" rel="nofollow" target="_blank" class="main-btn wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.8s">Purchase Now</a></li>
                                <li><a href="javascript:void(0)" class="main-btn main-btn-2 wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="1.2s">Learn More</a></li>
                            </ul> -->
                        </div> <!-- header hero content -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </div> <!-- header hero -->
    </header>
<br><br>
    <!--====== HEADER PART ENDS ======-->
    
    
    <!--====== CONTACT PART START ======-->
    
    <section id="contact" class="contact_area ">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact_form pt-105 pb-120">
                        <div class="section_title pb-25">
                            <span class="line"></span>
                            <h3 class="title">Get in Touch</h3>
                        </div>
                         <!-- section title -->
                         
                        <!-- Display the alert message if it exists -->
                        <?php if ($alert_message): ?>
                            <?php echo $alert_message; ?>
                        <?php endif; ?>
                        
                        <form id="contact-form" method="post">
                            <div class="single_form">
                                <input type="text" name="name" placeholder="Name" required>
                            </div> 
                            <div class="single_form">
                                <input type="text" name="email" placeholder="Email" required>
                            </div> 
                            <div class="single_form">
                                <input type="text" name="subject" placeholder="Subject" required>
                            </div> 
                            <div class="single_form">
                                <textarea name="message" placeholder="Message" required></textarea>
                            </div> 
                            <p class="form-message"></p>
                            <div class="single_form">
                                <button class="main-btn" name="send">SUBMIT</button>
                            </div> 
                        </form>
                        <p>Contact <a href="tel:+914954019933">+91 495 401 99 33</a> &nbsp to book directly or for advice
                        <br>  Mail us at <a href="mailto:hotelmetroempires@gmail.com">hotelmetroempires@gmail.com</a>
                        <div class="text-break">
                            Address:
                            <address>
                                P. V SWAMI ROAD, CALICUT - 673002
                            </address>
                        </div>
                        
                        </p>
                    </div> <!-- contact form -->
                </div>
            </div>  <!-- row -->
        </div> <!-- container -->
        
        <div class="contact_map">
            <div class="gmap_canvas">                            
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3913.217011420115!2d75.78403661390472!3d11.245440353438333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba659c474beba4f%3A0x42b967de43b62738!2sMetro%20Empires%20Hotel!5e0!3m2!1sen!2sin!4v1639829923505!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div> <!-- contact map -->
    </section>


    <!--====== CONTACT PART ENDS ======-->
    
    <!--====== FOOTER PART START ======-->
    
    <section id="footer" class="footer_area">

        
        <div class="footer_copyright">
            <div class="container">
                <div class="copyright text-center">
                    <p>Copyright &copy; 2021. Designed and Developed by <a href="https://lestora.com" rel="nofollow">Lestora Technologies</a></p>
                </div>  <!-- copyright -->
            </div> <!-- container -->
        </div> <!-- footer copyright -->
    </section>
    
    <!--====== FOOTER PART ENDS ======-->
    
    <!--====== BACK TOP TOP PART START ======-->

    <a href="#" class="back-to-top"><i class="lni lni-chevron-up"></i></a>

    <!--====== BACK TOP TOP PART ENDS ======-->
    
    <!--====== PART START ======-->
    
<!--
    <section class="">
        <div class="container">
            <div class="row">
                <div class="col-lg-"></div>
            </div>
        </div>
    </section>
-->
    
    <!--====== PART ENDS ======-->







    <!--====== Jquery js ======-->
    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/vendor/modernizr-3.7.1.min.js"></script>
    
    <!--====== Bootstrap js ======-->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.4.5.2.min.js"></script>
    <!-- Bootstrap JS (with Popper.js) -->
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    
    <!--====== Slick js ======-->
    <script src="assets/js/slick.min.js"></script>
    
    <!--====== Ajax Contact js ======-->
    <script src="assets/js/ajax-contact.js"></script>
    
    <!--====== Counter Up js ======-->
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    
    <!--====== Magnific Popup js ======-->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    
    <!--====== Scrolling Nav js ======-->
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/scrolling-nav.js"></script>
    
    <!--====== Main js ======-->
    <script src="assets/js/main.js"></script>
    <script src="script.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>
