<?php

include_once '../dbConfig.php';
include_once '../authentication/hashPassword.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = hashPassword($_POST['password']);
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    // Define allowed roles
    $allowed_roles = ['Student', 'Faculty', 'Guest'];

    if (!in_array($role, $allowed_roles)) {
        $error_message = "Invalid role selected.";
    } else {
        // Check if the user already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM driver WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $error_message = "Email already exists. Please login";
            } else {
                // Proceed with inserting the new user
                $stmt = $conn->prepare("INSERT INTO driver (email, password, first_name, last_name, address, role) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ssssss", $email, $password, $firstName, $lastName, $address, $role);

                    // Execute the statement
                    if ($stmt->execute()) {
                        $_SESSION['userCreatedMessage'] = 'User added. Please login below';
                        echo "User Added";
                    } else {
                        $error_message = "Error: " . $stmt->error;
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    $error_message = "Error preparing statement: " . $conn->error;
                }
            }
        } else {
            $error_message = "Error preparing statement: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      background: url('../assets/univeristy_background_image.jpg');
      background-size: cover;
    }
    .background-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
      z-index: 1;
    }
    .card {
      position: relative;
      z-index: 2;
    }
    .alert {
      z-index: 3;
      position: relative;
    }
  </style>
</head>
<body>
  <div class="background-overlay"></div>
  <div class="card" style="width:50rem">
    <div class="card-body">
        <a href="../login" class="btn btn-primary"><i class="bi bi-check"></i>Login</a>
        <h3 class="card-title">New user registration</h3>
        <br>
        <?php
            if (isset($error_message)) {
                echo '<div class="alert alert-danger">' . $error_message . '</div>';
            }
          ?>
        <form method="POST" action="">
            <div class="mb-3 row">
                <label for="email" class="col-sm-2 col-form-label"><b>Email</b></label>
                <div class="col-sm-10">
                <input type="email" class="form-control" name="email" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="password" class="col-sm-2 col-form-label"><b>Password</b></label>
                <div class="col-sm-10">
                <input type="password" class="form-control" name="password" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="firstName" class="col-sm-2 col-form-label"><b>First Name</b></label>
                <div class="col-sm-10">
                <input type="text" class="form-control" name="firstName" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="lastName" class="col-sm-2 col-form-label"><b>Last Name</b></label>
                <div class="col-sm-10">
                <input type="text" class="form-control" name="lastName" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="address" class="col-sm-2 col-form-label"><b>Address</b></label>
                <div class="col-sm-10">
                <input type="text" class="form-control" name="address" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="role" class="col-sm-2 col-form-label"><b>Role</b></label>
                <div class="col-sm-10">
                <select class="form-control" name="role" required>
                    <option value="">Select Role</option>
                    <option value="Student">Student</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Guest">Guest</option>
                </select>
                </div>
            </div>
            <div class="row">
                <div class="offset-sm-10 col-sm-2">
                <button class="btn btn-primary" style="float: right" type="submit">Register</button>
                </div>
            </div>
        </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
