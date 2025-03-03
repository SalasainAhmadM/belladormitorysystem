<?php
session_start();
include('includes/config.php');

if (isset($_GET['requestId']) && isset($_GET['payment'])) {
    $requestId = intval($_GET['requestId']);
    $paymentStatus = $_GET['payment'];

    if ($paymentStatus == 'success') {
        // Update payment status in database
        $stmt = $mysqli->prepare("UPDATE maintenance SET payment = fee WHERE id = ?");
        $stmt->bind_param("i", $requestId);

        if ($stmt->execute()) {
            echo "<script>alert('Payment Successful!'); window.location.href='book-maintenance.php';</script>";
        } else {
            echo "<script>alert('Database update failed.'); window.location.href='book-maintenance.php';</script>";
        }
    } else {
        echo "<script>alert('Payment was canceled.'); window.location.href='book-maintenance.php';</script>";
    }
}
$requested_by = $_SESSION['id'];

// Fetch user's regNo from userregistration
$query = "SELECT regNo FROM userregistration WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $requested_by);
$stmt->execute();
$stmt->bind_result($regNo);
$stmt->fetch();
$stmt->close();

// Fetch room number from registration using regNo
$query = "SELECT roomno FROM registration WHERE regno = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $regNo); // regNo is varchar
$stmt->execute();
$stmt->bind_result($room_no);
$stmt->fetch();
$stmt->close();

// Default to "No Room Booked Yet" if no room found
if (empty($room_no)) {
    $room_no = "No Room Booked Yet";
}

// Fetch available maintenance workers
$query = "SELECT id, name, profession, status, fee FROM maintenance WHERE status = 'available'";
$result = $mysqli->query($query);
$workers = $result->fetch_all(MYSQLI_ASSOC);

// Fetch maintenance requests by user
$query = "SELECT * FROM maintenance WHERE requested_by = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $requested_by);
$stmt->execute();
$requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Set timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Handle maintenance booking
if ($room_no !== "No Room Booked Yet" && isset($_POST['book_maintenance'], $_POST['worker_id'])) {
    $worker_id = $_POST['worker_id'];
    $description = $_POST['description'];
    $fee = $_POST['fee'];
    $payment_method = $_POST['payment_method'];
    $status = 'working';
    $requested_time = date('Y-m-d H:i:s'); // Get Manila time

    $stmt = $mysqli->prepare("UPDATE maintenance 
                              SET requested_by = ?, room_no = ?, issue = ?, fee = ?, status = ?, 
                                  payment_method = ?, requested_time = ? 
                              WHERE id = ?");
    $stmt->bind_param("sssssssi", $requested_by, $room_no, $description, $fee, $status, $payment_method, $requested_time, $worker_id);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        echo "<script>alert('Maintenance request submitted successfully.');</script>";
    } else {
        echo "<script>alert('There was an error. Please try again later.');</script>";
    }
}


?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Maintenance</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">>
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
</head>
<style>
    .side-by-side-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .form-section,
    .table-section {
        flex: 1;
    }
</style>

