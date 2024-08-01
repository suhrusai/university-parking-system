<?php
$roles = Array("Admin");
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
require_once "../../dbConfig.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unauthorized.php",$roles);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_vehicle'])) {
    $vehicleId = $_POST['vehicle_id'];
    $deleteQuery = "DELETE FROM vehicle WHERE Vehicle_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $vehicleId);
    if ($stmt->execute()) {
        echo "<script>alert('Vehicle deleted successfully!'); window.location.href = 'manageVehicles.php';</script>";
    } else {
        echo "<script>alert('Failed to delete vehicle!'); window.location.href = 'manageVehicles.php';</script>";
    }
    $stmt->close();
}
?>
