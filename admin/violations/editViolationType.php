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

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $violationTypeId = $_GET['id'];

    $query = "SELECT Violation_Type_ID, Violation_Name, Penalty_Amount FROM violation_type WHERE Violation_Type_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $violationTypeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $violationType = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_violation_type'])) {
    $violationTypeId = $_POST['violation_type_id'];
    $violationName = $_POST['violation_name'];
    $penaltyAmount = $_POST['penalty_amount'];

    $query = "UPDATE violation_type SET Violation_Name = ?, Penalty_Amount = ? WHERE Violation_Type_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdi", $violationName, $penaltyAmount, $violationTypeId);

    if ($stmt->execute()) {
        echo "<script>alert('Violation type updated successfully!'); window.location.href = 'manageViolations.php';</script>";
    } else {
        echo "<script>alert('Failed to update violation type!'); window.location.href = 'editViolationType.php?id=$violationTypeId';</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Edit Violation Type</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../assets/university_of_utah_logo.png" width="30px" alt="Logo"> Parking Management
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container card-section mt-5 pt-5">
    <h2>Edit Violation Type</h2>
    <form method="POST" action="editViolationType.php">
        <input type="hidden" name="violation_type_id" value="<?php echo $violationType['Violation_Type_ID']; ?>">
        <div class="mb-3">
            <label for="violation_name" class="form-label">Violation Name</label>
            <input type="text" class="form-control" id="violation_name" name="violation_name" value="<?php echo $violationType['Violation_Name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="penalty_amount" class="form-label">Penalty Amount</label>
            <input type="number" step="0.01" class="form-control" id="penalty_amount" name="penalty_amount" value="<?php echo $violationType['Penalty_Amount']; ?>" required>
        </div>
        <button type="submit" name="update_violation_type" class="btn btn-primary">Update Violation Type</button>
        <a href="manageViolations.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
