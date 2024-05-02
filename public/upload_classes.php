<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "You have to log in first";
    header('location: login.php');
    exit();
}

// Redirect if not an admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Access denied: You must be an admin to access this page.";
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
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Upload XML File for Classes</h5>
                </div>
                <div class="card-body">
                    <form action="data/import_classes.php" method="post" enctype="multipart/form-data" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="file" class="mr-2">Choose file:</label>
                            <input type="file" name="file" id="file" class="form-control-file">
                        </div>
                        <button type="submit" value="Upload" name="submit" class="btn btn-primary mb-2 ml-2">Upload</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 d-flex flex-column flex-shrink-0 p-3 bg-light">
            <span class="fs-4 d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">My Account</span>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <!-- Admin-specific links -->
                    <li class="nav-item">
                        <a href="#" class="nav-link link-dark">
                            <i class="fa-solid fa-gauge-high"></i> Admin Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="upload_classes.php" class="nav-link active">
                            <i class="fa-solid fa-upload"></i> Upload Classes XML
                        </a>
                    </li>
                    <li>
                        <a href="upload_members.php" class="nav-link link-dark">
                            <i class="fa-solid fa-upload"></i> Upload Members XML
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