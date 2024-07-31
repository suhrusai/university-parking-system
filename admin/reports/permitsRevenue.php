<?php
$roles = Array("Admin");
require_once "../../authentication/isAuthenticated.php";
require_once "../../authentication/checkAutorization.php";
require_once "../../dbConfig.php";
checkAuthentication('../../login.php');
checkAuthorization("../../unautorized.php",$roles);



// Fetch distinct months that have permits
$stmt = $conn->prepare("
    select distinct(DATE_SUB(LAST_DAY(p.Purchase_Date), INTERVAL DAY(LAST_DAY(p.Purchase_Date)) - 1 DAY)) AS Purchase_Month
    from permit as p
    join payment as pay on p.Payment_ID = pay.Payment_ID
    where pay.Amount > 0");
$stmt->execute();
$months = $stmt->get_result();
$stmt->close();

$last_month = null;
while ($row = $months->fetch_assoc()): 
    $last_month = $row['Purchase_Month'];
endwhile;

// Fetch permits information from the database
if(isset($_POST['report_month'])){
    $qry_month = $_POST['report_month'];
} else {
    $qry_month = $last_month;
}

$stmt = $conn->prepare("
    select Permit_Type,
        sum(Amount) as Revenue
    from (
        select p.Permit_Type,
            DATE_SUB(LAST_DAY(p.Purchase_Date), INTERVAL DAY(LAST_DAY(p.Purchase_Date)) - 1 DAY) AS Purchase_Month,
            pay.Amount
        from permit as p
        join payment as pay on p.Payment_ID = pay.Payment_ID
        ) as qry_a
    where Purchase_Month = ?
    group by Permit_Type
    order by Permit_Type");
$stmt->bind_param("s",$qry_month);
$stmt->execute();
$result = $stmt->get_result();
$permits = [];
$totalRevenue = 0;
while($row = $result->fetch_assoc()){
    $permits[] = $row;
    $totalRevenue += $row['Revenue'];
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

    <div class="container mt-5 pt-5">
        
        <h2>Revenue from Permits - <?php echo (new DateTime($qry_month))->format('M Y') ?></h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Permit</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($permits)): ?>
                        <tr>
                            <td colspan="2" class="text-center">There are currently no permits for this period.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($permits as $permit): ?>
                            <tr>
                                <td><?php echo $permit['Permit_Type']; ?></td>
                                <td>$<?php echo $permit['Revenue']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="text-end"><strong>Total</strong></td>
                            <td><strong>$<?php echo number_format($totalRevenue, 2); ?></strong></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <p>&nbsp;</p>
        <form method="POST" action="permitsRevenue.php">
            <label for="report_month">Select a month for a new report:</label>
            <select name="report_month" id="report_month">
                <?php foreach ($months as $row): ?>
                    <option value="<?= htmlspecialchars($row['Purchase_Month']) ?>"><?= htmlspecialchars($row['Purchase_Month']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Submit">
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
