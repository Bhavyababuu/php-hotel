<?php
// Include database configuration and essentials
require('./Admin/inc/db_config.php');
require('./Admin/inc/essentials.php');

// Fetch room data based on the selected room ID
$roomId = $_GET['room_id']; // Ensure this ID is sanitized or validated
$query = "SELECT * FROM `rooms` WHERE `id` = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $roomId);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

$imagePath = str_replace('../', '', $room['image']); // Adjust path as necessary

// Initialize variables for payment form
$showPaymentForm = false;
$bookingDetails = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];
    $roomType = $_POST['room_type']; // Get the selected room type

    // Calculate the number of days
    $checkinDate = new DateTime($checkin);
    $checkoutDate = new DateTime($checkout);
    $interval = $checkinDate->diff($checkoutDate);
    $numberOfDays = $interval->days;

    // Get the base price per room type
    $pricePerDay = $room['price_' . $roomType]; // Ensure you have columns like 'price_single', 'price_double', 'price_triple'

    // Calculate the total number of people
    $totalPeople = $adults + $children;

    // Calculate the base amount for up to 3 people
    $baseAmount = $pricePerDay * $numberOfDays;

    // If more than 3 people, add 650 for each additional person
    $additionalCost = 0;
    if ($totalPeople > 3) {
        $additionalPeople = $totalPeople - 3;
        $additionalCost = $additionalPeople * 650;
    }

    // Calculate total amount
    $totalAmount = $baseAmount + $additionalCost;

    // Insert data into the payment table, including room_id
    $insertQuery = "INSERT INTO `payment` (`name`, `phone`, `room-type`, `checkin`, `checkout`, `adults`, `children`, `total_amount`, `room_id`, `created_at`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $insertStmt = $con->prepare($insertQuery);

    // Bind parameters, adding 'i' for room_id at the end
    $insertStmt->bind_param('sssssiidi', $name, $phone, $roomType, $checkin, $checkout, $adults, $children, $totalAmount, $roomId);

    // Execute the query
    if ($insertStmt->execute()) {
        // Prepare data for displaying payment form
        $bookingDetails = [
            'room_title' => $room['title'],
            'name' => $name,
            'phone' => $phone,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'total_amount' => $totalAmount
        ];
        $showPaymentForm = true;

        // Generate a unique order ID
        $orderId = uniqid('OID');

        // Insert data into the order table, including room_id
        $orderInsertQuery = "INSERT INTO `order` (`order_id`, `name`, `phone`, `room_title`, `checkin`, `checkout`, `total_amount`, `room_id`, `created_at`) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $orderInsertStmt = $con->prepare($orderInsertQuery);

        // Bind parameters, adding 'i' for room_id at the end
        $orderInsertStmt->bind_param('ssssssdi', $orderId, $name, $phone, $bookingDetails['room_title'], $checkin, $checkout, $totalAmount, $roomId);
        
        // Execute order table insertion
        if ($orderInsertStmt->execute()) {
            // Order details successfully saved
            // Additional success handling can be done here
        } else {
            // Error handling if the query fails
            echo "Error inserting order details: " . $orderInsertStmt->error;
        }

        // Close the order statement
        $orderInsertStmt->close();
    } else {
        // Display an error message if the query fails
        echo "Error: " . $insertStmt->error;
    }

    // Close the statement
    $insertStmt->close();
}

