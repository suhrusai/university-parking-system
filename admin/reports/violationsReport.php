<?php
require_once "../../dbConfig.php";
require_once "../../authentication/isAuthenticated.php";
//checkAuthentication('../../login.php');
//$userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

// Fetch outstanding violations information from the database

$stmt = $conn->prepare("select *
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
    where Remaining > 0");
$stmt->execute();
$result = $stmt->get_result();
$violations = [];
while($row = $result->fetch_assoc()){
    $violations[] = $row;
}
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
        <a class="navbar-brand" href="#"><img src="../../assets/university_of_utah_logo.png" width="30px" alt="Logo"> Parking Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="../../homepage.php">Home</a>
            </li>
            </ul>
        </div>
        </div>
    </nav>
    <div class="container">
        <h2>Outstanding Violations Report</h2>
        


        <table width="80%">
<?php
if (empty($violations)):
echo <<<_END
    <tr>
        <td colspan="5">There are currently no outstainding violations.</td>
    </tr>
_END;
else:
    foreach ($violations as $violation):
echo <<<_END
        <tr>
            <td colspan="5"><b>$violation[Driver]<b></td>
        </tr>
        <tr>
            <td>$violation[Violation]</td>
            <td>$violation[Occurred]</td>
            <td>$$violation[Fine]</td>
            <td>$$violation[Paid]</td>
            <td>$$violation[Remaining].00</td>
        </tr>
_END;
    endforeach;
endif;
?>
        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
