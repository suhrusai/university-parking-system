<?php
$roles = Array("Admin");
require_once "../../dbConfig.php";
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unauthorized.php",$roles);

// Fetch all drivers for the dropdown
$query = "SELECT Driver_ID, First_Name, Last_Name FROM driver";
$driversResult = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $vehicleId = $_GET['id'];

    $query = "SELECT Vehicle_ID, Driver_ID, License_Plate, Make, Model, Color, Year FROM vehicle WHERE Vehicle_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_vehicle'])) {
    $vehicleId = $_POST['vehicle_id'];
    $driverId = $_POST['driver_id'];
    $licensePlate = $_POST['license_plate'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $year = $_POST['year'];

    $query = "UPDATE vehicle SET Driver_ID = ?, License_Plate = ?, Make = ?, Model = ?, Color = ?, Year = ? WHERE Vehicle_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issssii", $driverId, $licensePlate, $make, $model, $color, $year, $vehicleId);

    if ($stmt->execute()) {
        echo "<script>alert('Vehicle updated successfully!'); window.location.href = 'manageVehicles.php';</script>";
    } else {
        echo "<script>alert('Failed to update vehicle!'); window.location.href = 'editVehicle.php?id=$vehicleId';</script>";
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
  <title>Edit Vehicle</title>
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
    <h2>Edit Vehicle</h2>
    <form method="POST" action="editVehicle.php">
        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['Vehicle_ID']; ?>">
        <div class="mb-3">
            <label for="driver_id" class="form-label">Driver</label>
            <select class="form-select" id="driver_id" name="driver_id" required>
                <?php while($row = $driversResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['Driver_ID']; ?>" <?php echo ($row['Driver_ID'] == $vehicle['Driver_ID']) ? 'selected' : ''; ?>>
                        <?php echo $row['First_Name'] . ' ' . $row['Last_Name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="license_plate" class="form-label">License Plate</label>
            <input type="text" class="form-control" id="license_plate" name="license_plate" value="<?php echo $vehicle['License_Plate']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="make" class="form-label">Make</label>
            <input type="text" class="form-control" id="make" name="make" value="<?php echo $vehicle['Make']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control" id="model" name="model" value="<?php echo $vehicle['Model']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="color" class="form-label">Color</label>
            <input type="text" class="form-control" id="color" name="color" value="<?php echo $vehicle['Color']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" class="form-control" id="year" name="year" value="<?php echo $vehicle['Year']; ?>" required>
        </div>
        <button type="submit" name="update_vehicle" class="btn btn-primary">Update Vehicle</button>
        <a href="manageVehicles.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
