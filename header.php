<?php 
session_start(); 
?>

<!-- Header with navigation -->
<header class="header-area">
    <div class="navbar-area">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="index.php">
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
                            <a class="page-scroll" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="page-scroll" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="page-scroll" href="room.php">Room</a>
                        </li>
                        <li class="nav-item">
                            <a class="page-scroll" href="contact.php">Contact</a>
                        </li>
                    </ul>

                    <!-- Check if user is logged in -->
                    <?php if (isset($_SESSION['username'])): ?>
                        <!-- Show Profile Link -->
                        <div class="d-flex align-items-center">
                            <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profile" height="30px" class="rounded-circle mr-2">
                            <a href="profile.php" class="btn btn-outline-secondary">Profile</a>
                            <a href="logout.php" class="btn btn-outline-danger ml-2">Logout</a>
                        </div>
                    <?php else: ?>
                        <!-- Show Login and Signup Buttons -->
                        <div class="d-flex">
                            <button class="btn btn-outline-primary" id="loginBtn">Login</button>
                            <button class="btn btn-primary ml-2" id="signupBtn">Sign Up</button>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </div>
</header>
