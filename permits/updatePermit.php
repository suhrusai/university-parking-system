<?php
$roles = Array("Admin","User","Faculty","Guest");
include_once '../dbConfig.php';
require_once "../authentication/isAuthenticated.php";
require_once "../authentication/checkAutorization.php";
checkAuthentication('../login.php');
checkAuthorization("../unautorized.php",$roles);

$permitId = isset($_GET['permit_id']) ? $_GET['permit_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $permitId = $_POST['permit_id'];
    $permitType = trim($_POST['permitType']);
    $vehiclePlate = trim($_POST['vehiclePlate']);
    $purchaseDate = $_POST['purchaseDate'];
    $expiryDate = $_POST['expiryDate'];
    $cost = $_POST['cost'];

    // Check if the vehicle exists
    $stmt = $conn->prepare("SELECT Vehicle_ID FROM vehicle WHERE LOWER(License_Plate) = LOWER(?)");
    $stmt->bind_param("s", $vehiclePlate);
    $stmt->execute();
    $stmt->bind_result($vehicleId);
    $stmt->fetch();
    $stmt->close();

    if (empty($vehicleId)) {
        $error_message = "Vehicle not found. Please enter a valid vehicle number plate.";
    } else {
        // Update permit information
        $stmt = $conn->prepare("UPDATE permit SET Permit_Type = ?, Vehicle_ID = ?, Purchase_Date = ?, Expiry_Date = ?, Cost = ? WHERE Permit_ID = ?");
        $stmt->bind_param("sisssi", $permitType, $vehicleId, $purchaseDate, $expiryDate, $cost, $permitId);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Permit information updated successfully.";
            header("Location: viewPermit.php");
            exit();
        } else {
            $error_message = "Error updating permit information: " . $stmt->error;
        }

        $stmt->close();
    }
} else {
    // Fetch current permit information
    $stmt = $conn->prepare("SELECT * FROM permit WHERE Permit_ID = ?");
    $stmt->bind_param("i", $permitId);
    $stmt->execute();
    $result = $stmt->get_result();
    $permit = $result->fetch_assoc();
    $stmt->close();
}

// Fetch available vehicles for the user
$stmt = $conn->prepare("SELECT * FROM vehicle WHERE Driver_ID = (SELECT Driver_ID FROM permit WHERE Permit_ID = ?)");
$stmt->bind_param("i", $permitId);
$stmt->execute();
$vehicles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// print_r($vehicles);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Update Permit</title>
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
    <h2>Update Permit</h2>
    <form method="POST" action="">
      <input type="hidden" name="permit_id" value="<?php echo htmlspecialchars($permitId); ?>">
      <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
      <?php endif; ?>
      <div class="mb-3 row">
        <label for="permitType" class="col-sm-2 col-form-label"><b>Permit Type</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="permitType" name="permitType" value="<?php echo htmlspecialchars($permit['Permit_Type']); ?>" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="vehiclePlate" class="col-sm-2 col-form-label"><b>Vehicle Number Plate</b></label>
        <div class="col-sm-10">
          <select class="form-control" id="vehiclePlate" name="vehiclePlate" required>
            <?php foreach ($vehicles as $vehicle): ?>
              <option value="<?php echo htmlspecialchars($vehicle['License_Plate']); ?>" <?php if ($vehicle['Vehicle_ID'] == $permit['Vehicle_ID']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($vehicle['License_Plate']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="purchaseDate" class="col-sm-2 col-form-label"><b>Purchase Date</b></label>
        <div class="col-sm-10">
          <input type="date" class="form-control" id="purchaseDate" name="purchaseDate" value="<?php echo htmlspecialchars($permit['Purchase_Date']); ?>" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="expiryDate" class="col-sm-2 col-form-label"><b>Expiry Date</b></label>
        <div class="col-sm-10">
          <input type="date" class="form-control" id="expiryDate" name="expiryDate" value="<?php echo htmlspecialchars($permit['Expiry_Date']); ?>" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="cost" class="col-sm-2 col-form-label"><b>Cost</b></label>
        <div class="col-sm-10">
          <input type="number" step="0.01" class="form-control" id="cost" name="cost" value="<?php echo htmlspecialchars($permit['Cost']); ?>" required>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-10 offset-sm-2">
          <button type="submit" class="btn btn-primary">Update Permit</button>
          <a class="btn btn-secondary" href="./viewPermit.php">Back</a>
        </div>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>
