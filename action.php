<?php
// Include your database connection file
require('./Admin/inc/db_config.php');
require('./Admin/inc/essentials.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $room_type = $_POST['room_type'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];

    // Calculate the total amount based on your criteria
    // Replace this with actual calculation logic
    $total_amount = calculateTotalAmount($room_type, $checkin, $checkout, $adults, $children);

    // Prepare SQL query to insert data into payment table
    $sql = "INSERT INTO payment (name, phone, room_type, checkin, checkout, adults, children, total_amount) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the SQL query
        $stmt->bind_param("sssssiid", $name, $phone, $room_type, $checkin, $checkout, $adults, $children, $total_amount);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to a success page or display a success message
            echo "Booking successful!";
            // header('Location: booking_success.php'); // Uncomment to redirect to a success page
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $con->error;
    }

    // Close the database connection
    $con->close();
}

// Function to calculate total amount
function calculateTotalAmount($room_type, $checkin, $checkout, $adults, $children) {
    // Replace with your own calculation logic based on room type and other factors
    $days = (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24); // Calculate the number of days
    $base_price = 100; // Base price per night (adjust this value)
    
    switch ($room_type) {
        case 'single':
            $rate = 100;
            break;
        case 'double':
            $rate = 150;
            break;
        case 'triple':
            $rate = 200;
            break;
        default:
            $rate = 100;
            break;
    }

    $total = $days * $rate * ($adults + ($children * 0.5)); // Simplified calculation
    return $total;
}
?>
