<?php
require_once 'db_connection.php';

if (isset($_GET['usn'])) {
    $usn = $_GET['usn'];

    $sql = "DELETE FROM students WHERE usn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usn);

    if ($stmt->execute()) {
        header("Location: index.php?success=1");
        exit();
    } else {
        header("Location: index.php?error=1");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

$conn->close();
?>