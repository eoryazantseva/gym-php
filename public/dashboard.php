<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "You have to log in first";
    header('location: login.php');
    exit();
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['email']);
    header("location: login.php");
    exit();
}

require "partials/header.php";
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 col-sm-12 order-md-last">
            <?php include('partials/message.php'); ?>
            <?php if (isset($_SESSION['email'])) : ?>
                <p class="d-none d-md-block">
                    Welcome <strong><?php echo $_SESSION['first_name']; ?></strong>!
                </p>
                <p class="d-none d-md-block">
                    <a style="color: red" href="?logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Click here to Logout</a>
                </p>
            <?php endif; ?>
        </div>

        <div class="col-md-4 col-sm-12 d-flex flex-column flex-shrink-0 p-3 bg-light">
            <span class="fs-4 d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">My Account</span>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <!-- Admin-specific links -->
                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="fa-solid fa-gauge-high"></i> Admin Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="upload_classes.php" class="nav-link link-dark">
                            <i class="fa-solid fa-upload"></i> Upload Classes XML
                        </a>
                    </li>
                    <li>
                        <a href="upload_schedule.php" class="nav-link link-dark">
                            <i class="fa-solid fa-upload"></i> Upload Schedule XML
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Links for regular users -->
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active">
                            <i class="fa-solid fa-gauge"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="class_bookings.php" class="nav-link link-dark">
                            <i class="fa-solid fa-calendar-check"></i> My Classes
                        </a>
                    </li>
                    <li>
                        <a href="account-details.php" class="nav-link link-dark">
                            <i class="fa-solid fa-circle-info"></i> Account details
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="?logout" class="nav-link link-dark"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log out</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php require "partials/footer.php"; ?>
