<?php
session_start();
include_once "../../config.php"; // Ensure this includes your database connection settings

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Access denied: You must be an admin to access this page.";
    header('location: ../login.php');
    exit();
}

if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];
    $conn = getConnection();

    // Begin transaction to handle this operation as an atomic unit
    mysqli_begin_transaction($conn);

    try {
        // First, delete any bookings that depend on this schedule
        $stmt = mysqli_prepare($conn, "DELETE FROM bookings WHERE schedule_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $schedule_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Then, delete the schedule itself
        $stmt = mysqli_prepare($conn, "DELETE FROM class_schedule WHERE schedule_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $schedule_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // If all operations were successful, commit the transaction
        mysqli_commit($conn);

        $_SESSION['message'] = "Class and related bookings deleted successfully.";
    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($conn); // Roll back changes if any SQL operation fails
        $_SESSION['message'] = "Error deleting class: " . $exception->getMessage();
    }

    mysqli_close($conn);
    header('location: ../schedule.php');
    exit();
} else {
    $_SESSION['message'] = "No class was specified for deletion.";
    header('location: ../schedule.php');
    exit();
}
?>
