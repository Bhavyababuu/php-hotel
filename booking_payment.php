<?php
// Include database configuration and essentials
require('./Admin/inc/db_config.php');
require('./Admin/inc/essentials.php');

// Fetch the booking details from the query parameters
$days = isset($_GET['days']) ? intval($_GET['days']) : 0;
$amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;
$roomId = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

// Initialize default values
$imagePath = 'default-image.jpg'; // Placeholder image
$roomTitle = 'Unknown Room';

// Check if roomId is valid
if ($roomId > 0) {
    // Prepare and execute the query
    $query = "SELECT * FROM `rooms` WHERE `id` = ?";
    $stmt = $con->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . $con->error);
    }

    $stmt->bind_param('i', $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        die('Execute failed: ' . $stmt->error);
    }

    $room = $result->fetch_assoc();
    $stmt->close();

    // Check if room data is found
    if ($room) {
        $imagePath = str_replace('../', '', $room['image']); // Adjust path as necessary
        $roomTitle = htmlspecialchars($room['title']);
    }
}
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
    <style>
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

        .booking-details h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        .booking-details p {
            font-size: 18px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border-radius: 4px;
            text-align: center;
            display: block;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
    <div class="container booking-container">
        <!-- Room Image and Title -->
        <div class="room-image">
            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($roomTitle); ?>">
            <div class="room-title"><?php echo htmlspecialchars($roomTitle); ?></div>
        </div>

        <!-- Booking Details -->
        <div class="booking-details">
            <h2>Booking Summary</h2>
            <p>Total Days: <?php echo htmlspecialchars($days); ?></p>
            <p>Total Amount: ₹<?php echo htmlspecialchars(number_format($amount, 2)); ?></p>
            <a href="process_payment.php" class="btn-primary">Proceed to Payment</a>
        </div>
    </div>
</body>
</html>

