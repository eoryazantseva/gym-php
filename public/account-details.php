<?php
session_start();

require_once "../config.php";

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "You have to log in first";
    header('location: login.php');
    exit();
}

$conn = getConnection();

if (isset($_POST['update_details'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['cust_phone']);
    $email_changed = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "UPDATE users SET first_name='$first_name', last_name='$last_name', phone='$phone', email='$email_changed' WHERE email='{$_SESSION['email']}'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Personal Details Updated";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['message'] = "Personal Details Not Updated";
        header("Location: dashboard.php");
        exit();
    }
}

require "partials/header.php";
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 col-sm-12 order-md-last">
            <?php include('partials/message.php'); ?>

            <?php
            $query = "SELECT * FROM users WHERE email='{$_SESSION['email']}'";
            $result = mysqli_query($conn, $query);
            if ($user = mysqli_fetch_assoc($result)) {
            ?>

            <form class="my-4 mb-sm-3" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group mb-3">
                    <label for="first_name" class="form-label">First Name:</label>
                    <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="cust_phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" name="cust_phone" value="<?= htmlspecialchars($user['phone']); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']); ?>">
                </div>
                <button type="submit" class="btn btn-primary text-uppercase" name="update_details">Update Details</button>
                <a href="index.php" class="btn btn-primary text-uppercase">Cancel</a>
            </form>

            <?php
            } else {
                echo "<p>User details not found.</p>";
            }
            ?>
        </div>
        <!-- Sidebar starts -->
        <div class="col-md-4 col-sm-12 d-flex flex-column flex-shrink-0 p-3 bg-light">
            <span class="fs-4 d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">My Account</span>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link link-dark" aria-current="dashboard">
                        <i class="fa-solid fa-gauge"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="class_bookings.php" class="nav-link link-dark">
                        <i class="fa-solid fa-calendar-check"></i> My Classes
                    </a>
                </li>
                <li>
                    <a href="account-details.php" class="nav-link active">
                        <i class="fa-solid fa-circle-info"></i> Account details
                    </a>
                </li>
                <?php if (isset($_SESSION['email'])) : ?>
                    <li>
                        <a href="?logout" class="nav-link link-dark"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log out</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- Sidebar ends -->
    </div>
</div>
<!-- FOOTER STARTS -->
<?php require "partials/footer.php"; ?>
