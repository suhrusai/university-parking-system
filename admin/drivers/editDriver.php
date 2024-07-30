<?php
// session_start();
require_once "../../authentication/isAuthenticated.php";
require_once "../../dbConfig.php";
checkAuthentication('../../login.php');

// Check if the logged-in user is an admin
if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $driverId = $_GET['id'];

    $query = "SELECT Driver_ID, First_Name, Last_Name, Email, Role FROM driver WHERE Driver_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $driverId);
    $stmt->execute();
    $result = $stmt->get_result();
    $driver = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_driver'])) {
    $driverId = $_POST['driver_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $query = "UPDATE driver SET First_Name = ?, Last_Name = ?, Email = ?, Role = ? WHERE Driver_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $firstName, $lastName, $email, $role, $driverId);

    if ($stmt->execute()) {
        echo "<script>alert('Driver updated successfully!'); window.location.href = 'manageDrivers.php';</script>";
    } else {
        echo "<script>alert('Failed to update driver!'); window.location.href = 'editDriver.php?id=$driverId';</script>";
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
  <title>Edit Driver</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="../../assets/university_of_utah_logo.png" width="30px" alt="Logo"> Parking Management</a>
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

<div class="container card-section mt-5 pt-5">
    <h2>Edit Driver</h2>
    <form method="POST" action="editDriver.php">
        <input type="hidden" name="driver_id" value="<?php echo $driver['Driver_ID']; ?>">
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $driver['First_Name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $driver['Last_Name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $driver['Email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="Student" <?php echo ($driver['Role'] == 'Student') ? 'selected' : ''; ?>>Student</option>
                <option value="Faculty" <?php echo ($driver['Role'] == 'Faculty') ? 'selected' : ''; ?>>Faculty</option>
                <option value="Staff" <?php echo ($driver['Role'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                <option value="Admin" <?php echo ($driver['Role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <button type="submit" name="update_driver" class="btn btn-primary">Update Driver</button>
        <a href="manageDrivers.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
