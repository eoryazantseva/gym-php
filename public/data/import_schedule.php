<?php
session_start();
include_once "../../config.php"; // Adjust the path as needed

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Access denied: You must be an admin to access this page.";
    header('location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check if there was no error uploading the file
    if ($file['error'] == UPLOAD_ERR_OK) {
        $tempName = $file['tmp_name'];
        $xml = simplexml_load_file($tempName);

        $conn = getConnection();
        foreach ($xml->Schedule as $schedule) {
            $class_id = $schedule->ClassId;
            $trainer_id = $schedule->TrainerId;
            $date = $schedule->Date;
            $start_time = $schedule->StartTime;
            $end_time = $schedule->EndTime;
            $level = $schedule->Level;
            $capacity = $schedule->Capacity;

            $sql = "INSERT INTO class_schedule (class_id, trainer_id, date, start_time, end_time, level, capacity) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iissssi", $class_id, $trainer_id, $date, $start_time, $end_time, $level, $capacity);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        $_SESSION['message'] = "Schedules imported successfully.";
        header("Location: ../dashboard.php");
    } else {
        $_SESSION['message'] = "Error uploading file.";
        header("Location: upload_schedule.php");
    }
    exit();
}
?>
