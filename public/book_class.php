<?php
session_start();
include_once 'data/config.php';  // Ensure this path is correct

if (!isset($_SESSION['email'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}

// Check if the schedule_id is present in the URL
if (isset($_GET['schedule_id'])) {
    $conn = getConnection();
    $schedule_id = mysqli_real_escape_string($conn, $_GET['schedule_id']);
    $user_id = $_SESSION['user_id'];  // Assuming you store user_id in session on login

    // Check if there are spots available for the class
    $query = "SELECT capacity FROM class_schedule WHERE schedule_id = '$schedule_id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['capacity'] > 0) {
            // Insert booking
            $insertQuery = "INSERT INTO bookings (user_id, schedule_id) VALUES ('$user_id', '$schedule_id')";
            if (mysqli_query($conn, $insertQuery)) {
                // Decrease the capacity
                $updateQuery = "UPDATE class_schedule SET capacity = capacity - 1 WHERE schedule_id = '$schedule_id'";
                mysqli_query($conn, $updateQuery);

                $_SESSION['message'] = "Booking successful!";
            } else {
                $_SESSION['message'] = "Booking failed: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['message'] = "No spots available.";
        }
    } else {
        $_SESSION['message'] = "Class does not exist.";
    }
    mysqli_close($conn);
} else {
    $_SESSION['message'] = "Invalid request.";
}

// Redirect back to the classes schedule page
header('Location: classes.php');
exit();
?>
