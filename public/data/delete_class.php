<?php
session_start();
include_once "../../config.php"; // Ensure this includes your database connection settings

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Access denied: You must be an admin to access this page.";
    header('location: ../login.php');
    exit();
}

// Check if schedule_id is present
if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    // Establish a database connection
    $conn = getConnection();

    // Prepare the SQL statement to avoid SQL injection
    $stmt = mysqli_prepare($conn, "DELETE FROM class_schedule WHERE schedule_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $schedule_id);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Class deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting class.";
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Redirect back to the schedule page or wherever you list classes
    header('location: ../schedule.php');
    exit();
} else {
    // Redirect them back with an error message if schedule_id wasn't provided
    $_SESSION['message'] = "No class was specified for deletion.";
    header('location: ../schedule.php');
    exit();
}
?>
