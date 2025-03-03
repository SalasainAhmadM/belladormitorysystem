<?php
session_start();
include('includes/config.php');

if (isset($_GET['id']) && isset($_GET['amount'])) {
    $id = intval($_GET['id']);
    $amountPaid = floatval($_GET['amount']);
    $paymentMethod = "PayPal";

    // Get current date and time in Asia/Manila timezone
    date_default_timezone_set('Asia/Manila');

    // Fetch existing payment details
    $stmt = $mysqli->prepare("SELECT fee, payment FROM maintenance WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        $_SESSION['error'] = "Request not found.";
        header("Location: book-maintenance.php?payment=failed");
        exit();
    }

    $fee = floatval($row['fee']);
    $currentPayment = floatval($row['payment']);
    $newPaymentTotal = $currentPayment + $amountPaid;

    // Ensure total payment does not exceed the fee
    if ($newPaymentTotal > $fee) {
        $_SESSION['error'] = "Payment exceeds the required amount.";
        header("Location: book-maintenance.php?payment=failed");
        exit();
    }

    // Update the maintenance payment record with the correct amount
    $updateStmt = $mysqli->prepare("UPDATE maintenance SET payment = ?, payment_method = ? WHERE id = ?");
    $updateStmt->bind_param("dsi", $newPaymentTotal, $paymentMethod, $id);

    if ($updateStmt->execute()) {
        $_SESSION['success'] = "PayPal payment recorded successfully! Total Paid: ₱" . number_format($newPaymentTotal, 2);
        header("Location: book-maintenance.php?payment=success");
        exit();
    } else {
        $_SESSION['error'] = "Error processing PayPal payment.";
        header("Location: book-maintenance.php?payment=failed");
        exit();
    }

} else {
    $_SESSION['error'] = "Invalid PayPal response.";
    header("Location: book-maintenance.php?payment=failed");
    exit();
}
?>