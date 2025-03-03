<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_GET['del'])) {
	$id = intval($_GET['del']);

	// First, delete from monthly_payments where regno matches
	$adn1 = "DELETE FROM monthly_payments WHERE regno = (SELECT regno FROM registration WHERE id = ?)";
	$stmt1 = $mysqli->prepare($adn1);
	$stmt1->bind_param('i', $id);
	$stmt1->execute();
	$stmt1->close();

	// Then, delete from registration
	$adn2 = "DELETE FROM registration WHERE id = ?";
	$stmt2 = $mysqli->prepare($adn2);
	$stmt2->bind_param('i', $id);
	$stmt2->execute();
	$stmt2->close();

	echo "<script>alert('Data Deleted');</script>";
}
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
	<title>Manage Rooms</title>
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
						<h2 class="page-title" style="margin-top:4%">Manage Registred Tenants</h2>
						<div class="panel panel-default">
							<div class="panel-heading">All Room Details</div>
							<div class="panel-body">
								<table id="zctb" class="display table table-striped table-bordered table-hover"
									cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Sno.</th>
											<th>Student Name</th>
											<th>Reg no</th>
											<th>Contact no </th>
											<th>Room no </th>
											<th>Bed </th>
											<th>Staying From </th>
											<th>Monthly Fees</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Sno.</th>
											<th>Student Name</th>
											<th>Reg no</th>
											<th>Contact no </th>
											<th>Room no </th>
											<th>Bed </th>
											<th>Staying From </th>
											<th>Monthly Fees</th>
											<th>Action</th>
										</tr>
									</tfoot>
									<tbody>
										<?php
										date_default_timezone_set('Asia/Manila');
										$aid = $_SESSION['id'];
										$current_month = date('F Y');
										// Query to fetch student details along with payment status
										$ret = "SELECT r.*, 
               COALESCE(mp.amount_paid, 0) AS amount_paid, 
               COALESCE(mp.payment_method, 'Not Paid') AS payment_method 
        FROM registration r 
        LEFT JOIN monthly_payments mp 
        ON r.regno = mp.regno 
        AND mp.month = ?";

										$stmt = $mysqli->prepare($ret);
										$stmt->bind_param('s', $current_month);
										$stmt->execute();
										$res = $stmt->get_result();
										$cnt = 1;

										while ($row = $res->fetch_object()) {
											?>
											<tr>
												<td><?php echo $cnt; ?></td>
												<td><?php echo $row->firstName . " " . $row->middleName . " " . $row->lastName; ?>
												</td>
												<td><?php echo $row->regno; ?></td>
												<td><?php echo $row->contactno; ?></td>
												<td><?php echo $row->roomno; ?></td>
												<td><?php echo $row->seater; ?></td>
												<td><?php echo date("F d, Y", strtotime($row->stayfrom)); ?></td>
												<!-- Format stay date -->
												<td>
													<?php if ($row->amount_paid > 0) { ?>
														<span style="color: green;">Paid
															(<?php echo $row->payment_method; ?>)</span>
													<?php } else { ?>
														<span style="color: red;">Not Paid</span>
													<?php } ?>
												</td>
												<td>
													<a href="javascript:void(0);"
														onClick="popUpWindow('full-profile.php?id=<?php echo $row->id; ?>');"
														title="View Full Details">
														<i class="fa fa-desktop"></i>
													</a>
													&nbsp;&nbsp;
													<a href="manage-students.php?del=<?php echo $row->id; ?>"
														title="Delete Record"
														onclick="return confirm('Do you want to delete this record?');">
														<i class="fa fa-close"></i>
													</a>
												</td>
											</tr>
											<?php
											$cnt++;
										}
										?>

									</tbody>
								</table>


							</div>
						</div>


					</div>
				</div>



			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>

</body>

</html>