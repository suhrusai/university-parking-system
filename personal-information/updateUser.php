<?php
$roles = Array("Admin","User","Faculty","Guest");
include_once '../dbConfig.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];

    // Update user information
    $stmt = $conn->prepare("UPDATE driver SET first_name = ?, last_name = ?, address = ? WHERE driver_id = ?");
    $stmt->bind_param("sssi", $firstName, $lastName, $address, $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User information updated successfully.";
        header("Location: viewUser.php");
        exit();
    } else {
        $error_message = "Error updating user information: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Fetch current user information
    $stmt = $conn->prepare("SELECT first_name, last_name, address FROM driver WHERE driver_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName, $address);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Editable Driver Information</title>
</head>
<body>
  <div class="container mt-5">
    <form method="POST" action="">
      <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
      <?php endif; ?>
      <div class="mb-3 row">
        <label for="firstName" class="col-sm-2 col-form-label"><b>First Name</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="lastName" class="col-sm-2 col-form-label"><b>Last Name</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" required>
        </div>
      </div>
      <div class="mb-3 row">
        <label for="address" class="col-sm-2 col-form-label"><b>Address</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-10 offset-sm-2">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
