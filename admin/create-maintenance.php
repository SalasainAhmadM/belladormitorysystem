<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if ($_POST['submit']) {
    $name = $_POST['name'];
    // $requested_by = $_POST['requested_by'];
    // $room_no = $_POST['room_no'];
    $fee = $_POST['fee'];
    $status = 'available';
    $profession = $_POST['profession'];
    $description = $_POST['description'];

    // Insert maintenance request
    $query = "INSERT INTO maintenance (name, fee, status, profession, description) 
	          VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssss', $name, $fee, $status, $profession, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Maintenance has been added successfully');</script>";
    } else {
        echo "<script>alert('Error adding maintenance request');</script>";
    }
    $stmt->close();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Create Maintenance Request</title>
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
                        <h2 class="page-title">Add Maintenance Request</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Create Maintenance Request</div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Maintenance Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label class="col-sm-2 control-label">Requested By</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="requested_by">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Room No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="room_no">
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Fee</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="fee" required>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label class="col-sm-2 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <Select name="status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="available">Available</option>
                                                <option value="working">Working</option>
                                            </Select>
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Profession</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="profession" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="description" required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-8 col-sm-offset-2">
                                        <input class="btn btn-primary" type="submit" name="submit"
                                            value="Create Maintenance">
                                    </div>

                                </form>
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