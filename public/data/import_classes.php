<?php
session_start();
include_once "../../config.php";

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Access denied: You must be an admin to access this page.";
    header('location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // File error checking
    if ($file['type'] != 'text/xml' && $file['type'] != 'application/xml') {
        $_SESSION['message'] = "Invalid file type. Only XML files are allowed.";
        header("Location: ../upload_schedule.php");
        exit();
    }

    // Validate the MIME type of the file
    $fileType = mime_content_type($file['tmp_name']);
    if ($fileType != 'text/xml' && $fileType != 'application/xml') {
        $_SESSION['message'] = "Invalid file type. Please upload a valid XML file.";
        header("Location: ../upload_classes.php");
        exit();
    }

    // Load and validate XML structure
    $xml = simplexml_load_file($file['tmp_name']);
    if (!$xml || !validateXMLStructureForClasses($xml)) {
        $_SESSION['message'] = "Invalid XML structure or content. Please ensure the XML is formatted correctly for class data.";
        header("Location: ../upload_classes.php");
        exit();
    }

    $conn = getConnection();
    mysqli_begin_transaction($conn);
    $all_queries_successful = true;

    foreach ($xml->Class as $class) {
        if (!isset($class->Name, $class->Description)) {
            $all_queries_successful = false;
            break;
        }

        $sql = "INSERT INTO classes (name, description) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $class->Name, $class->Description);
        if (!mysqli_stmt_execute($stmt)) {
            $all_queries_successful = false;
            mysqli_stmt_close($stmt);
            break; 
        }
        mysqli_stmt_close($stmt);
    }

    if ($all_queries_successful) {
        mysqli_commit($conn);
        $_SESSION['message'] = "Classes imported successfully.";
    } else {
        mysqli_rollback($conn);
        $_SESSION['message'] = "Failed to import some or all classes.";
    }
    
    mysqli_close($conn);
    header("Location: ../dashboard.php");
    exit();
} else {
    $_SESSION['message'] = "No file uploaded or incorrect method.";
    header("Location: ../upload_classes.php");
    exit();
}

function validateXMLStructureForClasses($xml) {
    // Ensure there is at least one Class element and it has the required children
    return isset($xml->Class) && count($xml->Class) > 0 && array_reduce($xml->Class, function ($valid, $class) {
        return $valid && isset($class->Name, $class->Description);
    }, true);
}
?>
