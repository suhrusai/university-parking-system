<?php
$roles = Array("Admin","User","Faculty","Guest");
require_once "../dbConfig.php";
require_once "../authentication/isAuthenticated.php";
require_once "../authentication/checkAutorization.php";
checkAuthentication('../login.php');
checkAuthorization("../unautorized.php",$roles);
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

// Fetch user information from the database
$stmt = $conn->prepare("SELECT first_name, last_name, address, email FROM driver WHERE driver_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($firstName, $lastName, $address, $email);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>View User Details</title>
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
    <div class="container mt-1">
        <a href="../homepage.php" class="btn btn-secondary mb-2">Back</a>
        <h2>User Details</h2>
        <p><b>First Name</b>: <?php echo htmlspecialchars($firstName); ?></p>
        <p><b>Last Name</b>: <?php echo htmlspecialchars($lastName); ?></p>
        <p><b>Address</b>: <?php echo htmlspecialchars($address); ?></p>
        <p><b>Email</b>: <?php echo htmlspecialchars($email); ?></p>
        <div class="d-flex">
            <div>
                <a class="btn btn-primary" href="./updateUser.php?user_id=<?php echo $userId; ?>">Update Details</a>
            </div>
            <div class="ms-2">
                <form action="./deleteUser.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
