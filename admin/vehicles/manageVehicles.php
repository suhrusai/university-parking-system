<?php
$roles = Array("Admin");
require_once "../../authentication/isAuthenticated.php";
require_once "../../dbConfig.php";
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unauthorized.php",$roles);
// Fetch all vehicles
$query = "
    SELECT v.Vehicle_ID, v.Driver_ID, v.License_Plate, v.Make, v.Model, v.Color, v.Year,
           d.First_Name, d.Last_Name
    FROM vehicle v
    LEFT JOIN driver d ON v.Driver_ID = d.Driver_ID";
$vehiclesResult = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Manage Vehicles</title>
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
    <h2>Manage Vehicles</h2>
    <div class="row">
        <div class="col-md-12">
            <a href="addVehicle.php" class="btn btn-success mb-3">Add Vehicle</a>
            <?php if ($vehiclesResult->num_rows > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Vehicle ID</th>
                            <th scope="col">Driver</th>
                            <th scope="col">License Plate</th>
                            <th scope="col">Make</th>
                            <th scope="col">Model</th>
                            <th scope="col">Color</th>
                            <th scope="col">Year</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $vehiclesResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['Vehicle_ID']; ?></td>
                                <td><?php echo $row['First_Name'] . ' ' . $row['Last_Name']; ?></td>
                                <td><?php echo $row['License_Plate']; ?></td>
                                <td><?php echo $row['Make']; ?></td>
                                <td><?php echo $row['Model']; ?></td>
                                <td><?php echo $row['Color']; ?></td>
                                <td><?php echo $row['Year']; ?></td>
                                <td>
                                    <a href="editVehicle.php?id=<?php echo $row['Vehicle_ID']; ?>" class="btn btn-primary">Edit</a>
                                    <form method="POST" action="deleteVehicle.php" style="display:inline;">
                                        <input type="hidden" name="vehicle_id" value="<?php echo $row['Vehicle_ID']; ?>">
                                        <button type="submit" name="delete_vehicle" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this vehicle?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No vehicles found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
