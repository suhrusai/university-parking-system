<?php
require_once "../../authentication/isAuthenticated.php";
require_once "../../dbConfig.php";
checkAuthentication('../../login.php');

// Check if the logged-in user is an admin
if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch all drivers for the dropdown
$query = "SELECT Driver_ID, First_Name, Last_Name FROM driver";
$driversResult = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $driverId = $_POST['driver_id'];
    $licensePlate = $_POST['license_plate'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $year = $_POST['year'];

    $query = "INSERT INTO vehicle (Driver_ID, License_Plate, Make, Model, Color, Year) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issssi", $driverId, $licensePlate, $make, $model, $color, $year);

    if ($stmt->execute()) {
        echo "<script>alert('Vehicle added successfully!'); window.location.href = 'manageVehicles.php';</script>";
    } else {
        echo "<script>alert('Failed to add vehicle!'); window.location.href = 'addVehicle.php';</script>";
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
  <title>Add Vehicle</title>
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
    <h2>Add Vehicle</h2>
    <form method="POST" action="addVehicle.php">
        <div class="mb-3">
            <label for="driver_id" class="form-label">Driver</label>
            <select class="form-select" id="driver_id" name="driver_id" required>
                <?php while($row = $driversResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['Driver_ID']; ?>"><?php echo $row['First_Name'] . ' ' . $row['Last_Name'] .'-'. $row['Driver_ID'] ; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="license_plate" class="form-label">License Plate</label>
            <input type="text" class="form-control" id="license_plate" name="license_plate" required>
        </div>
        <div class="mb-3">
            <label for="make" class="form-label">Make</label>
            <input type="text" class="form-control" id="make" name="make" required>
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control" id="model" name="model" required>
        </div>
        <div class="mb-3">
            <label for="color" class="form-label">Color</label>
            <input type="text" class="form-control" id="color" name="color" required>
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" class="form-control" id="year" name="year" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Vehicle</button>
        <a href="manageVehicles.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
