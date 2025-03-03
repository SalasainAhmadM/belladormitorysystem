<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
?>
<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	<title>Room Details</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<script language="javascript" type="text/javascript">
		var popUpWin = 0;
		function popUpWindow(URLStr, left, top, width, height) {
			if (popUpWin) {
				if (!popUpWin.closed) popUpWin.close();
			}
			popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width=' + 510 + ',height=' + 430 + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' + top + '');
		}

	</script>

</head>

<body>
	<?php include('includes/header.php'); ?>

	<div class="ts-main-content">
		<?php include('includes/sidebar.php'); ?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h2 class="page-title" style="margin-top:2%">Rooms Details</h2>
						<div class="panel panel-default">
							<div class="panel-heading">All Room Details</div>
							<div class="panel-body">
								<table id="zctb" class="table table-bordered " cellspacing="0" width="100%">


									<tbody>
										<?php
										$aid = $_SESSION['login'];
										$ret = "select * from registration where emailid=?";
										$stmt = $mysqli->prepare($ret);
										$stmt->bind_param('s', $aid);
										$stmt->execute();
										$res = $stmt->get_result();
										$cnt = 1;
										while ($row = $res->fetch_object()) {
											?>

											<tr>
												<td colspan="4">
													<h4>Room Realted Info</h4>
												</td>
												<td><a href="javascript:void(0);"
														onClick="popUpWindow('http://localhost/bella/full-profile.php?id=<?php echo $row->emailid; ?>');"
														title="View Full Details">Print Data</a></td>

												<td>
													<?php
													// Calculate Monthly Fee
													$feespm = $row->feespm;
													$foodCost = ($row->foodstatus == 1) ? 2000 : 0;
													$waterCost = ($row->waterstatus == 1) ? 1000 : 0;
													$electricCost = ($row->electricstatus == 1) ? 1500 : 0;
													$internetCost = ($row->internetstatus == 1) ? 1000 : 0;

													$monthlyFee = $feespm + $foodCost + $waterCost + $electricCost + $internetCost;

													// Get current month and year
													$currentMonth = date("F Y"); // Example: "January 2025"
													$regno = $row->regno;

													// Fetch total amount paid for the current month
													$query = "SELECT SUM(amount_paid) AS totalPaid FROM monthly_payments WHERE regno = ? AND month = ?";
													$stmt = $mysqli->prepare($query);
													$stmt->bind_param("is", $regno, $currentMonth);
													$stmt->execute();
													$result = $stmt->get_result();
													$totalPaid = 0;

													if ($result->num_rows > 0) {
														$rowPayment = $result->fetch_assoc();
														$totalPaid = $rowPayment['totalPaid'];
													}

													// Check if the monthly fee is fully paid
													if ($totalPaid >= $monthlyFee) {
														echo '<span class="text-success"><b>Paid for this Month</b></span>';
													} else {
														?>
														<button class="btn btn-success pay-now-btn" data-bs-toggle="modal"
															data-bs-target="#paymentModal"
															data-regno="<?php echo $row->regno; ?>"
															data-name="<?php echo $row->firstName; ?>"
															data-roomno="<?php echo $row->roomno; ?>"
															data-baseamount="<?php echo $row->feespm; ?>"
															data-foodcost="<?php echo $row->foodstatus == 1 ? 2000 : 0; ?>"
															data-watercost="<?php echo $row->waterstatus == 1 ? 1000 : 0; ?>"
															data-electriccost="<?php echo $row->electricstatus == 1 ? 1500 : 0; ?>"
															data-internetcost="<?php echo $row->internetstatus == 1 ? 1000 : 0; ?>">
															Pay Now
														</button>

														<?php
													}
													?>


												</td>
												<td>



											</tr>

											<tr>
												<td colspan="6"><b>Reg no. :<?php echo $row->postingDate; ?></b></td>
											</tr>



											<tr>
												<td><b>Room no :</b></td>
												<td><?php echo $row->roomno; ?></td>
												<td><b>Bed :</b></td>
												<td><?php echo $row->seater; ?></td>
												<td><b>Fees ₱ :</b></td>
												<td><?php echo "₱ " . number_format($row->feespm, 2, '.', ','); ?></td>

											</tr>

											<tr>
												<td><b>Food Status:</b></td>
												<td>
													<?php if ($row->foodstatus == 0) {
														echo "Without Food";
													} else {
														echo "With Food (₱ 2,000.00)";
													}
													; ?>
												</td>

												<td><b>Stay From :</b></td>
												<td><?php echo $row->stayfrom; ?></td>
												<td><b>Duration:</b></td>
												<td><?php echo $dr = $row->duration; ?> Months</td>
											</tr>
											<tr>
												<td><b>Water Status:</b></td>
												<td>
													<?php if ($row->waterstatus == 0) {
														echo "Without Water";
													} else {
														echo "With Water (₱ 1,000.00)";
													}
													; ?>
												</td>

												<td><b>Electric Status:</b></td>
												<td>
													<?php if ($row->electricstatus == 0) {
														echo "Without Electric";
													} else {
														echo "With Electric (₱ 1,500.00)";
													}
													; ?>
												</td>
												<td><b>Internet Status:</b></td>
												<td>
													<?php if ($row->internetstatus == 0) {
														echo "Without Internet";
													} else {
														echo "With Internet (₱ 1,000.00)";
													}
													; ?>
												</td>
											</tr>
											<tr>
												<td colspan="6"><b>Monthly Fee :
														<?php
														$feespm = $row->feespm;
														// Define additional costs based on status
														$foodCost = ($row->foodstatus == 1) ? 2000 : 0;
														$waterCost = ($row->waterstatus == 1) ? 1000 : 0;
														$electricCost = ($row->electricstatus == 1) ? 1500 : 0;
														$internetCost = ($row->internetstatus == 1) ? 1000 : 0;

														// Calculate total monthly cost before any payments
														$monthlyFee = $feespm + $foodCost + $waterCost + $electricCost + $internetCost;

														// Get current month and year
														$currentMonth = date("F Y"); // Example: "January 2025"
														$regno = $row->regno;

														// Fetch the amount paid for the current month
														$query = "SELECT amount_paid FROM monthly_payments WHERE regno = ? AND month = ?";
														$stmt = $mysqli->prepare($query);
														$stmt->bind_param("is", $regno, $currentMonth);
														$stmt->execute();
														$result = $stmt->get_result();
														$amountPaid = 0;

														if ($result->num_rows > 0) {
															$rowPayment = $result->fetch_assoc();
															$amountPaid = $rowPayment['amount_paid'];
														}

														// Calculate balance
														$balance = $monthlyFee - $amountPaid;

														// Format the output
														echo "₱ " . number_format($monthlyFee, 2, '.', ',') . " - Balance: ₱ " . number_format($balance, 2, '.', ',');
														?>
													</b></td>
											</tr>
											<td colspan="6"><b>Total Fee :
													<?php
													$feespm = $row->feespm;
													// Define additional costs based on status
													$foodCost = ($row->foodstatus == 1) ? 2000 : 0;
													$waterCost = ($row->waterstatus == 1) ? 1000 : 0;
													$electricCost = ($row->electricstatus == 1) ? 1500 : 0;
													$internetCost = ($row->internetstatus == 1) ? 1000 : 0;

													// Calculate total sum before multiplying by duration
													$totalSum = $feespm + $foodCost + $waterCost + $electricCost + $internetCost;

													// Multiply by the number of months
													$totalFee = $dr * $totalSum;

													// Fetch total amount paid by the user
													$regno = $row->regno;
													$query = "SELECT SUM(amount_paid) AS totalPaid FROM monthly_payments WHERE regno = ?";
													$stmt = $mysqli->prepare($query);
													$stmt->bind_param("i", $regno);
													$stmt->execute();
													$result = $stmt->get_result();
													$totalPaid = 0;

													if ($result->num_rows > 0) {
														$rowPayment = $result->fetch_assoc();
														$totalPaid = $rowPayment['totalPaid'];
													}

													// Calculate the remaining balance after all payments
													$remainingBalance = $totalFee - $totalPaid;

													// Format the output
													echo "₱ " . number_format($totalFee, 2, '.', ',') . " - Paid: ₱ " . number_format($totalPaid, 2, '.', ',') . " - Balance: ₱ " . number_format($remainingBalance, 2, '.', ',');
													?>
												</b></td>
											</tr>

											</tr>

											<tr>
												<td colspan="6">
													<h4>Personal Info Info</h4>
												</td>
											</tr>

											<tr>
												<td><b>Reg No. :</b></td>
												<td><?php echo $row->regno; ?></td>
												<td><b>Full Name :</b></td>
												<td><?php echo $row->firstName; ?> 	<?php echo $row->middleName; ?>
													<?php echo $row->lastName; ?>
												</td>
												<td><b>Email :</b></td>
												<td><?php echo $row->emailid; ?></td>
											</tr>


											<tr>
												<td><b>Contact No. :</b></td>
												<td><?php echo $row->contactno; ?></td>
												<td><b>Gender :</b></td>
												<td><?php echo $row->gender; ?></td>
												<td>
													<!-- <b>Course :</b> -->
												</td>
												<td>
													<!-- <?php echo $row->course; ?> -->
												</td>
											</tr>


											<tr>
												<td><b>Emergency Contact No. :</b></td>
												<td><?php echo $row->egycontactno; ?></td>
												<td><b>Guardian Name :</b></td>
												<td><?php echo $row->guardianName; ?></td>
												<td><b>Guardian Relation :</b></td>
												<td><?php echo $row->guardianRelation; ?></td>
											</tr>

											<tr>
												<td><b>Guardian Contact No. :</b></td>
												<td colspan="6"><?php echo $row->guardianContactno; ?></td>
											</tr>

											<tr>
												<td colspan="6">
													<h4>Addresses</h4>
												</td>
											</tr>
											<tr>
												<td><b>Correspondense Address</b></td>
												<td colspan="2">
													<?php echo $row->corresAddress; ?><br />
													<?php echo $row->corresCIty; ?>,
													<?php echo $row->corresPincode; ?><br />
													<?php echo $row->corresState; ?>


												</td>
												<td><b>Permanent Address</b></td>
												<td colspan="2">
													<?php echo $row->pmntAddress; ?><br />
													<?php echo $row->pmntCity; ?>, <?php echo $row->pmntPincode; ?><br />
													<?php echo $row->pmnatetState; ?>

												</td>
											</tr>


											<?php
											$cnt = $cnt + 1;
										} ?>
									</tbody>
								</table>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			const payNowBtns = document.querySelectorAll(".pay-now-btn");

			payNowBtns.forEach(btn => {
				btn.addEventListener("click", function () {
					const regNo = btn.getAttribute("data-regno");
					const name = btn.getAttribute("data-name");
					const roomNo = btn.getAttribute("data-roomno");
					let baseAmount = parseFloat(btn.getAttribute("data-baseamount"));

					// Get service costs from data attributes
					const foodCost = btn.getAttribute("data-foodcost");
					const waterCost = btn.getAttribute("data-watercost");
					const electricCost = btn.getAttribute("data-electriccost");
					const internetCost = btn.getAttribute("data-internetcost");

					let servicesHTML = `<b>Base Amount:</b> ₱${baseAmount.toFixed(2)} <br>`;

					if (foodCost > 0) servicesHTML += `<label><input type="checkbox" class="service-checkbox" data-price="2000"> Food (₱2,000)</label><br>`;
					if (waterCost > 0) servicesHTML += `<label><input type="checkbox" class="service-checkbox" data-price="1000"> Water (₱1,000)</label><br>`;
					if (electricCost > 0) servicesHTML += `<label><input type="checkbox" class="service-checkbox" data-price="1500"> Electricity (₱1,500)</label><br>`;
					if (internetCost > 0) servicesHTML += `<label><input type="checkbox" class="service-checkbox" data-price="1000"> Internet (₱1,000)</label><br>`;

					servicesHTML += `<b>Total:</b> <span id="totalAmount">₱${baseAmount.toFixed(2)}</span>`;

					Swal.fire({
						title: `Pay your monthly bills ${name}?`,
						html: `<div style="text-align:left;">${servicesHTML}</div>`,
						showCancelButton: true,
						confirmButtonText: "Proceed to Pay",
						didOpen: () => {
							const totalAmountField = document.getElementById("totalAmount");
							const serviceCheckboxes = document.querySelectorAll(".service-checkbox");

							serviceCheckboxes.forEach(checkbox => {
								checkbox.addEventListener("change", function () {
									let total = baseAmount;
									serviceCheckboxes.forEach(cb => {
										if (cb.checked) {
											total += parseFloat(cb.getAttribute("data-price"));
										}
									});
									totalAmountField.textContent = `₱${total.toFixed(2)}`;
								});
							});
						}
					}).then((result) => {
						if (result.isConfirmed) {
							const amount = parseFloat(document.getElementById("totalAmount").textContent.replace("₱", ""));
							choosePaymentMethod(regNo, name, roomNo, amount);
						}
					});
				});
			});
		});


		function choosePaymentMethod(regNo, name, roomNo, amount) {
			Swal.fire({
				title: "Choose Payment Method",
				showDenyButton: true,
				showCancelButton: true,
				confirmButtonText: "PayPal",
				denyButtonText: "Cash",
			}).then((result) => {
				if (result.isConfirmed) {
					redirectToPayPal(regNo, name, roomNo, amount);
				} else if (result.isDenied) {
					processCashPayment(regNo, roomNo, amount);
				}
			});
		}

		function redirectToPayPal(regNo, name, roomNo, amount) {
			var successUrl = "http://localhost/bella/paypal-success.php?regno=" + encodeURIComponent(regNo) +
				"&roomno=" + encodeURIComponent(roomNo) + "&amount=" + encodeURIComponent(amount);
			var cancelUrl = "http://localhost/bella/room-details.php?payment=failed";

			window.location.href = `https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sb-daa2k32419423@business.example.com&item_name=${encodeURIComponent(name)}&amount=${amount}&currency_code=PHP&return=${encodeURIComponent(successUrl)}&cancel_return=${encodeURIComponent(cancelUrl)}`;
		}


		function processCashPayment(regNo, roomNo, amount) {
			fetch("process-payment.php", {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},
				body: `regno=${regNo}&roomno=${roomNo}&amount_paid=${amount}&payment_method=Cash`
			})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						Swal.fire("Payment Successful", "Your payment has been recorded.", "success").then(() => {
							location.reload();
						});
					} else {
						Swal.fire("Error", "Something went wrong. Try again.", "error");
					}
				})
				.catch(error => {
					console.error("Error:", error);
					Swal.fire("Error", "Unable to process payment.", "error");
				});
		}

		document.addEventListener("DOMContentLoaded", function () {
			const urlParams = new URLSearchParams(window.location.search);

			// Check if payment was successful
			if (urlParams.get("payment") === "success") {
				Swal.fire({
					title: "Payment Successful!",
					text: "Your payment has been recorded successfully.",
					icon: "success",
					confirmButtonText: "OK"
				}).then(() => {
					window.location.href = "room-details.php"; // Redirect after user clicks OK
				});
			}

			// Check if payment failed
			if (urlParams.get("payment") === "failed") {
				Swal.fire({
					title: "Payment Failed!",
					text: "There was an issue processing your payment. Please try again.",
					icon: "error",
					confirmButtonText: "OK"
				});
			}
		});
	</script>



	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<!-- <script src="js/bootstrap.min.js"></script> -->
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>

</body>

</html>