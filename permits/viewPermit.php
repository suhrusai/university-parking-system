<?php
require_once "../dbConfig.php";
require_once "../authentication/isAuthenticated.php";
checkAuthentication('../login.php');

$userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

// Fetch permit information from the database
$stmt = $conn->prepare("SELECT p.Permit_ID, p.Permit_Type, v.License_Plate, p.Purchase_Date, p.Expiry_Date, p.Cost 
                        FROM permit p 
                        JOIN vehicle v ON p.Vehicle_ID = v.Vehicle_ID 
                        WHERE p.Driver_ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Store fetched data
$permits = [];
while ($row = $result->fetch_assoc()) {
    $permits[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>View Permits</title>
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
        <h2>Permit Details</h2>
        <div class="mb-3">
            <a class="btn btn-success" href="addPermit.php?user_id=<?php echo $userId; ?>">Add Permit</a>
        </div>
        <div class="mb-3">
            <a class="btn btn-secondary" href="../homepage.php">Back</a>
        </div>
        <?php if (empty($permits)): ?>
            <p>No permits found for this driver.</p>
        <?php else: ?>
            <?php foreach ($permits as $permit): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p><b>Permit Type:</b> <?php echo htmlspecialchars($permit['Permit_Type']); ?></p>
                        <p><b>Vehicle Plate:</b> <?php echo htmlspecialchars($permit['License_Plate']); ?></p>
                        <p><b>Purchase Date:</b> <?php echo htmlspecialchars($permit['Purchase_Date']); ?></p>
                        <p><b>Expiry Date:</b> <?php echo htmlspecialchars($permit['Expiry_Date']); ?></p>
                        <p><b>Cost:</b> <?php echo htmlspecialchars($permit['Cost']); ?></p>
                        <div class="d-flex">
                            <a class="btn btn-primary" href="updatePermit.php?permit_id=<?php echo $permit['Permit_ID']; ?>">Update</a>
                            <form action="deletePermit.php" method="POST" class="ms-2">
                                <input type="hidden" name="permit_id" value="<?php echo $permit['Permit_ID']; ?>">
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
