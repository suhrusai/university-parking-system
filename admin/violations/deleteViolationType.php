<?php
$roles = Array("Admin");
require_once "../../dbConfig.php";
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unauthorized.php",$roles);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['violation_type_id'])) {
    $violationId = $_POST['violation_type_id'];
    $deleteQuery = "DELETE FROM violation_type WHERE Violation_Type_ID = ?";
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
