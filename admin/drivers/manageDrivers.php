<?php
$roles = Array("Admin");
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
require_once "../../dbConfig.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unautorized.php",$roles);

// Fetch all drivers
$query = "SELECT Driver_ID, First_Name, Last_Name, Email, Role FROM driver ORDER BY Driver_ID";
$result = $conn->query($query);

// Handle delete driver
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_driver'])) {
    $driverId = $_POST['driver_id'];
    $deleteQuery = "DELETE FROM driver WHERE Driver_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $driverId);
    if ($stmt->execute()) {
        echo "<script>alert('Driver deleted successfully!'); window.location.href = 'manageDrivers.php';</script>";
    } else {
        echo "<script>alert('Failed to delete driver!'); window.location.href = 'manageDrivers.php';</script>";
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
  <title>Manage Drivers</title>
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
    <h2>Manage Drivers</h2>
    <div class="row">
        <div class="col-md-12">
            <!-- <a href="addDriver.php" class="btn btn-success mb-3">Add Driver</a> -->
            <?php if ($result->num_rows > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Driver ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['Driver_ID']; ?></td>
                                <td><?php echo $row['First_Name']; ?></td>
                                <td><?php echo $row['Last_Name']; ?></td>
                                <td><?php echo $row['Email']; ?></td>
                                <td><?php echo $row['Role']; ?></td>
                                <td>
                                    <a href="editDriver.php?id=<?php echo $row['Driver_ID']; ?>" class="btn btn-primary">Edit</a>
                                    <form method="POST" action="manageDrivers.php" style="display:inline;">
                                        <input type="hidden" name="driver_id" value="<?php echo $row['Driver_ID']; ?>">
                                        <button type="submit" name="delete_driver" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this driver?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No drivers found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
