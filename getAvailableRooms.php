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
    echo '<div class="col-lg-12 col-md-6">';
    echo '<div class="single_courses mt-30">';
    echo '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($row['title']) . '" class="img-fluid" style="width:900px;">';
    echo '<h4 class="title"><a href="javascript:void(0)">' . htmlspecialchars($row['title']) . '</a></h4>';
    echo '<hr>';
    echo '<strong>';

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
    echo '</strong><br>';
    echo '<dl class="row mt-2">';
    echo '<dt class="col-sm-5">Capacity</dt>';
    echo '<dd class="col-sm-7">' . htmlspecialchars($row['capacity']) . '</dd>';
    echo '<dt class="col-sm-5">Extra Person</dt>';
    echo '<dd class="col-sm-7">₹' . htmlspecialchars($row['extra_person_cost']) . '</dd>';
    echo '<dt class="col-sm-5">Bed Type</dt>';
    echo '<dd class="col-sm-7">' . htmlspecialchars($row['bed_type']) . '</dd>';
    echo '<dt class="col-sm-5">Services</dt>';
    echo '<dd class="col-sm-7">' . htmlspecialchars($row['services']) . '</dd>';
    echo '</dl>';
    echo '<div class="text-center mt-4">';
    echo '<a href="confirm_booking.php?room_id=' . htmlspecialchars($row['id']) . '" class="btn btn-primary">Book Now</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>
