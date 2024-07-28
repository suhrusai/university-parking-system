<?php
    require_once "./authentication/isAuthenticated.php";
    checkAuthentication('login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 56px;
      background-color: #f8f9fa;
    }
    .card-section {
      margin-top: 30px;
    }
    .card-icon {
      font-size: 24px;
      margin-right: 10px;
    }
    a.card-link {
      text-decoration: none;
      color: inherit;
    }
  </style>
  <title>Parking Management System</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="assets/university_of_utah_logo.png" width="30px" alt="Logo"> Parking Management</a>
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

  <div class="container card-section">
    <h2>Driver</h2>
    <div class="row">
      <div class="col-md-4">
        <a href="./personal-information/viewUser.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-person card-icon"></i>Personal Information</h5>
              <p class="card-text">View, update, or add personal info.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./vehicle-information/viewVehicles.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-car-front card-icon"></i>Vehicle Information</h5>
              <p class="card-text">View, update, add, or delete vehicle info.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./permit-information/viewPermits.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-card-checklist card-icon"></i>Permits</h5>
              <p class="card-text">View or add permits.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./payment-information/viewPayments.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-currency-dollar card-icon"></i>Payments</h5>
              <p class="card-text">Make payment for a permit or violation.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <div class="container card-section">
    <h2>Administrator</h2>
    <div class="row">
      <div class="col-md-4">
        <a href="./admin/drivers/viewDrivers.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-people card-icon"></i>Drivers and Vehicles</h5>
              <p class="card-text">View a list of drivers and their vehicles.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./admin/drivers/manageDrivers.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-person-plus card-icon"></i>Manage Drivers</h5>
              <p class="card-text">View, update, add, or delete drivers.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./admin/vehicles/manageVehicles.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-car-front card-icon"></i>Manage Vehicles</h5>
              <p class="card-text">View, update, add, or delete vehicles.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./admin/permits/managePermits.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-card-checklist card-icon"></i>Manage Permits</h5>
              <p class="card-text">View, update, add, or delete permits.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./admin/violations/manageViolations.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-exclamation-circle card-icon"></i>Manage Violations</h5>
              <p class="card-text">View, update, add, or delete violations and types.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./admin/parking/manageParking.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-house-door card-icon"></i>Manage Parking</h5>
              <p class="card-text">View, update, add, or delete parking lots and spaces.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="./admin/reports/generateReports.php" class="card-link">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-graph-up card-icon"></i>Generate Reports</h5>
              <p class="card-text">Generate various reports for violations and revenue.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
