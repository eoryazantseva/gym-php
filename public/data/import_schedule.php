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

    // Check if the file is an XML file
    if ($file['type'] != 'text/xml' && $file['type'] != 'application/xml') {
        $_SESSION['message'] = "Invalid file type. Only XML files are allowed.";
        header("Location: ../upload_schedule.php");
        exit();
    }

    // Check if there was no error uploading the file
    if ($file['error'] == UPLOAD_ERR_OK) {
        $tempName = $file['tmp_name'];
        $xml = simplexml_load_file($tempName);

        // Validate the XML structure
        if (!validateXMLStructure($xml)) {
            $_SESSION['message'] = "Invalid XML structure for schedule.";
            header("Location: ../upload_schedule.php");
            exit();
        }

        $conn = getConnection();
        $errors = [];
        foreach ($xml->Schedule as $schedule) {
            if (!isset($schedule->ClassId, $schedule->TrainerId, $schedule->Date, $schedule->StartTime, $schedule->EndTime, $schedule->Level, $schedule->Capacity)) {
                $errors[] = "Missing required schedule elements.";
                continue;
            }

            $class_id = (int) $schedule->ClassId;
            $trainer_id = (int) $schedule->TrainerId;

            // Validate existence of IDs
            if (!checkIdExists($conn, 'classes', 'class_id', $class_id) || !checkIdExists($conn, 'trainers', 'trainer_id', $trainer_id)) {
                $errors[] = "Class ID $class_id or Trainer ID $trainer_id does not exist.";
                continue;
            }

            $date = $schedule->Date;
            $start_time = $schedule->StartTime;
            $end_time = $schedule->EndTime;
            $level = $schedule->Level;
            $capacity = (int) $schedule->Capacity;

            $sql = "INSERT INTO class_schedule (class_id, trainer_id, date, start_time, end_time, level, capacity) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iissssi", $class_id, $trainer_id, $date, $start_time, $end_time, $level, $capacity);
            if (!mysqli_stmt_execute($stmt)) {
                $errors[] = "Failed to insert schedule for class ID $class_id.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);

        if (!empty($errors)) {
            $_SESSION['message'] = "Errors occurred: \n" . implode("\n", $errors);
            header("Location: ../upload_schedule.php");
        } else {
            $_SESSION['message'] = "Schedules imported successfully.";
            header("Location: ../dashboard.php");
        }
    } else {
        $_SESSION['message'] = "Error uploading file.";
        header("Location: ../upload_schedule.php");
    }
    exit();
}

function validateXMLStructure($xml) {
    // Check for the presence of main elements
    return isset($xml->Schedule) && isset($xml->Schedule->ClassId) && isset($xml->Schedule->TrainerId);
}

function checkIdExists($conn, $table, $column, $id) {
    $sql = "SELECT COUNT(*) FROM $table WHERE $column = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}
?>
