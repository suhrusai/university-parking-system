<?php
$roles = Array("Admin");
require_once "../../dbConfig.php";
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unautorized.php",$roles);


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
