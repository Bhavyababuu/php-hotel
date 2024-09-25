<?php
// Include database configuration and essentials
require('./Admin/inc/db_config.php');
require('./Admin/inc/essentials.php');

// Retrieve input parameters from the request
$checkInDate = $_POST['checkInDate'];
$checkOutDate = $_POST['checkOutDate'];
$numAdults = $_POST['numAdults'];
$numChildren = $_POST['numChildren'];

// Corrected query to fetch available rooms based on the search criteria
$query = "SELECT * FROM `rooms` WHERE NOT EXISTS (
            SELECT * FROM `order` 
            WHERE rooms.id = order.room_id 
            AND order.checkin <= '$checkOutDate' 
            AND order.checkout >= '$checkInDate'
          )";

$result = mysqli_query($con, $query);

// Check for query errors
if (!$result) {
    die('Query Error: ' . mysqli_error($con));
}

// Display filtered rooms
while ($row = mysqli_fetch_assoc($result)) {
    $imagePath = str_replace('../', '', $row['image']);
    ?>
    <div class="col-lg-16 col-md-6" style="margin-left: 350px; margin-top:150px;">
        <div class="single_courses mt-30">
            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="img-fluid" style="width:900px;">
            <h4 class="title"><a href="javascript:void(0)"><?php echo htmlspecialchars($row['title']); ?></a></h4>
            <hr>
            <strong>
                <?php
                $display_text = '';
                if ($row['price_single'] > 0) {
                    $display_text .= 'Single - ₹' . $row['price_single'] . ' per day';
                }
                if ($row['price_double'] > 0) {
                    if ($display_text) $display_text .= ' | ';
                    $display_text .= 'Double - ₹' . $row['price_double'] . ' per day';
                }
                if ($row['price_triple'] > 0) {
                    if ($display_text) $display_text .= ' | ';
                    $display_text .= 'Triple - ₹' . $row['price_triple'] . ' per day';
                }
                echo $display_text;
                ?>
            </strong><br>
            <dl class="row mt-2">
                <dt class="col-sm-5">Capacity</dt>
                <dd class="col-sm-7"><?php echo htmlspecialchars($row['capacity']); ?></dd>
                <dt class="col-sm-5">Extra Person</dt>
                <dd class="col-sm-7">₹<?php echo htmlspecialchars($row['extra_person_cost']); ?></dd>
                <dt class="col-sm-5">Bed Type</dt>
                <dd class="col-sm-7"><?php echo htmlspecialchars($row['bed_type']); ?></dd>
                <dt class="col-sm-5">Services</dt>
                <dd class="col-sm-7"><?php echo htmlspecialchars($row['services']); ?></dd>
            </dl>
            <div class="text-center mt-4">
                <a href="confirm_booking.php?room_id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary">Book Now</a>
            </div>
        </div>
    </div>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Add your CSS file path -->
    <style>

        </style>
</head>
<body><br><br><br>
    <div class="col-lg-12 col-md-12 px-4">
    <div class="row" style="margin-left: 200px; margin-top:50px;">

            <div id="roomsContent" class="container mt-5 ">
                <!-- Available rooms will be displayed here -->
            </div>
            
        </div> <!-- End of wrapper row -->
    </div>
</body>
</html>
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
    <style>



        /* Additional styles for proper layout */
        #formContainer {
            #formContainer {
    position: relative; /* Ensure it's positioned in relation to its normal position */
    z-index: 10; /* Higher value than the hero section */
    background: white; /* Optionally set a background color to ensure visibility */
    padding: 20px; /* Optional padding */
}
        }

        .header-hero {
    position: relative; /* Ensure it's positioned in relation to its normal position */
    z-index: 1; /* Lower value than the form container */
}
    
.container{
    margin-left:100px;
}
    /* Style for filter section */
.border {
    border: 1px solid #ddd;
}

.bg-light {
    background-color: #f8f9fa;
}

.form-control {
    background-color: #e0dfdd;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 8px;
    font-size: 15px;
    font-weight: bold;
    font-family: cursive;
    color: #333;
    width: 100%;
    height: 50px;
    margin-top: 5px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    box-shadow: inset 0 0 5px dogger rgba(197, 114, 6, 0.1);
    border: 1px solid #744c02;

}

