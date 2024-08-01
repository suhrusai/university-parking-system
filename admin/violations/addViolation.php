<?php
$roles = Array("Admin");
require_once "../../dbConfig.php";
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unauthorized.php",$roles);

// Fetch all violation types for the dropdown
$query = "SELECT Violation_Type_ID, Violation_Name FROM violation_type";
$violationTypesResult = $conn->query($query);

// Fetch all drivers and vehicles for the dropdown
$query = "SELECT d.Driver_ID, d.First_Name, d.Last_Name, v.Vehicle_ID, v.License_Plate 
          FROM driver d 
          LEFT JOIN vehicle v ON d.Driver_ID = v.Driver_ID";
$driversVehiclesResult = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicleId = $_POST['vehicle_id'];
    $datetime = $_POST['datetime'];
    $violationTypeId = $_POST['violation_type_id'];

    $query = "INSERT INTO violation (Vehicle_ID, Datetime, Violation_Type_ID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $vehicleId, $datetime, $violationTypeId);

    if ($stmt->execute()) {
        echo "<script>alert('Violation added successfully!'); window.location.href = 'manageViolations.php';</script>";
    } else {
        echo "<script>alert('Failed to add violation!'); window.location.href = 'addViolation.php';</script>";
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
  <title>Add Violation</title>
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


<div class="container card-section pt-5 mt-5">
    <h2>Add Violation</h2>
    <form method="POST" action="addViolation.php">
        <div class="mb-3">
            <label for="vehicle_id" class="form-label">Vehicle</label>
            <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                <?php while($row = $driversVehiclesResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['Vehicle_ID']; ?>">
                        <?php echo $row['First_Name'] . ' ' . $row['Last_Name'] . ' - ' . $row['License_Plate']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="datetime" class="form-label">Datetime</label>
            <input type="datetime-local" class="form-control" id="datetime" name="datetime" required>
        </div>
        <div class="mb-3">
            <label for="violation_type_id" class="form-label">Violation Type</label>
            <select class="form-select" id="violation_type_id" name="violation_type_id" required>
                <?php while($row = $violationTypesResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['Violation_Type_ID']; ?>"><?php echo $row['Violation_Name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Violation</button>
        <a href="manageViolations.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
