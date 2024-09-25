<?php
require('inc/db_config.php');
require('inc/essentials.php');

// Handle form submission for editing general settings
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_general'])) {
    $site_title = mysqli_real_escape_string($con, $_POST['site_title']);
    $site_about = mysqli_real_escape_string($con, $_POST['site_about']);

    // Update settings in the database
    $update_query = "UPDATE settings SET site_title='$site_title', site_about='$site_about' WHERE s_no=1";
    $update_result = mysqli_query($con, $update_query);
    
    if (!$update_result) {
        die("Update failed: " . mysqli_error($con));
    }
}

// Handle form submission for editing contact settings
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_contact'])) {
    $contact_address = mysqli_real_escape_string($con, $_POST['contact_address']);
    $contact_gmap = mysqli_real_escape_string($con, $_POST['contact_gmap']);
    $contact_phone = mysqli_real_escape_string($con, $_POST['contact_phone']);
    $contact_email = mysqli_real_escape_string($con, $_POST['contact_email']);

    // Update contact settings in the database
    $update_contact_query = "UPDATE contact SET address='$contact_address', gmap='$contact_gmap', phone='$contact_phone', email='$contact_email' WHERE s_no=1";
    $update_contact_result = mysqli_query($con, $update_contact_query);
    
    if (!$update_contact_result) {
        die("Update failed: " . mysqli_error($con));
    }
}

// Fetch general settings data
$query = "SELECT site_title, site_about FROM settings WHERE s_no = 1";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

if (mysqli_num_rows($result) > 0) {
    $settings = mysqli_fetch_assoc($result);
    $site_title = $settings['site_title'];
    $site_about = $settings['site_about'];
} else {
    $site_title = "Default Title";
    $site_about = "Default About Us";
}

// Fetch contact data
$contact_query = "SELECT address, gmap, phone, email FROM contact WHERE s_no = 1";
$contact_result = mysqli_query($con, $contact_query);

if (!$contact_result) {
    die("Query failed: " . mysqli_error($con));
}

if (mysqli_num_rows($contact_result) > 0) {
    $contact = mysqli_fetch_assoc($contact_result);
    $contact_address = $contact['address'];
    $contact_gmap = $contact['gmap'];
    $contact_phone = $contact['phone'];
    $contact_email = $contact['email'];
} else {
    $contact_address = "123 Main Street, Anytown, USA";
    $contact_gmap = "https://www.google.com/maps";
    $contact_phone = "+1234567890";
    $contact_email = "example@example.com";
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
                                <a class="nav-link" href="#">Rooms</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="user">User_Query</a>
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
                <h1>Settings</h1>

                <!-- General Settings Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0">General Settings</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-toggle="modal" data-target="#general-s">
                                <i class="bi bi-pencil-square"></i>
                                Edit
                            </button>
                        </div>
                        <h6 class="card-subtitle mb-1 fw-bold">Site Title</h6>
                        <p class="card-text" id="site_title"><?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?></p>
                        <h6 class="card-subtitle mb-1 fw-bold">About us</h6>
                        <p class="card-text" id="site_about"><?php echo htmlspecialchars($site_about, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>

                <!-- Contact Settings Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0">Contact Settings</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-toggle="modal" data-target="#contact-s">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </div>
                        <!-- Address -->
                        <h6 class="card-subtitle mb-1 fw-bold">Address</h6>
                        <p class="card-text" id="contact_address"><?php echo htmlspecialchars($contact_address, ENT_QUOTES, 'UTF-8'); ?></p>
                        <!-- Google Map -->
                        <h6 class="card-subtitle mb-1 fw-bold">Google Map</h6>
                        <p class="card-text" id="contact_gmap"><?php echo htmlspecialchars($contact_gmap, ENT_QUOTES, 'UTF-8'); ?></p>
                        <!-- Phone Number -->
                        <h6 class="card-subtitle mb-1 fw-bold">Phone Number</h6>
                        <p class="card-text" id="contact_phone"><?php echo htmlspecialchars($contact_phone, ENT_QUOTES, 'UTF-8'); ?></p>
                        <!-- Email -->
                        <h6 class="card-subtitle mb-1 fw-bold">Email</h6>
                        <p class="card-text" id="contact_email"><?php echo htmlspecialchars($contact_email, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>

                <!-- General Settings Modal -->
                <div class="modal fade" id="general-s" tabindex="-1" aria-labelledby="generalModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="generalModalLabel">General Settings</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="siteTitle">Site Title</label>
                                        <input type="text" class="form-control" name="site_title" value="<?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="siteAbout">About Us</label>
                                        <textarea class="form-control" rows="3" name="site_about"><?php echo htmlspecialchars($site_about, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="update_general" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End of General Settings Modal -->

                <!-- Contact Settings Modal -->
                <div class="modal fade" id="contact-s" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="contactModalLabel">Contact Settings</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="contactAddress">Address</label>
                                        <textarea class="form-control" rows="2" name="contact_address"><?php echo htmlspecialchars($contact_address, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactGmap">Google Map Embed Code</label>
                                        <textarea class="form-control" rows="3" name="contact_gmap"><?php echo htmlspecialchars($contact_gmap, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactPhone">Phone Number</label>
                                        <input type="text" class="form-control" name="contact_phone" value="<?php echo htmlspecialchars($contact_phone, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactEmail">Email Address</label>
                                        <input type="email" class="form-control" name="contact_email" value="<?php echo htmlspecialchars($contact_email, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="update_contact" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End of Contact Settings Modal -->

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
