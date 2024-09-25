<?php
require('inc/db_config.php');
require('inc/essentials.php');

// Fetch user queries from the database
$query = "SELECT * FROM user_query ORDER BY date DESC";
$result = mysqli_query($con, $query);

// Handle delete request
if (isset($_GET['del'])) {
    $frm_data = filteration($_GET);
    $query = "DELETE FROM `user_query` WHERE `s_no` = ?";
    $stmt = $con->prepare($query);
    if ($stmt) {
        $stmt->bind_param('i', $frm_data['del']);
        if ($stmt->execute()) {
            alert('success', 'Record deleted successfully');
        } else {
            alert('error', 'Failed to delete record');
        }
        $stmt->close();
    } else {
        alert('error', 'Failed to prepare statement');
    }
}

// Handle seen request
if (isset($_GET['seen'])) {
    $frm_data = filteration($_GET);
    if ($frm_data['seen'] == 'all') {
        // Handle 'all' case if needed
    } else {
        $q = "UPDATE `user_query` SET `seen` = ? WHERE `s_no` = ?";
        $stmt = $con->prepare($q);
        if ($stmt) {
            $seen = 1;
            $stmt->bind_param('ii', $seen, $frm_data['seen']);
            if ($stmt->execute()) {
                alert('success', 'Marked as read');
            } else {
                alert('error', 'Operation failed');
            }
            $stmt->close();
        } else {
            alert('error', 'Failed to prepare statement');
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
                <h1>User Query</h1>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive-md" style="height:450px; overflow-y: scroll; width:900px;">
                            <table class="table table-hover border">
                                <thead class="thead-dark sticky-top">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Message</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 1; // Initialize count
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $seen = '';
                                    if ($row['seen'] != 1){
                                        $seen = "<a href='user_query.php?seen=" . $row['s_no'] . "' class='btn btn-sm rounded-pill btn-primary'>Mark as Read</a>";
                                    }
                                    $seen .= "<a href='javascript:void(0);' class='btn btn-sm rounded-pill btn-danger mt-2' onclick='confirmDelete(" . $row['s_no'] . ")'>Delete</a>";

                                    echo "<tr>";
                                    echo "<th scope='row'>" . $count++ . "</th>";
                                    echo "<td>" . htmlspecialchars($row['name'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['subject'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['message'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['date'] ?? '') . "</td>";
                                    echo "<td>" . $seen . "</td>";
                                    echo "</tr>";
                                }
                                ?>

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
    <script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            window.location.href = 'user_query.php?del=' + id;
        }
    }
    </script>

    <script src="../assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="../assets/js/vendor/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/vendor/jquery-migrate-3.3.0.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.4.5.2.min.js"></script>
</body>
</html>
