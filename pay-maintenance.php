<?php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['amount'])) {
        $id = intval($_POST['id']); // Sanitize input
        $amountPaid = floatval($_POST['amount']); // Ensure valid numeric value

        // Fetch the existing payment details
        $stmt = $mysqli->prepare("SELECT fee, payment, payment_method FROM maintenance WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            echo json_encode(["success" => false, "message" => "Request not found."]);
            exit;
        }

        $fee = floatval($row['fee']);  // Convert fee to float
        $currentPayment = floatval($row['payment']);  // Already paid amount
        $paymentMethod = $row['payment_method'];

        // Calculate remaining balance
        $remainingBalance = $fee - $currentPayment;

        // Validate payment amount
        if ($amountPaid <= 0 || $amountPaid > $remainingBalance) {
            echo json_encode(["success" => false, "message" => "Invalid payment amount. You can only pay up to ₱" . number_format($remainingBalance, 2)]);
            exit;
        }

        // New total payment after adding this transaction
        $newPaymentTotal = $currentPayment + $amountPaid;

        // Handle Cash Payments
        if ($paymentMethod == 'Cash') {
            $updateStmt = $mysqli->prepare("UPDATE maintenance SET payment = ? WHERE id = ?");
            $updateStmt->bind_param("di", $newPaymentTotal, $id);

            if ($updateStmt->execute()) {
                echo json_encode(["success" => true, "message" => "Payment recorded successfully! Total Paid: ₱" . number_format($newPaymentTotal, 2)]);
            } else {
                echo json_encode(["success" => false, "message" => "Database update failed."]);
            }

            // Handle PayPal Payments (Redirect to Success Page)
        } elseif ($paymentMethod == 'PayPal') {
            $returnUrl = "saddlebrown-marten-993083.hostingersite.com/paypal-success-maintenance.php?id=$id&amount=$amountPaid";
            echo json_encode(["success" => true, "redirect" => $returnUrl]);

        } else {
            echo json_encode(["success" => false, "message" => "Invalid payment method."]);
        }

    } else {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>