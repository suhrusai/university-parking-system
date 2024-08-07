<?php
$roles = Array("Admin");
require_once "../../dbConfig.php";
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unauthorized.php",$roles);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $violationName = $_POST['violation_name'];
    $penaltyAmount = $_POST['penalty_amount'];

    $query = "INSERT INTO violation_type (Violation_Name, Penalty_Amount) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sd", $violationName, $penaltyAmount);

    if ($stmt->execute()) {
        echo "<script>alert('Violation type added successfully!'); window.location.href = 'manageViolations.php';</script>";
    } else {
        echo "<script>alert('Failed to add violation type!'); window.location.href = 'addViolationType.php';</script>";
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
  <title>Add Violation Type</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../../assets/university_of_utah_logo.png" width="30px" alt="Logo"> Parking Management
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../../homepage.php">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container card-section mt-5 pt-5">
    <h2>Add Violation Type</h2>
    <form method="POST" action="addViolationType.php">
        <div class="mb-3">
            <label for="violation_name" class="form-label">Violation Name</label>
            <input type="text" class="form-control" id="violation_name" name="violation_name" required>
        </div>
        <div class="mb-3">
            <label for="penalty_amount" class="form-label">Penalty Amount</label>
            <input type="number" step="0.01" class="form-control" id="penalty_amount" name="penalty_amount" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Violation Type</button>
        <a href="manageViolations.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
