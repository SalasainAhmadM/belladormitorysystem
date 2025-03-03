<?php
session_start();
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $regno = $_POST['regno'];
    $roomno = $_POST['roomno'];
    $amount_paid = $_POST['amount_paid'];
    $payment_method = $_POST['payment_method'];

    // Get the current month and Asia/Manila time
    date_default_timezone_set('Asia/Manila');
    $month = date('F Y'); // Example: "January 2025"
    $payment_date = date('Y-m-d H:i:s');

    // Ensure input validation and type casting for numeric values
    if (!is_numeric($regno) || !is_numeric($roomno) || !is_numeric($amount_paid)) {
        echo json_encode(["success" => false, "error" => "Invalid input data."]);
        exit;
    }

    // Insert into the monthly_payments table
    $stmt = $mysqli->prepare("INSERT INTO monthly_payments (regno, roomno, month, amount_paid, payment_method, payment_date) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iissss", $regno, $roomno, $month, $amount_paid, $payment_method, $payment_date);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => $mysqli->error]);
    }

    $mysqli->close();
}
?>