<?php
session_start();

require_once "data/booking_helper.php";

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "You have to log in first";
    header('location: login.php');
    exit();
}

$conn = getConnection();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];
$bookings = getUserBookings($conn, $user_id);


if (isset($_POST['booking_id'])) {
    require_once "data/booking_helper.php";
    $conn = getConnection();
    $user_id = $_SESSION['user_id'];
    $booking_id = $_POST['booking_id'];
    $message = cancelBooking($conn, $user_id, $booking_id);
    $_SESSION['message'] = $message;
    header('Location: class_bookings.php');
    exit();
}


require "partials/header.php";
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 col-sm-12 order-md-last">
            <?php include('partials/message.php'); ?>

            <main class="container mt-5">
                <h2 class="font-bold">My Classes</h2>
                <h4 class="mt-3 mb-3">Upcoming Classes</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Trainer</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                            <?php if ($booking['is_upcoming']): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['class_name']) ?></td>
                                <td><?= htmlspecialchars($booking['trainer_name']) ?></td>
                                <td><?= htmlspecialchars($booking['formatted_date']) ?></td>
                                <td><?= htmlspecialchars($booking['start_time']) . ' - ' . htmlspecialchars($booking['end_time']); ?></td>
                                <td>
                                    <form action="class_bookings.php" method="POST">
                                        <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                                        <button type="submit" class="btn btn-danger">Cancel Booking</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <h4 class="mt-3 mb-3">Past Classes</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Trainer</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                            <?php if (!$booking['is_upcoming']): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['class_name']) ?></td>
                                <td><?= htmlspecialchars($booking['trainer_name']) ?></td>
                                <td><?= htmlspecialchars($booking['formatted_date']) ?></td>
                                <td><?= htmlspecialchars($booking['start_time']) . ' - ' . htmlspecialchars($booking['end_time']); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="4">No classes booked yet.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>

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
                    <a href="class_bookings.php" class="nav-link active">
                        <i class="fa-solid fa-calendar-check"></i> My Classes
                    </a>
                </li>
                <li>
                    <a href="account-details.php" class="nav-link link-dark">
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

