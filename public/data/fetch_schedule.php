<?php
session_start();
include_once '../../config.php'; // Ensure correct path
include_once 'get_schedule.php'; // Assuming this path and function are set correctly

$trainerId = isset($_GET['trainer_id']) ? $_GET['trainer_id'] : null;
$schedules = getClassSchedules($trainerId);

$lastDate = null; // Variable to track the last processed date
$output = '';

foreach ($schedules as $schedule) {
    if ($lastDate !== $schedule['formatted_date']) {
        if ($lastDate !== null) {
            // Close the previous table if it's not the first iteration
            $output .= '</tbody></table>';
        }
        $lastDate = $schedule['formatted_date'];
        // Start a new table for each new date
        $output .= '<h4 class="mt-5">' . htmlspecialchars($schedule['formatted_date']) . '</h4>';
        $output .= '<table class="table table-bordered"><thead><tr class="text-center">
                    <th>Class Name</th>
                    <th>Trainer</th>
                    <th>Time</th>
                    <th>Level</th>
                    <th class="class-capacity">Spots Available</th>';
        if (isset($_SESSION['email'])) {
            $output .= '<th>Action</th>'; // Add Action column header only if user is logged in
        }
        $output .= '</tr></thead><tbody>';
    }

    $output .= '<tr class="text-center">';
    $output .= '<td>' . htmlspecialchars($schedule['class_name']) . '</td>';
    $output .= '<td>' . htmlspecialchars($schedule['trainer_name']) . '</td>';
    $output .= '<td>' . htmlspecialchars($schedule['start_time']) . ' - ' . htmlspecialchars($schedule['end_time']) . '</td>';
    $output .= '<td>' . htmlspecialchars($schedule['level']) . '</td>';
    $output .= '<td class="class-capacity">' . htmlspecialchars($schedule['capacity']) . '</td>';
    if (isset($_SESSION['email'])) {
        $output .= '<td>';
        if ($_SESSION['role'] === 'admin') {
            $output .= '<a href="data/delete_class.php?schedule_id=' . $schedule['schedule_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this class?\');">Delete</a>';
        } else {
            $output .= '<a href="book_class.php?schedule_id=' . $schedule['schedule_id'] . '" class="btn btn-primary">Book</a>';
        }
        $output .= '</td>';
    }
    $output .= '</tr>';
}

if ($lastDate !== null) {
    // Close the last table
    $output .= '</tbody></table>';
}

echo $output;
?>
