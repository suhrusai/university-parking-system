<?php
$roles = Array("Admin","User","Faculty","Guest");
include_once '../dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $permitId = $_POST['permit_id'];

    // Delete permit
    $stmt = $conn->prepare("DELETE FROM permit WHERE Permit_ID = ?");
    $stmt->bind_param("i", $permitId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Permit deleted successfully.";
        header("Location: viewPermit.php");
        exit();
    } else {
        $error_message = "Error deleting permit: " . $stmt->error;
    }

    $stmt->close();
}
?>
