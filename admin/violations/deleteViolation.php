<?php
require_once "../../authentication/isAuthenticated.php";
require_once "../../dbConfig.php";
checkAuthentication('../../login.php');

// Check if the logged-in user is an admin
if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_violation'])) {
    $violationId = $_POST['violation_id'];
    $deleteQuery = "DELETE FROM violation WHERE Violation_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $violationId);
    if ($stmt->execute()) {
        echo "<script>alert('Violation deleted successfully!'); window.location.href = 'manageViolations.php';</script>";
    } else {
        echo "<script>alert('Failed to delete violation!'); window.location.href = 'manageViolations.php';</script>";
    }
    $stmt->close();
}
?>
