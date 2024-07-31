<?php
$roles = Array("Admin","User","Faculty","Guest");
include_once '../dbConfig.php';
require_once "../authentication/isAuthenticated.php";
require_once "../authentication/checkAutorization.php";
checkAuthentication('../login.php');
checkAuthorization("../unautorized.php",$roles);

// Fetch payments for the logged-in user
$userId = $_SESSION['user_id']; // assuming the user's ID is stored in the session

// Query to fetch pending payments for violations and permits
$queryPending = "SELECT 'violation' AS type, v.Violation_ID AS id, vt.Penalty_Amount as Amount, v.Payment_ID 
                  FROM violation v
                  JOIN violation_type vt ON v.Violation_Type_ID = vt.Violation_Type_ID
                  JOIN vehicle ve ON v.Vehicle_ID = ve.Vehicle_ID
                  WHERE v.Payment_ID IS NULL AND ve.Driver_ID = ?
                  UNION
                  SELECT 'permit' AS type, p.Permit_ID AS id, p.Cost AS Amount, p.Payment_ID 
                  FROM permit p
                  WHERE p.Payment_ID IS NULL AND p.Driver_ID = ?
                  ";

// Query to fetch completed payments for violations and permits
$queryCompleted = "SELECT 'violation' AS type, v.Violation_ID AS id, vt.Penalty_Amount as Amount, v.Payment_ID 
                    FROM violation v
                    JOIN violation_type vt ON v.Violation_Type_ID = vt.Violation_Type_ID
                    JOIN vehicle ve ON v.Vehicle_ID = ve.Vehicle_ID
                    WHERE v.Payment_ID IS NOT NULL AND ve.Driver_ID = ?
                    UNION
                    SELECT 'permit' AS type, p.Permit_ID AS id, p.Cost AS Amount, p.Payment_ID 
                    FROM permit p
                    WHERE p.Payment_ID IS NOT NULL AND p.Driver_ID = ?
                ";

$stmtPending = $conn->prepare($queryPending);
$stmtPending->bind_param("ii", $userId, $userId);
$stmtPending->execute();
$resultPending = $stmtPending->get_result();

$stmtCompleted = $conn->prepare($queryCompleted);
$stmtCompleted->bind_param("ii", $userId, $userId);
$stmtCompleted->execute();
$resultCompleted = $stmtCompleted->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Payments</title>
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

<div class="container card-section mt-5 pt-4">
  <a href="../homepage.php" class="btn btn-secondary">Back</a>
    <h2>Pending Payments</h2>
    <div class="row">
      <?php if ($resultPending->num_rows > 0): ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Type</th>
              <th scope="col">ID</th>
              <th scope="col">Amount</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $resultPending->fetch_assoc()): ?>
              <tr>
                <td><?php echo ucfirst($row['type']); ?></td>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['Amount']; ?></td>
                <td><?php echo is_null($row['Payment_ID']) ? 'Pending' : 'Completed'; ?></td>
                <td>
                  <?php if (is_null($row['Payment_ID'])): ?>
                    <a href="makePayment.php?type=<?php echo $row['type']; ?>&id=<?php echo $row['id']; ?>" class="btn btn-primary">Pay Now</a>
                  <?php else: ?>
                    <span class="text-success">Paid</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No pending payments found.</p>
      <?php endif; ?>
    </div>

    <h2>Completed Payments</h2>
    <div class="row">
      <?php if ($resultCompleted->num_rows > 0): ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Type</th>
              <th scope="col">ID</th>
              <th scope="col">Amount</th>
              <th scope="col">Status</th>
              <th scope="col">Payment ID</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $resultCompleted->fetch_assoc()): ?>
              <tr>
                <td><?php echo ucfirst($row['type']); ?></td>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['Amount']; ?></td>
                <td>Completed</td>
                <td><?php echo $row['Payment_ID']; ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No completed payments found.</p>
      <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
