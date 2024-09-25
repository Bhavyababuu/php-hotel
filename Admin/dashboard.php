<?php
require('inc/db_config.php');
require('inc/essentials.php');

// Fetching order table items
$orderFetchQuery = "SELECT `id`, `order_id`, `name`, `phone`, `room_title`, `checkin`, `checkout`, `total_amount`, `created_at` FROM `order`";
$orderResult = $con->query($orderFetchQuery);
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
            max-width: 500px;
            margin-bottom: 20px;
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 30px auto;
            }
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
                                <a class="nav-link" href="user_query.php">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="settings.php">Settings</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <!-- Sidebar Navbar End -->

            <!-- Main Content -->
            <div class="col-lg-9">
                <h1>Order Details</h1>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive-md" style="height:450px; overflow-y: scroll; width:900px;">
                            <table class="table table-hover border">
                                <thead class="thead-dark sticky-top">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Room Title</th>
                                        <th scope="col">Check-in</th>
                                        <th scope="col">Check-out</th>
                                        <th scope="col">Total Amount</th>
                                        <th scope="col">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($orderResult->num_rows > 0) {
                                        // Fetch each row and display
                                        while ($row = $orderResult->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                                <td><?php echo htmlspecialchars($row['room_title']); ?></td>
                                                <td><?php echo htmlspecialchars($row['checkin']); ?></td>
                                                <td><?php echo htmlspecialchars($row['checkout']); ?></td>
                                                <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                            </tr>
                                    <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No orders found.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Additional Content or Forms Can Go Here -->
            </div>
            <!-- Main Content End -->
        </div>
    </div>

    <script src="../assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="../assets/js/vendor/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/vendor/jquery-migrate-3.3.0.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.4.5.2.min.js"></script>
</body>
</html>
