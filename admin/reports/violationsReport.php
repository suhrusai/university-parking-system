<?php
$roles = Array("Admin");
require_once "../../dbConfig.php";
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unauthorized.php",$roles);

// Fetch outstanding violations information from the database
$stmt = $conn->prepare("
    select *
    from (
    select concat(d.First_Name, ' ', d.Last_Name) as Driver,
        vt.Violation_Name as Violation,
        v.Datetime as Occurred,
        vt.Penalty_Amount as Fine,
        @paid := case when p.Amount is null then 0 else p.Amount end as Paid, 
        @remaining := vt.Penalty_Amount - @paid as Remaining
    from violation as v
    join violation_type as vt on v.Violation_Type_ID = vt.Violation_Type_ID
    left join payment as p on v.Payment_ID = p.Payment_ID
    left join vehicle as c on v.Vehicle_ID = c.Vehicle_ID
    left join driver as d on c.Driver_ID = d.Driver_ID
    ) as qry_a
    where Remaining > 0
    ");
$stmt->execute();
$result = $stmt->get_result();
$violations = [];
$totalFine = 0;
$totalPaid = 0;
$totalRemaining = 0;
while($row = $result->fetch_assoc()){
    $violations[] = $row;
    $totalFine += $row['Fine'];
    $totalPaid += $row['Paid'];
    $totalRemaining += $row['Remaining'];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Outstanding Violations Report</title>
  <style>
    body {
      padding-top: 80px;
      background-color: #f8f9fa;
    }
    .navbar-brand img {
      margin-right: 10px;
    }
  </style>
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

    <div class="container mt-5 pt-5">
        <h2>Outstanding Violations Report</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Violation</th>
                        <th>Occurred</th>
                        <th>Fine</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($violations)): ?>
                        <tr>
                            <td colspan="6" class="text-center">There are currently no outstanding violations.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($violations as $violation): ?>
                            <tr>
                                <td><b><?php echo $violation['Driver']; ?></b></td>
                                <td><?php echo $violation['Violation']; ?></td>
                                <td><?php echo (new DateTime($violation['Occurred']))->format('Y-m-d H:i:s'); ?></td>
                                <td>$<?php echo $violation['Fine']; ?></td>
                                <td>$<?php echo $violation['Paid']; ?></td>
                                <td>$<?php echo $violation['Remaining']; ?>.00</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td><strong>$<?php echo number_format($totalFine, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($totalPaid, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($totalRemaining, 2); ?></strong></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
