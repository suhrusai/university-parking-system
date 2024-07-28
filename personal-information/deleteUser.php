<?php
$roles = Array("Admin","User","Faculty","Guest");
include_once '../dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];

    // Delete user
    $stmt = $conn->prepare("DELETE FROM driver WHERE driver_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Destroy the session to log out the user
        session_unset();
        session_destroy();
        header("Location: ../login.php");
        exit();
    } else {
        $error_message = "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
}
?>
