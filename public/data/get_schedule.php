<?php
include_once "../../config.php";

function getClassSchedules($trainerId = null) {
    $conn = getConnection(); 

    $sql = "SELECT cs.schedule_id, cl.name as class_name, tr.name as trainer_name,
               DATE_FORMAT(cs.date, '%W, %d %M %Y') as formatted_date, 
               DATE_FORMAT(cs.start_time, '%H:%i') as start_time, 
               DATE_FORMAT(cs.end_time, '%H:%i') as end_time, 
               cs.level, cs.capacity
            FROM class_schedule cs
            JOIN classes cl ON cs.class_id = cl.class_id
            JOIN trainers tr ON cs.trainer_id = tr.trainer_id
            WHERE cs.date >= CURDATE()";

    $params = []; 
    $types = '';  // Initialize types as an empty string

    // Only add trainer ID condition if a specific trainer is selected
    if (!empty($trainerId)) {
        $sql .= " AND cs.trainer_id = ?";
        $params[] = $trainerId;
        $types .= 'i'; // 'i' for integer type for trainer_id
    }

    $sql .= " ORDER BY cs.date ASC, cs.start_time ASC"; // Ensure ORDER BY clause is at the end of the query

    $stmt = $conn->prepare($sql);

    if ($types) { // Bind parameters only if types are set
        $stmt->bind_param($types, ...$params);
    }

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
?>
