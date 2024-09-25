<?php
require('inc/db_config.php');
require('inc/essentials.php');

// Fetch room data from the database
$query = "SELECT * FROM `rooms`";
$result = mysqli_query($con, $query);

// Handle form submission for adding a new room
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_room') {
        // Sanitize input
        $title = mysqli_real_escape_string($con, $_POST['title']);
        $image = mysqli_real_escape_string($con, $_POST['image']);
        $price_single = mysqli_real_escape_string($con, $_POST['price_single']);
        $price_double = mysqli_real_escape_string($con, $_POST['price_double']);
        $price_triple = mysqli_real_escape_string($con, $_POST['price_triple']);
        $capacity = mysqli_real_escape_string($con, $_POST['capacity']);
        $extra_person_cost = mysqli_real_escape_string($con, $_POST['extra_person_cost']);
        $bed_type = mysqli_real_escape_string($con, $_POST['bed_type']);
        $services = mysqli_real_escape_string($con, $_POST['services']);

        // Insert into database
        $query = "INSERT INTO rooms (title, image, price_single, price_double, price_triple, capacity, extra_person_cost, bed_type, services)
                  VALUES ('$title', '$image', '$price_single', '$price_double', '$price_triple', '$capacity', '$extra_person_cost', '$bed_type', '$services')";

        if (mysqli_query($con, $query)) {
            header('Location: ' . $_SERVER['PHP_SELF']); // Redirect to the same page
            exit();
        } else {
            echo '<script>alert("Error: ' . mysqli_error($con) . '");</script>';
        }
    } elseif ($_POST['action'] === 'delete_room') {
        $room_id = intval($_POST['room_id']);
        
        // Delete from database
        $query = "DELETE FROM rooms WHERE id = $room_id";

        if (mysqli_query($con, $query)) {
            header('Location: ' . $_SERVER['PHP_SELF']); // Redirect to the same page
            exit();
        } else {
            echo '<script>alert("Error: ' . mysqli_error($con) . '");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="../assets/css/magnific-popup.css">
    <link rel="stylesheet" href="../assets/css/slick.css">
    <link rel="stylesheet" href="../assets/css/LineIcons.2.0.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.4.5.2.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/default.css">
    <style>
        .navbar {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 24px;
            color: #343a40;
        }

        .navbar-toggler {
            margin-bottom: 15px;
        }

        .nav-pills .nav-item {
            width: 100%;
            margin-bottom: 10px;
        }

        .nav-pills .nav-link {
            color: #495057;
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-pills .nav-link:hover {
            background-color: #eec0a1;
            color: #ffffff;
        }

        .nav-pills .nav-link.active {
            background-color: #eec0a1;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .navbar {
                margin-bottom: 20px;
            }

            .navbar-toggler {
                margin: 0 auto 15px auto;
            }

            .navbar-collapse {
                text-align: center;
            }
        }

        .card {
            width: 100%;
            max-width: 900px;
            margin-bottom: 20px;
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 30px auto;
            }
        }

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            overflow: auto;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        #roomTable th, #roomTable td {
            padding: 8px;
            text-align: left;
        }

        #roomTable {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <div class="container-fluid text-light p-3 d-flex align-items-center justify-content-between customs-bg" style="background-color: #eec0a1;">
        <img src="../assets/img/logo.svg" alt="Logo" height="40px">
        <a href="logout.php" class="btn btn-light btn-sm">LOGOUT</a>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Navbar -->
            <div class="col-lg-3 mb-4" id="dashboard-menu">
                <nav class="navbar navbar-expand-lg navbar-light rounded-shadow flex-lg-column align-items-stretch p-0" style="background-color: #eec0a1;">
                    <h4 class="navbar-brand text-center mb-4">ADMIN PANEL</h4>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminDropdown" aria-controls="adminDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="adminDropdown">
                        <ul class="nav nav-pills flex-column p-3">
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="rooms.php">Rooms</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Settings</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <!-- Sidebar Navbar End -->

            <!-- Main Content -->
            <div class="col-lg-9">
                <h1 class="mb-4">Manage Rooms</h1>

                <!-- Add Room Button -->
                <button id="addRoomBtn" class="btn btn-primary mb-3">Add Room</button>

                <!-- Display rooms -->
                  <!-- Display rooms -->
<div class="row">
    <?php while ($row = mysqli_fetch_assoc($result)) {

         ?>
        <div class="col-lg-6 col-md-6">
            <div class="single_courses mt-30">
                <img src="<?php echo $row['image']; ?>" alt="courses" class="img-fluid">
                <h4 class="title"><a href="javascript:void(0)"><?php echo $row['title']; ?></a></h4>
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
                    <dd class="col-sm-7"><?php echo $row['capacity']; ?></dd>
                    <dt class="col-sm-5">Extra Person</dt>
                    <dd class="col-sm-7">₹<?php echo $row['extra_person_cost']; ?></dd>
                    <dt class="col-sm-5">Bed Type</dt>
                    <dd class="col-sm-7"><?php echo $row['bed_type']; ?></dd>
                    <dt class="col-sm-5">Services</dt>
                    <dd class="col-sm-7"><?php echo $row['services']; ?></dd>
                </dl>

                <!-- Delete Button -->
                <form method="POST" class="d-inline">
                    <input type="hidden" name="action" value="delete_room">
                    <input type="hidden" name="room_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    <?php } ?>
</div>

            </div>
            <!-- Main Content End -->
        </div>
    </div>

    <!-- Add Room Popup -->
    <div id="addRoomPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeAddRoomPopup()">&times;</span>
            <h2>Add Room</h2>
            <form method="POST" id="addRoomForm">
                <input type="hidden" name="action" value="add_room">
                <div class="form-group">
                    <label for="title">Room Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="image">Image URL</label>
                    <input type="text" id="image" name="image" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price_single">Single Price</label>
                    <input type="number" id="price_single" name="price_single" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price_double">Double Price</label>
                    <input type="number" id="price_double" name="price_double" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price_triple">Triple Price</label>
                    <input type="number" id="price_triple" name="price_triple" class="form-control">
                </div>
                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <input type="number" id="capacity" name="capacity" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="extra_person_cost">Extra Person Cost</label>
                    <input type="number" id="extra_person_cost" name="extra_person_cost" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="bed_type">Bed Type</label>
                    <input type="text" id="bed_type" name="bed_type" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="services">Services</label>
                    <input type="text" id="services" name="services" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Add Room</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('addRoomBtn').addEventListener('click', function() {
            document.getElementById('addRoomPopup').style.display = 'flex';
        });

        function closeAddRoomPopup() {
            document.getElementById('addRoomPopup').style.display = 'none';
        }

        // Close the popup when clicking outside of the popup content
        window.onclick = function(event) {
            if (event.target == document.getElementById('addRoomPopup')) {
                closeAddRoomPopup();
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
