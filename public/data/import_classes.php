<?php
session_start();
include_once "../../config.php"; // Adjust the path as needed to ensure it's correct
$conn = getConnection();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check if there was no error uploading the file
    if ($file['error'] == UPLOAD_ERR_OK) {
        $tempName = $file['tmp_name'];
        $xml = simplexml_load_file($tempName);

        $conn = getConnection();
        foreach ($xml->Class as $class) {
            $name = $class->Name;
            $description = $class->Description;

            $sql = "INSERT INTO classes (name, description) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $name, $description);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        $_SESSION['message'] = "Classes imported successfully.";
        header("Location: ../dashboard.php");
    } else {
        $_SESSION['message'] = "Error uploading file.";
        header("Location: upload_classes.php");
    }
    exit();
}
?>
