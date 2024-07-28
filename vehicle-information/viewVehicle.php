<?php
$roles = Array("Admin","User","Faculty","Guest");
require_once "../dbConfig.php";
require_once "../authentication/isAuthenticated.php";
checkAuthentication('../login.php');

$userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

// Fetch vehicle information from the database
$stmt = $conn->prepare("SELECT Vehicle_ID, License_Plate, Make, Model, Color, Year FROM vehicle WHERE Driver_ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Store fetched data
$vehicles = [];
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>View Vehicle Details</title>
  <style>
    body {
      padding-top: 80px;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="../assets/university_of_utah_logo.png" width="30px" alt="Logo"> Parking Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
            </ul>
        </div>
        </div>
    </nav>
    <div class="container">
        <h2>Vehicle Details</h2>
        <div class="mb-3">
            <a class="btn btn-secondary" href="../homepage.php">Back</a>
        </div>
        <div class="mb-3">
            <a class="btn btn-primary" href="addVehicle.php?user_id=<?php echo $userId; ?>">Add Vehicle</a>
        </div>
        <?php if (empty($vehicles)): ?>
            <p>No vehicles found for this driver.</p>
        <?php else: ?>
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p><b>License Plate:</b> <?php echo htmlspecialchars($vehicle['License_Plate']); ?></p>
                        <p><b>Make:</b> <?php echo htmlspecialchars($vehicle['Make']); ?></p>
                        <p><b>Model:</b> <?php echo htmlspecialchars($vehicle['Model']); ?></p>
                        <p><b>Color:</b> <?php echo htmlspecialchars($vehicle['Color']); ?></p>
                        <p><b>Year:</b> <?php echo htmlspecialchars($vehicle['Year']); ?></p>
                        <div class="d-flex">
                            <a class="btn btn-primary" href="updateVehicle.php?vehicle_id=<?php echo $vehicle['Vehicle_ID']; ?>">Update</a>
                            <form action="deleteVehicle.php" method="POST" class="ms-2">
                                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['Vehicle_ID']; ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>