<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div style="margin-top: 20px" class="container-fluid">
                <h2 class="page-title">Book Maintenance</h2>
                <div class="side-by-side-container">
                    <div class="form-section">
                        <div class="well">
                            <?php if ($room_no === "No Room Booked Yet") { ?>
                                <p class="alert alert-warning">You cannot request maintenance as you have not booked a room
                                    yet.</p>
                            <?php } else { ?>
                                <form action="" method="post">
                                    <input type="hidden" name="book_maintenance" value="1"> <!-- Hidden flag -->
                                    <label for="worker_id">Select Worker</label>
                                    <select name="worker_id" id="worker_id" class="form-control mb" required>
                                        <option value="">Select Worker</option>
                                        <?php foreach ($workers as $worker) { ?>
                                            <option value="<?php echo $worker['id']; ?>"
                                                data-fee="<?php echo $worker['fee']; ?>">
                                                <?php echo $worker['name'] . ' - ' . $worker['profession'] . ' (' . $worker['status'] . ')'; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <label for="room_no">Room Number</label>
                                    <input type="text" name="room_no" class="form-control mb"
                                        value="<?php echo $room_no; ?>" readonly>

                                    <label for="description">Issue Description</label>
                                    <textarea name="description" class="form-control mb" rows="4" required></textarea>

                                    <label for="fee">Fee</label>
                                    <input type="text" id="fee" name="fee" class="form-control mb" required readonly>

                                    <input type="hidden" name="payment_method" id="payment_method">

                                    <button type="button" class="btn btn-primary btn-block" id="openPaymentModal">Submit
                                        Request</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="table-section">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Worker</th>
                                    <th>Room No</th>
                                    <th>Issue</th>
                                    <th>Fee</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $request) { ?>
                                    <tr>
                                        <td><?php echo $request['id']; ?></td>
                                        <td><?php echo $request['name']; ?></td>
                                        <td><?php echo $request['room_no']; ?></td>
                                        <td><?php echo $request['issue']; ?></td>
                                        <td><?php echo $request['fee']; ?></td>
                                        <td><?php echo $request['status']; ?></td>
                                        <td><?php echo $request['payment_method']; ?></td>
                                        <td><?php echo $request['payment'] ? $request['payment'] : 'Not Paid'; ?></td>
                                        <td>
                                            <!-- Payment Button for Cash -->
                                            <?php if ($request['payment_method'] == 'Cash') { ?>
                                                <button class="btn btn-primary btn-sm mt-2" data-toggle="modal"
                                                    data-target="#paymentModal-<?php echo $request['id']; ?>">
                                                    Pay Now
                                                </button>
                                            <?php } elseif ($request['payment_method'] == 'PayPal') { ?>
                                                <!-- PayPal Payment Button (Triggers Modal Instead of Direct Redirection) -->
                                                <button class="btn btn-warning btn-sm mt-2" data-toggle="modal"
                                                    data-target="#paymentModal-<?php echo $request['id']; ?>">
                                                    Pay with PayPal
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>

                                    <div id="paymentModal-<?php echo $request['id']; ?>" class="modal fade" tabindex="-1"
                                        role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Confirm Payment for Request
                                                        #<?php echo $request['id']; ?></h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Payment Amount:</p>

                                                    <?php
                                                    // Fetch current payment status from the database
                                                    $stmt = $mysqli->prepare("SELECT payment FROM maintenance WHERE id = ?");
                                                    $stmt->bind_param("i", $request['id']);
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    $row = $result->fetch_assoc();

                                                    $currentPayment = $row ? floatval($row['payment']) : 0; // Amount already paid
                                                    $remainingBalance = floatval($request['fee']) - $currentPayment; // Calculate balance
                                                    ?>

                                                    <input type="number" class="form-control payment-amount mb-3"
                                                        id="paymentAmount-<?php echo $request['id']; ?>"
                                                        value="<?php echo $remainingBalance; ?>"
                                                        max="<?php echo $remainingBalance; ?>" min="1"
                                                        oninput="validateAmount(<?php echo $request['id']; ?>, <?php echo $remainingBalance; ?>)">

                                                    <p>Do you want to confirm this payment?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>

                                                    <!-- Conditional Button for Cash or PayPal -->
                                                    <?php if ($request['payment_method'] == 'Cash') { ?>
                                                        <button type="button" class="btn btn-success confirm-payment-button"
                                                            data-id="<?php echo $request['id']; ?>">
                                                            Confirm Payment
                                                        </button>
                                                    <?php } elseif ($request['payment_method'] == 'PayPal') { ?>
                                                        <button type="button" class="btn btn-success"
                                                            onclick="processPayPalPayment('<?php echo $request['id']; ?>', '<?php echo $request['name']; ?>', <?php echo $request['fee']; ?>, <?php echo $currentPayment; ?>)">
                                                            Proceed to PayPal
                                                        </button>
                                                    <?php } ?>
                                                </div>

                                            </div>
                                        </div>

                                    <?php } ?>
                            </tbody>



                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Payment Modal -->
    <div id="paymentModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Select Payment Method</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Choose your preferred payment method:</p>
                    <button type="button" class="btn btn-success btn-block" id="payCash">Cash</button>
                    <button type="button" class="btn btn-info btn-block" id="payPaypal">PayPal</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateAmount(requestId, remainingBalance) {
            let inputField = document.getElementById('paymentAmount-' + requestId);
            let enteredAmount = parseFloat(inputField.value);

            if (isNaN(enteredAmount) || enteredAmount <= 0) {
                inputField.value = 1; // Reset to minimum valid amount
            } else if (enteredAmount > remainingBalance) {
                inputField.value = remainingBalance; // Restrict to remaining balance
                alert("You cannot pay more than the remaining balance: ₱" + remainingBalance.toFixed(2));
            }
        }


        function processPayPalPayment(requestId, name, fee, currentPayment) {
            let amountInput = document.getElementById('paymentAmount-' + requestId);
            let amount = parseFloat(amountInput.value);
            let remainingBalance = fee - currentPayment;

            // Validate the payment amount
            if (isNaN(amount) || amount <= 0) {
                alert("Please enter a valid amount.");
                return;
            }

            if (amount > remainingBalance) {
                alert("You cannot pay more than the remaining balance: ₱" + remainingBalance.toFixed(2));
                return;
            }

            // Construct return and cancel URLs with the correct amount
            let returnUrl = "https://saddlebrown-marten-993083.hostingersite.com/paypal-success-maintenance.php?id=" + encodeURIComponent(requestId) + "&amount=" + encodeURIComponent(amount);
            let cancelUrl = "https://saddlebrown-marten-993083.hostingersite.com/book-maintenance.php?requestId=" + encodeURIComponent(requestId) + "&payment=failed";

            // Redirect to PayPal with the exact amount
            window.location.href = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sb-daa2k32419423@business.example.com&item_name=" +
                encodeURIComponent(name) + "&amount=" + amount + "&currency_code=PHP&return=" + encodeURIComponent(returnUrl) + "&cancel_return=" + encodeURIComponent(cancelUrl);
        }

        // AJAX Request for Cash Payments
        $(document).ready(function () {
            $(document).on("click", ".confirm-payment-button", function () {
                var requestId = $(this).data("id");
                var amount = $('#paymentAmount-' + requestId).val();

                $.ajax({
                    url: "pay-maintenance.php",
                    type: "POST",
                    data: { id: requestId, amount: amount },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload(); // Refresh the page to update the table
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert("An error occurred. Please try again.");
                    }
                });
            });
        });

        $(document).ready(function () {
            // Handle worker fee selection
            $('#worker_id').change(function () {
                var selectedFee = $(this).find(':selected').data('fee');
                $('#fee').val(selectedFee);
            });

            // Open payment modal
            $('#openPaymentModal').click(function () {
                $('#paymentModal').modal('show');
            });

            // Handle payment button click and submit form
            $('#payCash, #payPaypal').click(function () {
                var paymentMethod = $(this).attr('id') === 'payCash' ? 'Cash' : 'PayPal';
                $('#payment_method').val(paymentMethod);

                $('form').submit();
            });
        });

        // Function to check for success message in the URL
        function checkPaymentSuccess() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('payment') && urlParams.get('payment') === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful!',
                    text: 'Your payment has been processed successfully.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Remove 'payment=success' from the URL to prevent alert on refresh
                    const newUrl = window.location.origin + window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                });
            }
        }

        // Run the function after the page loads
        window.onload = checkPaymentSuccess;
    </script>

    <!-- Include SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>
    <script>
        $(document).ready(function () {
            $('#worker_id').change(function () {
                var selectedFee = $(this).find(':selected').data('fee');
                $('#fee').val(selectedFee);
            });
        });
    </script>
</body>

</html>