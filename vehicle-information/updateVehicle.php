<?php
$roles = Array("Admin","User","Faculty","Guest");
include_once '../dbConfig.php';
require_once "../authentication/isAuthenticated.php";
require_once "../authentication/checkAutorization.php";
checkAuthentication('../login.php');
checkAuthorization("../unautorized.php",$roles);

$vehicleId = isset($_GET['vehicle_id']) ? $_GET['vehicle_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicleId = $_POST['vehicle_id'];
    $licensePlate = $_POST['licensePlate'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $year = $_POST['year'];

    // Update vehicle information
    $stmt = $conn->prepare("UPDATE vehicle SET license_plate = ?, make = ?, model = ?, color = ?, year = ? WHERE vehicle_id = ?");
    $stmt->bind_param("ssssii", $licensePlate, $make, $model, $color, $year, $vehicleId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Vehicle information updated successfully.";
        header("Location: viewVehicle.php");
        exit();
    } else {
        $error_message = "Error updating vehicle information: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Fetch current vehicle information
    $stmt = $conn->prepare("SELECT license_plate, make, model, color, year FROM vehicle WHERE vehicle_id = ?");
    $stmt->bind_param("i", $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Update Vehicle</title>
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
                      <a class="nav-link" href="../homepage.php">Home</a>
                  </li>
              </ul>
              <ul class="navbar-nav">
                  <li class="nav-item">
                      <a class="nav-link" href="../logout.php">Logout</a>
                  </li>
              </ul>
        </div>
    </div>
</nav>

  <div class="container mt-5 pt-5">
    <a class="btn btn-secondary" href="viewVehicle.php">Back</a>
    <h2>Update Vehicle</h2>
    <form method="POST" action="">
      <input type="hidden" name="vehicle_id" value="<?php echo htmlspecialchars($vehicleId); ?>">
      <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
      <?php endif; ?>
      <div class="mb-3 row">
        <label for="licensePlate" class="col-sm-2 col-form-label"><b>License Plate</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="licensePlate" name="licensePlate" value="<?php echo htmlspecialchars($vehicle['license_plate']); ?>" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="make" class="col-sm-2 col-form-label"><b>Make</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="make" name="make" value="<?php echo htmlspecialchars($vehicle['make']); ?>" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="model" class="col-sm-2 col-form-label"><b>Model</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($vehicle['model']); ?>" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="color" class="col-sm-2 col-form-label"><b>Color</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="color" name="color" value="<?php echo htmlspecialchars($vehicle['color']); ?>" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="year" class="col-sm-2 col-form-label"><b>Year</b></label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($vehicle['year']); ?>" required>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-10 offset-sm-2">
          <button type="submit" class="btn btn-primary">Update Vehicle</button>
        </div>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>
