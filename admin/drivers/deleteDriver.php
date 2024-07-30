<?php
session_start();
require_once "../../authentication/isAuthenticated.php";
require_once "../../dbConfig.php";
checkAuthentication('../../login.php');

// Check if the logged-in user is an admin
if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_driver'])) {
    $driverId = $_POST['driver_id'];
    $deleteQuery = "DELETE FROM driver WHERE Driver_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $driverId);
    if ($stmt->execute()) {
        echo "<script>alert('Driver deleted successfully!'); window.location.href = 'manageDrivers.php';</script>";
    } else {
        echo "<script>alert('Failed to delete driver!'); window.location.href = 'manageDrivers.php';</script>";
    }
    $stmt->close();
}
?>
