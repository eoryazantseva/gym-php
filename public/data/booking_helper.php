<?php
include_once "../config.php";

function getUserBookings($conn, $user_id) {
    $sql = "SELECT b.booking_id, c.name AS class_name, cs.date, t.name as trainer_name,
            DATE_FORMAT(cs.date, '%W, %d %M %Y') as formatted_date, 
            DATE_FORMAT(cs.start_time, '%H:%i') as start_time, 
            DATE_FORMAT(cs.end_time, '%H:%i') as end_time, 
            (cs.date >= CURDATE()) as is_upcoming
            FROM bookings b
            JOIN class_schedule cs ON b.schedule_id = cs.schedule_id
            JOIN classes c ON cs.class_id = c.class_id
            JOIN trainers t ON cs.trainer_id = t.trainer_id
            WHERE b.user_id = ?
            ORDER BY cs.date DESC, cs.start_time DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $bookings;
}

function cancelBooking($conn, $user_id, $booking_id) {
    // Check if the booking belongs to the user
    $sql = "SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $booking_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Proceed to cancel the booking
        $sql = "DELETE FROM bookings WHERE booking_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $booking_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return "Booking successfully cancelled.";
    } else {
        return "You do not have permission to cancel this booking.";
    }
}
