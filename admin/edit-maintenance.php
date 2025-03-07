<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Code to update maintenance details
if ($_POST['submit']) {
	$name = $_POST['name'];
	$fee = $_POST['fee'];
	$profession = $_POST['profession'];
	$description = $_POST['description'];
	$id = $_GET['id'];

	$query = "UPDATE maintenance SET name=?, fee=?, profession=?, description=? WHERE id=?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ssssi', $name, $fee, $profession, $description, $id);
	$stmt->execute();

	echo "<script>alert('Maintenance Request has been updated successfully');</script>";
}
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title>Edit Maintenance Request</title>
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
</head>

<body>
	<?php include('includes/header.php'); ?>
	<div class="ts-main-content">
		<?php include('includes/sidebar.php'); ?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h2 class="page-title">Edit Maintenance Request</h2>

						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Edit Maintenance Details</div>
									<div class="panel-body">
										<form method="post" class="form-horizontal">
											<?php
											$id = $_GET['id'];
											$ret = "SELECT * FROM maintenance WHERE id=?";
											$stmt = $mysqli->prepare($ret);
											$stmt->bind_param('i', $id);
											$stmt->execute();
											$res = $stmt->get_result();

											while ($row = $res->fetch_object()) {
												?>
												<div class="hr-dashed"></div>
												<div class="form-group">
													<label class="col-sm-2 control-label">Name</label>
													<div class="col-sm-8">
														<input type="text" name="name" value="<?php echo $row->name; ?>"
															class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-2 control-label">Fee (Pesos)</label>
													<div class="col-sm-8">
														<input type="text" name="fee" value="<?php echo $row->fee; ?>"
															class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-2 control-label">Profession</label>
													<div class="col-sm-8">
														<input type="text" name="profession"
															value="<?php echo $row->profession; ?>" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-2 control-label">Description</label>
													<div class="col-sm-8">
														<textarea class="form-control"
															name="description"><?php echo $row->description; ?></textarea>
													</div>
												</div>

											<?php } ?>
											<div class="col-sm-8 col-sm-offset-2">
												<input class="btn btn-primary" type="submit" name="submit"
													value="Update Maintenance Request">
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

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