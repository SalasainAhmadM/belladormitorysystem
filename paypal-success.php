<?php
function paypalSuccess()
{
    session_start();
    include('includes/config.php');

    $regno = isset($_GET['regno']) ? intval($_GET['regno']) : 0;
    $roomno = isset($_GET['roomno']) ? intval($_GET['roomno']) : NULL;
    $amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;
    $month = date('F Y');

    if ($regno && $amount > 0) {
        $paymentMethod = 'PayPal';

        // Insert payment record into the database
        $query = "INSERT INTO monthly_payments (regno, roomno, month, amount_paid, payment_method) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("iisds", $regno, $roomno, $month, $amount, $paymentMethod);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Payment successfully recorded. Thank you!";
            header("Location: room-details.php?payment=success");
        } else {
            $_SESSION['error'] = "Failed to record payment. Please contact support.";
            header("Location: room-details.php?payment=failed");
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid payment details received.";
        header("Location: room-details.php?payment=failed");
    }

    $mysqli->close();
    exit();
}

paypalSuccess();
?>