.form-label {
    font-size: 12px;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-family: 'cursive';
    text-shadow: 0 0 7px #fff,
     0 0 10px #fff,
      0 0 15px #e69d00,
      0 0 20px #e69d00,
      0 0 25px #e69d00,
      0 0 30px #e69d00,
      0 0 35px #e69d00,

       ;
}
/* Styles for the Navbar */
.navbarr {
    position: -webkit-sticky;
    position: sticky;
    left: 0; /* Stick to the left side */
    top: 140px; /* Distance from the top of the viewport */
    z-index: 1020;
    background-color: rgba(255, 255, 255, 0.7); /* Slight transparency */
    backdrop-filter: blur(5px); /* Optional: Blur background */
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    padding: 15px;
    animation: fade-in 1.5s ease-out;
    width: 250px; /* Fixed width for sidebar */
    max-height: calc(100vh - 140px); /* Ensure the form doesn't exceed viewport height */
    overflow-y: auto; /* Allow scrolling within the sidebar if needed */
}

/* Transparent background for inner content */
.bg-transparent {
    background-color: rgba(155, 154, 152, 0.8);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(5px);
    animation: fade-in 1.5s ease-out;
}

/* Button Styling */
#searchRoomsBtn {
    background-color: #e0dfdd;
    color: #aaa9a6;
    border: 1px solid #744c02;
    color: #333;
    font-weight: bold;
    border-radius: 10px;
    font-size: 14px;
    padding: 8px 15px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.3s ease, color 0.3s ease;
    height: 40px;
    margin-top: 4px;
    font-family: 'cursive';

    box-shadow: 0 5px 15px rgba(51, 51, 51, 0.2);
}

#searchRoomsBtn:hover {
    background-color: #333;
    color: #fff;
}

/* Animation for form appearance */
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

</style>
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
                            <li class="nav-item">
                                <a class="page-scroll" href="contact.php">Contact</a>
                            </li>
                        </ul>

                        <!-- Login and Signup Buttons -->
                        <div class="d-flex">
                            <button class="btn btn-outline-primary" id="loginBtn">Login</button>
                            <button class="btn btn-primary ml-2" id="signupBtn">Sign Up</button>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- <div id="home" class="header-hero bg_cover d-flex align-items-center" style="background-image: url(assets/img/metro_empires_01.jpg)">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="header-hero-content text-center">
                        <h3 class="header-title">Rooms</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
     -->
    <!-- Placeholder for Loading Forms -->
    <div id="formContainer"></div>
    

    
    
    <!--====== room PART START ======-->
    <br><br><br>
    <div class="container"  >
    <div class="row" style="margin-left: 200px;">
    <div id="roomsContent" class="container mt-5">
                      <!-- Available rooms will be displayed here -->
                    </div>
              </div>
    </div>

    
    <!--====== CONTACT PART START ======-->
    <br><br><br>
    <section id="contact" class="contact_area ">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact_form pt-105 pb-120">
                        <div class="section_title pb-25">
                            <span class="line"></span>
                            <h3 class="title">Get in Touch</h3>
                        </div> <!-- section title -->
                        <!-- <form id="contact-form" action="assets/contact.php" method="post">
                            <div class="single_form">
                                <input type="text" name="name" placeholder="Name">
                            </div> 
                            
                            <div class="single_form">
                                <input type="text" name="email" placeholder="Email">
                            </div> 
                            <div class="single_form">
                                <input type="text" name="subject" placeholder="Subject">
                            </div> 
                            <div class="single_form">
                                <textarea name="message" placeholder="Message"></textarea>
                            </div> 
                            <p class="form-message"></p>
                            <div class="single_form">
                                <button class="main-btn">SUBMIT</button>
                            </div> 
                        </form> -->
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load the forms when buttons are clicked
            $('#loginBtn').on('click', function () {
                $('#formContainer').load('user.php #loginPopup', function() {
                    $('#loginPopup').show();
                });
            });
    
            $('#signupBtn').on('click', function () {
                $('#formContainer').load('user.php #signupPopup', function() {
                    $('#signupPopup').show();
                });
            });
    
            // Close the form popup
            $(document).on('click', '.close-btn', function () {
                $(this).closest('.popup').hide();
            });
        });


</script>
    





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

    
</body>

</html>
