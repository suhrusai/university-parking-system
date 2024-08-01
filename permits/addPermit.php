<?php
$roles = Array("Admin","User","Faculty","Guest");
include_once '../dbConfig.php';
require_once "../authentication/isAuthenticated.php";
require_once "../authentication/checkAutorization.php";
checkAuthentication('../login.php');
checkAuthorization("../unauthorized.php",$roles);
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
error_log("userId: " . $userId);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $permitType = trim($_POST['permitType']);
    $vehiclePlate = trim($_POST['vehiclePlate']);
    $purchaseDate = $_POST['purchaseDate'];
    $expiryDate = $_POST['expiryDate'];
    $cost = $_POST['cost'];

    $stmt = $conn->prepare("SELECT Vehicle_ID FROM vehicle WHERE LOWER(License_Plate) = LOWER(?) AND Driver_ID = ?");
    $stmt->bind_param("si", $vehiclePlate, $userId);
    $stmt->execute();
    $stmt->bind_result($vehicleId);
    $stmt->fetch();
    $stmt->close();

    if (empty($vehicleId)) {
        $error_message = "Vehicle not found. Please enter a valid vehicle number plate.";
    } else {
        // Insert new permit information
        $stmt = $conn->prepare("INSERT INTO permit (Permit_Type, Vehicle_ID, Purchase_Date, Expiry_Date, Cost, Driver_ID) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisssi", $permitType, $vehicleId, $purchaseDate, $expiryDate, $cost, $userId);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Permit added successfully.";
            header("Location: viewPermit.php?user_id=$userId");
            exit();
        } else {
            $error_message = "Error adding permit: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch available vehicles for the user
$stmt = $conn->prepare("SELECT License_Plate FROM vehicle WHERE Driver_ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$vehicles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <title>Add Permit</title>
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
    <h2>Add Permit</h2>
    <form method="POST" action="">
      <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
      <?php endif; ?>
      <div class="mb-3 row">
        <label for="permitType" class="col-sm-2 col-form-label"><b>Permit Type</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="permitType" name="permitType" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="vehiclePlate" class="col-sm-2 col-form-label"><b>Vehicle Number Plate</b></label>
        <div class="col-sm-10">
          <select class="form-control" id="vehiclePlate" name="vehiclePlate" required>
            <?php foreach ($vehicles as $vehicle): ?>
              <option value="<?php echo htmlspecialchars($vehicle['License_Plate']); ?>"><?php echo htmlspecialchars($vehicle['License_Plate']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="purchaseDate" class="col-sm-2 col-form-label"><b>Purchase Date</b></label>
        <div class="col-sm-10">
          <input type="date" class="form-control" id="purchaseDate" name="purchaseDate" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="expiryDate" class="col-sm-2 col-form-label"><b>Expiry Date</b></label>
        <div class="col-sm-10">
          <input type="date" class="form-control" id="expiryDate" name="expiryDate" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="cost" class="col-sm-2 col-form-label"><b>Cost</b></label>
        <div class="col-sm-10">
          <input type="number" step="0.01" class="form-control" id="cost" name="cost" required>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-10 offset-sm-2">
          <button type="submit" class="btn btn-primary">Add Permit</button>
          <a class="btn btn-secondary" href="./viewPermit.php">Back</a>
        </div>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
