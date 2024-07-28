<?php
include_once 'dbConfig.php';
include_once './authentication/hashPassword.php';
if(isset($_SESSION['userCreatedMessage']))
  $userCreatedMessage = $_SESSION['userCreatedMessage'];
$error_message = isset($_SESSION['errorMessage']) ?  $_SESSION['errorMessage'] : null;
unset($_SESSION['errorMessage']);
unset($_SESSION['userCreatedMessage']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM driver WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    // print_r($result->fetch_assoc());
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['Driver_ID']; 
            $_SESSION['role'] = $user['Role'];
            header("Location: homepage.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with that email.";
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
  <style>
    body, html {
      height: 100%;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      background: url('assets/univeristy_background_image.jpg');
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
    }
  </style>
</head>
<body>
  <div class="background-overlay"></div>
  <div class="card" style="width: 24rem;">
    <img src="assets/the-university-of-utah.jpg" class="card-img-top" alt="...">
    <div class="card-body">
      <?php
          if (isset($error_message)) {
              echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
          }
          if (isset($userCreatedMessage)) {
            echo '<div class="alert alert-success" role="alert">' . $userCreatedMessage . '</div>';
          }
        ?>
      <h5 class="card-title">Login</h5>
      <p class="card-text">
       <form  method="POST">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" placeholder="u1234567@utah.edu">
            </div>
            <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control">
            </div>      

          <p>New user? <a href="./personal-information/registerUser.php">Click here</a></p>      
          </p>
          <button href="./homepage.php" class="btn btn-primary" type="submit">Login</>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
