<?php
session_start();
include_once "../../config.php"; // Ensure this path is correctly pointed to your configuration file

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Access denied: You must be an admin to access this page.";
    header('location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // File error and type checking
    if ($file['error'] !== UPLOAD_ERR_OK || ($file['type'] != 'text/xml' && $file['type'] != 'application/xml')) {
        $_SESSION['message'] = "Invalid file type or upload error. Only XML files are allowed.";
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
    mysqli_begin_transaction($conn); // Start transaction
    $all_queries_successful = true;

    foreach ($xml->Class as $class) {
        if (!isset($class->Name, $class->Description)) {
            $all_queries_successful = false;
            break;
        }

        $name = trim($class->Name);
        $description = trim($class->Description);

        $sql = "INSERT INTO classes (name, description) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $name, $description);
        if (!mysqli_stmt_execute($stmt)) {
            $all_queries_successful = false;
            mysqli_stmt_close($stmt);
            break; 
        }
        mysqli_stmt_close($stmt);
    }

    if ($all_queries_successful) {
        mysqli_commit($conn); // Commit transaction if everything went well
        $_SESSION['message'] = "Classes imported successfully.";
        header("Location: ../dashboard.php");
    } else {
        mysqli_rollback($conn); // Rollback transaction on error
        $_SESSION['message'] = "Failed to import some or all classes.";
        header("Location: ../upload_classes.php");
    }
    
    mysqli_close($conn);
    exit();
} else {
    $_SESSION['message'] = "No file uploaded or incorrect method.";
    header("Location: ../upload_classes.php");
    exit();
}

function validateXMLStructureForClasses($xml) {
    if (!isset($xml->Class) || $xml->Class->count() == 0) {
        return false;
    }
    foreach ($xml->Class as $class) {
        if (!isset($class->Name) || !isset($class->Description)) {
            return false;
        }
    }
    return true;
}
?>
