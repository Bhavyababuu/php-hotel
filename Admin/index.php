<?php
require('inc/db_config.php');
require('inc/essentials.php');
session_start(); // Start the session here

if ((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {

    redirect('dashboard.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-form {
            max-width: 400px;
            margin: 120px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        .login-form h4 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            color: #333;
        }
        .login-form .form-control {
            height: 45px;
            font-size: 16px;
            border-radius: 5px;
        }
        .login-form .btn {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border-radius: 5px;
            background-color: rgb(179, 77, 9);
            border-color:  rgb(179, 77, 9);
        }
        .login-form .btn:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .custom-alert {
            position: fixed;
            top: 25px;
            right: 25px;
        }
    </style>
</head>
<body class="bg-light">
<div class="login-form">
    <form method="post">
        <h4>ADMIN PANEL</h4>
        <div>
            <div class="mb-3">
                <input name="admin_name" type="text" id="email" class="form-control text-center" placeholder="Admin name" shadow-none required>
            </div>
            <div class="mb-4">
                <input name="admin_pass" type="password" id="password" class="form-control" placeholder="Password" shadow-none required>
            </div>
            <button name="login" type="submit" class="btn text-white shadow-none">Login</button>
        </div>
    </form>
</div>

<?php
if (isset($_POST['login'])) {
    $frm_data = filter_input_array(INPUT_POST, [
        'admin_name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'admin_pass' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ]);
    
    // Ensure that the column names match those in your database
    $query = "SELECT * FROM `admin_cred` WHERE `admin-name` = ? AND `admin-pass` = ?";
    $values = [$frm_data['admin_name'], $frm_data['admin_pass']];
    $datatypes = "ss";
    
    $res = select($query, $values, $datatypes);

    if ($res->num_rows == 1) {
        $row = mysqli_fetch_assoc($res);
        session_start();
        $_SESSION['adminLogin'] = true;
        $_SESSION['adminId'] = $row['s_no'];
        redirect('dashboard.php');
    } else {
        alert('error', 'Login failed - Invalid Credentials');
    }
}
?>
<!-- Include your scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
