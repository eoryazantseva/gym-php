<?php
include_once "config.php"; // Includes the configuration and the getConnection function

function getClassSchedules() {
    $conn = getConnection(); // Establish a connection using the function from config.php

    $today = date("Y-m-d"); // Current date in 'Y-m-d' format

    $sql = "SELECT cs.schedule_id, cl.name as class_name, tr.name as trainer_name,
                   DATE_FORMAT(cs.date, '%W, %d %M %Y') as formatted_date, 
                   DATE_FORMAT(cs.start_time, '%H:%i') as start_time, 
                   DATE_FORMAT(cs.end_time, '%H:%i') as end_time, 
                   cs.level, cs.capacity
            FROM class_schedule cs
            JOIN classes cl ON cs.class_id = cl.class_id
            JOIN trainers tr ON cs.trainer_id = tr.trainer_id
            WHERE cs.date >= ? 
            ORDER BY cs.date ASC, cs.start_time ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();

    $schedules = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $schedules[] = $row;
        }
    } else {
        echo "0 results";
    }
    $stmt->close();
    $conn->close();
    return $schedules;
}
