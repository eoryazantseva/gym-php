<?php
include_once "../config.php";
$conn = getConnection();

$xml = simplexml_load_file('classes.xml');
foreach ($xml->Class as $class) {
    $name = $class->Name;
    $description = $class->Description;
    $trainerId = $class->TrainerId;
    $weekday = $class->Schedule->Weekday;
    $startTime = $class->Schedule->StartTime;
    $durationMinutes = $class->Schedule->DurationMinutes;
    $level = $class->Schedule->Level;

    // SQL to insert into classes table
    $sql = "INSERT INTO classes (name, description) VALUES ('$name', '$description')";
    mysqli_query($conn, $sql);
    $classId = mysqli_insert_id($conn);

    // SQL to insert into class_schedule table
    $sql = "INSERT INTO class_schedule (class_id, trainer_id, weekday, start_time, duration_minutes, level) 
            VALUES ('$classId', '$trainerId', '$weekday', '$startTime', '$durationMinutes', '$level')";
    mysqli_query($conn, $sql);
}
mysqli_close($conn);
?>
