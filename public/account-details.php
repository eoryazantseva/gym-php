<?php
session_start();

require_once "data/config.php";

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "You have to log in first";
    header('location: login.php');
    exit();
}

$conn = getConnection();

if (isset($_POST['update_details'])) {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $phone = mysqli_real_escape_string($conn, $_POST['cust_phone']);
    $email_changed = mysqli_real_escape_string($conn, $_POST['cust_email']);

    $query = "UPDATE users SET first_name='$firstName', last_name='$lastName', phone='$phone', email='$email_changed' WHERE email='{$_SESSION['email']}'";
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

require "header.php";
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 col-sm-12 order-md-last">
            <?php include('message.php'); ?>

            <?php
            $query = "SELECT * FROM users WHERE email='{$_SESSION['email']}'";
            $result = mysqli_query($conn, $query);
            if ($user = mysqli_fetch_assoc($result)) {
            ?>

            <form class="my-4 mb-sm-3" action="account-details.php" method="post">
                <div class="form-group mb-3">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" name="firstName" value="<?= htmlspecialchars($user['first_name']); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" name="lastName" value="<?= htmlspecialchars($user['last_name']); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="cust_phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" name="cust_phone" value="<?= htmlspecialchars($user['phone']); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="cust_email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="cust_email" value="<?= htmlspecialchars($user['email']); ?>">
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
                    <a href="index.php" class="nav-link link-dark" aria-current="dashboard">
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
                <li>
                    <a href="#" class="nav-link link-dark">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar ends -->
    </div>
</div>
<!-- FOOTER STARTS -->
<?php require "footer.php"; ?>