// Close the database connection
$con->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Metro Empire</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/bootstrap.4.5.2.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/default.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
        body{
  background-color: #fdfdfc; /* Fill background on hover */

        }
        .booking-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .room-image {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .room-image img {
            width: 500px;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .room-title {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .booking-details {
            flex: 1;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .booking-details h2,  {
            margin-bottom: 16px;
            font-size: 24px;
            text-align: center;
            margin-left:20px;
        }

        .booking-details form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border-radius: 4px;
        }

        .btn-primary:hover {
            background-color: #007bff;
            border-color: #004085;
        }

        .text-center {
            text-align: center;
        }

        .payment-details {
            display: none; /* Initially hide the payment form */
        }
        #room-title{
            font-size: 28px;
            color:#B34D09;
            font-family: "Lato", sans-serif;
            margin-left:20px;

        }
        .payment-details {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .payment-details h2 {
            color: #333;
            margin-bottom: 20px;
            margin: 10px 0;
        }
        .payment-details p {
            margin: 10px 0;
            font-size: 18px;
            color: #555;
        }
        .payment-details #room-title {
            color: #6f4f28; /* Brown color */
            font-size: 24px; /* Bigger size */
            font-weight: bold;
        }
        .payment-details .btn-primary {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .payment-details .btn-primary:hover {
            background-color: #0056b3;
        }

    </style>
    <script>
    function calculateTotal() {
        // Get the input values
        const name = document.getElementById('name').value;
        const phone = document.getElementById('phone').value;
        const roomType = document.getElementById('room-type').value;
        const checkin = document.getElementById('checkin').value;
        const checkout = document.getElementById('checkout').value;
        const adults = parseInt(document.getElementById('adults').value);
        const children = parseInt(document.getElementById('children').value);

        // Calculate the number of days
        const checkinDate = new Date(checkin);
        const checkoutDate = new Date(checkout);
        const numberOfDays = Math.floor((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));

        // Get the base price per room type
        const roomPrices = {
            'single': <?php echo $room['price_single']; ?>,
            'double': <?php echo $room['price_double']; ?>,
            'triple': <?php echo $room['price_triple']; ?>
        };
        const pricePerDay = roomPrices[roomType];

        // Calculate the total amount
        const totalPeople = adults + children;
        let baseAmount = pricePerDay * numberOfDays;
        let additionalCost = 0;
        if (totalPeople > 3) {
            additionalCost = (totalPeople - 3) * 650;
        }
        const totalAmount = baseAmount + additionalCost;

        // Display the total amount
        document.getElementById('total-amount').textContent = 'Total Amount: ' + totalAmount + ' USD';

        // Show the payment form
        document.querySelector('.booking-details').style.display = 'none';
        document.querySelector('.payment-details').style.display = 'block';

        // Set the values in the payment form
        document.getElementById('room-title').textContent = 'Room Type: ' + roomType.charAt(0).toUpperCase() + roomType.slice(1);
        document.getElementById('payment-adults').textContent = 'Adults: ' + adults;
        document.getElementById('payment-children').textContent = 'Children: ' + children;
        document.getElementById('payment-total').textContent = 'Total Amount: ' + totalAmount + ' USD';

        // Set input values for form submission
        document.getElementById('payment-name-input').value = name;
        document.getElementById('payment-phone-input').value = phone;
        document.getElementById('payment-room-type-input').value = roomType;
        document.getElementById('payment-checkin-input').value = checkin;
        document.getElementById('payment-checkout-input').value = checkout;
        document.getElementById('payment-total-amount-input').value = totalAmount;

        // Display the values on the payment form
        document.getElementById('payment-name').textContent = 'Name: ' + name;
        document.getElementById('payment-phone').textContent = 'Phone: ' + phone;
        document.getElementById('payment-checkin').textContent = 'Check-in: ' + checkin;
        document.getElementById('payment-checkout').textContent = 'Check-out: ' + checkout;
    }
</script>

</head>
<body>
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
        <h2><i class="bi bi-person-circle"></i> Sign Up</h2><br><br>
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
                    <label for="name">Name*</label>
                    <input type="text" id="name" name="name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="birthDate">Birth Date*</label>
                    <input type="date" id="birthDate" name="birthDate" required>
                </div>
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="country">Country*</label>
                    <input type="text" id="country" name="country" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone*</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="profileImage">Profile Image*</label>
                    <input type="file" id="profileImage" name="profileImage" accept="image/*" required>
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

        <!-- <div id="home" class="header-hero bg_cover d-flex align-items-center" style="background-image: url(assets/img/metro_empires_01.jpg)">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="header-hero-content text-center">
                            <h3 class="header-title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s">Welcome</h3>
                            <p class="wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.5s">to the Metro Empires.</p>
                            <!-- <ul>
                                <li><a href="https://rebrand.ly/fintess-ud" rel="nofollow" target="_blank" class="main-btn wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.8s">Purchase Now</a></li>
                                <li><a href="javascript:void(0)" class="main-btn main-btn-2 wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="1.2s">Learn More</a></li>
                            </ul> -->
                        </div> <!-- header hero content -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </div> <!-- header hero -->
    </header> -->
<br><br><br><br>

    <!-- Booking Details -->
<!-- Booking Form and Payment Form HTML -->
<div class="container booking-container">
    <!-- Room Image and Title -->
    <div class="room-image">
        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($room['title']); ?>">
        <div class="room-title"><?php echo htmlspecialchars($room['title']); ?></div>
    </div>
    <!-- Booking Details -->
    <div class="booking-details">
        <h2>Booking Details</h2><br>
        <!-- Form submits to the same page -->
        <?php if (!$showPaymentForm): ?>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" name="phone" id="phone" placeholder="Enter your phone number" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="room-type">Room Type:</label>
                        <select name="room_type" id="room-type" required>
                            <option value="single">Single</option>
                            <option value="double">Double</option>
                            <option value="triple">Triple</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="checkin">Check-in:</label>
                        <input type="date" name="checkin" id="checkin" required>
                    </div>
                    <div class="form-group">
                        <label for="checkout">Check-out:</label>
                        <input type="date" name="checkout" id="checkout" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="adults">Adults:</label>
                        <select name="adults" id="adults" required>
                            <option value="1">1 Adult</option>
                            <option value="2">2 Adults</option>
                            <option value="3">3 Adults</option>
                            <option value="4">4 Adults</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="children">Children:</label>
                        <select name="children" id="children" required>
                            <option value="0">0 Child</option>
                            <option value="1">1 Child</option>
                            <option value="2">2 Children</option>
                            <option value="3">3 Children</option>
                            <option value="4">4 Children</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <p id="total-amount"></p>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Book Now</button>
            </form>



            <?php else: ?>
    <!-- Payment Form -->
    <p><strong>Room Title:</strong> <?php echo htmlspecialchars($bookingDetails['room_title']); ?></p>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($bookingDetails['name']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($bookingDetails['phone']); ?></p>
    <p><strong>Check-in:</strong> <?php echo htmlspecialchars($bookingDetails['checkin']); ?></p>
    <p><strong>Check-out:</strong> <?php echo htmlspecialchars($bookingDetails['checkout']); ?></p>
    <p><strong>Total Amount:</strong> ₹<?php echo number_format($bookingDetails['total_amount'], 2); ?></p>

    <form method="POST" action="https://www.example.com/success/" class="pay-form">
        <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($bookingDetails['total_amount']); ?>"><br>
        <input type="hidden" name="room_title" value="<?php echo htmlspecialchars($bookingDetails['room_title']); ?>"><br>
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($bookingDetails['name']); ?>"><br>
        <input type="hidden" name="phone" value="<?php echo htmlspecialchars($bookingDetails['phone']); ?>"><br>
        <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($bookingDetails['checkin']); ?>"><br>
        <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($bookingDetails['checkout']); ?>"><br><br>

        <!-- Razorpay Pay Now Button -->
        <script
            src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="rzp_test_js2MEhBCKiH8p0" <!-- Your Razorpay API key -->
            data-amount="<?php echo $bookingDetails['total_amount'] * 100; ?>" <!-- Amount in paise -->
            data-currency="INR"
            data-id="<?php echo $orderId; ?>" <!-- Unique order ID -->
            data-buttontext="Pay Now"
            data-name="Metro Empire"
            data-description="Hotel Booking System"
            data-image="https://example.com/your_logo.jpg"
            data-prefill.name="<?php echo htmlspecialchars($bookingDetails['name']); ?>"
            data-prefill.contact="<?php echo htmlspecialchars($bookingDetails['phone']); ?>"
            data-theme.color="#F37254"
        ></script>
        <input type="hidden" custom="Hidden Element" name="hidden"/>
    </form>

<?php endif; ?>


</div>
</div>

    
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
