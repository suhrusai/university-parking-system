<?php
$roles = Array("Admin","User","Faculty","Guest");
include_once '../dbConfig.php';
require_once "../authentication/isAuthenticated.php";
require_once "../authentication/checkAutorization.php";
checkAuthentication('../login.php');
checkAuthorization("../unautorized.php",$roles);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];
    $userId = $_SESSION['user_id']; // assuming the user's ID is stored in the session

    // Get the amount based on type
    $amount = 0;
    if ($type === 'violation') {
        $query = "SELECT vt.Penalty_Amount as Amount FROM violation v 
                  JOIN violation_type vt ON v.Violation_Type_ID = vt.Violation_Type_ID 
                  WHERE v.Violation_ID = ? AND v.Vehicle_ID IN (SELECT Vehicle_ID FROM vehicle WHERE Driver_ID = ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $stmt->bind_result($amount);
        $stmt->fetch();
        $stmt->close();
    } else if ($type === 'permit') {
        $query = "SELECT Cost FROM permit WHERE Permit_ID = ? AND Driver_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $stmt->bind_result($amount);
        $stmt->fetch();
        $stmt->close();
    } else {
        die('Invalid payment type!');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Make Payment</title>
  <script>
    function validateCreditCard() {
        const creditCardInput = document.getElementById('credit_card_no');
        const creditCardNumber = creditCardInput.value;
        const regex = /^[0-9]{13,19}$/; // Validating 13 to 19 digit credit card number

        if (!regex.test(creditCardNumber)) {
            alert('Please enter a valid credit card number');
            return false;
        }
        return true;
    }
  </script>
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
<div class="container mt-5 pt-5">


    <h2>Make Payment</h2>
    <form action="makePayment.php" method="post" onsubmit="return validateCreditCard();">
        <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="text" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($amount); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="credit_card_no" class="form-label">Credit Card Number</label>
            <input type="text" class="form-control" id="credit_card_no" name="credit_card_no" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form submission
    $type = $_POST['type'];
    $id = $_POST['id'];
    $userId = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $creditCardNo = $_POST['credit_card_no'];

    // Ensure $conn is available
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert new payment record
        $query = "INSERT INTO payment (Credit_Card_No, Amount, Date) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sd", $creditCardNo, $amount);
        $stmt->execute();
        $newPaymentId = $stmt->insert_id;
        $stmt->close();

        // Update the payment status for the respective table
        if ($type === 'violation') {
            $query = "UPDATE violation SET Payment_ID = ? WHERE Violation_ID = ? AND Vehicle_ID IN (SELECT Vehicle_ID FROM vehicle WHERE Driver_ID = ?)";
        } else if ($type === 'permit') {
            $query = "UPDATE permit SET Payment_ID = ? WHERE Permit_ID = ? AND Driver_ID = ?";
        }
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $newPaymentId, $id, $userId);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Payment successful!'); window.location.href = 'viewPayments.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "<script>alert('Payment failed! Please try again. Error: ".$e->getMessage()."'); window.location.href = 'viewPayments.php';</script>";
    }

    $conn->close();
} else {
    header("Location: viewPayments.php");
    exit();
}
?>
