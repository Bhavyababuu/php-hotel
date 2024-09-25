<?php

require('./Admin/inc/db_config.php');
require('./Admin/inc/essentials.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Handle login
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            echo "<script>
                    alert('Login successful! ');
                    window.location.href = 'index.html';
                </script>";
        } else {
            echo "Invalid credentials.";
        }
    } elseif (isset($_POST['signup'])) {
        // Handle signup
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $birthDate = $_POST['birthDate'];

        $sql = "INSERT INTO users (username, password, email, phone, birth_date) 
                VALUES ('$user', '$pass', '$email', '$phone', '$birthDate')";

        if ($con->query($sql) === TRUE) {
            // Output JavaScript to display alert and redirect
            echo "<script>
                    alert('Signup successful! Redirecting to the homepage...');
                    window.location.href = 'index.html';
                </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }

    $con->close();
}
?>

<!-- Login Form -->
<div id="loginPopup" class="popup">
    <div class="popup-content">
        <span class="close-btn">&times;</span>
        <h2><i class="bi bi-person-circle"></i> User Login</h2>
        <form action="user.php" method="post">
            <input type="hidden" name="login" value="1">
            <label for="loginUsername">Username</label>
            <input type="text" id="loginUsername" name="username" required>
            <label for="loginPassword">Password</label>
            <input type="password" id="loginPassword" name="password" required>
            <button type="submit">Login</button>
            <a href="#" class="text-secondary text-decoration-none">Forgot password?</a>
        </form>
    </div>
</div>

<!-- Signup Form -->
<div id="signupPopup" class="popup">
    <div class="popup-content">
        <span class="close-btn">&times;</span>
        <h2><i class="bi bi-person-circle"></i> Sign Up</h2>
        <form action="user.php" method="post">
            <input type="hidden" name="signup" value="1">
            <label for="signupUsername">Username</label>
            <input type="text" id="signupUsername" name="username" required>
            <label for="signupPassword">Password</label>
            <input type="password" id="signupPassword" name="password" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" required>
            <label for="birthDate">Birth Date</label>
            <input type="date" id="birthDate" name="birthDate" required>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</div>

<script>
// Optional JavaScript to handle popup close button if needed
document.querySelectorAll('.close-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.popup').style.display = 'none';
    });
});

// Optional JavaScript to show the relevant popup
function showPopup(popupId) {
    document.getElementById(popupId).style.display = 'block';
}

// Initially display signup popup
showPopup('signupPopup');
</script>
