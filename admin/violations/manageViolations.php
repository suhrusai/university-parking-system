<?php
// session_start();
$roles = Array("Admin");
require_once "../../dbConfig.php";
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unautorized.php",$roles);

// Fetch all violations
$query = "
    SELECT v.Violation_ID, v.Vehicle_ID, v.Datetime, v.Payment_ID, vt.Violation_Name, vt.Penalty_Amount, 
           d.Driver_ID, d.First_Name, d.Last_Name, ve.License_Plate
    FROM violation v 
    LEFT JOIN violation_type vt ON v.Violation_Type_ID = vt.Violation_Type_ID
    LEFT JOIN vehicle ve ON v.Vehicle_ID = ve.Vehicle_ID
    LEFT JOIN driver d ON ve.Driver_ID = d.Driver_ID";
$violationsResult = $conn->query($query);

// Fetch all violation types
$query = "SELECT Violation_Type_ID, Violation_Name, Penalty_Amount FROM violation_type";
$violationTypesResult = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Manage Violations</title>
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
    <h2>Manage Violations</h2>
    <div class="row">
        <div class="col-md-12">
            <a href="addViolation.php" class="btn btn-success mb-3">Add Violation</a>
            <?php if ($violationsResult->num_rows > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Driver ID</th>
                            <th scope="col">Driver Name</th>
                            <th scope="col">License Plate</th>
                            <th scope="col">Datetime</th>
                            <th scope="col">Violation Type</th>
                            <th scope="col">Penalty Amount</th>
                            <th scope="col">Payment Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $violationsResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['Driver_ID']; ?></td>
                                <td><?php echo $row['First_Name'] . ' ' . $row['Last_Name']; ?></td>
                                <td><?php echo $row['License_Plate']; ?></td>
                                <td><?php echo $row['Datetime']; ?></td>
                                <td><?php echo $row['Violation_Name']; ?></td>
                                <td><?php echo $row['Penalty_Amount']; ?></td>
                                <td><?php echo $row['Payment_ID'] ? "Paid" : "Not paid"; ?></td>
                                <td>
                                    <a href="editViolation.php?id=<?php echo $row['Violation_ID']; ?>" class="btn btn-primary">Edit</a>
                                    <form method="POST" action="deleteViolation.php" style="display:inline;">
                                        <input type="hidden" name="violation_id" value="<?php echo $row['Violation_ID']; ?>">
                                        <button type="submit" name="delete_violation" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this violation?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No violations found.</p>
            <?php endif; ?>
        </div>
    </div>

    <h2>Manage Violation Types</h2>
    <div class="row">
        <div class="col-md-12">
            <a href="addViolationType.php" class="btn btn-success mb-3">Add Violation Type</a>
            <?php if ($violationTypesResult->num_rows > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Violation Type ID</th>
                            <th scope="col">Violation Name</th>
                            <th scope="col">Penalty Amount</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $violationTypesResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['Violation_Type_ID']; ?></td>
                                <td><?php echo $row['Violation_Name']; ?></td>
                                <td><?php echo $row['Penalty_Amount']; ?></td>
                                <td>
                                    <a href="editViolationType.php?id=<?php echo $row['Violation_Type_ID']; ?>" class="btn btn-primary">Edit</a>
                                    <form method="POST" action="deleteViolationType.php" style="display:inline;">
                                        <input type="hidden" name="violation_type_id" value="<?php echo $row['Violation_Type_ID']; ?>">
                                        <button type="submit" name="delete_violation_type" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this violation type?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No violation types found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
