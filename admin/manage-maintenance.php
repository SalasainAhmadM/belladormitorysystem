<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Delete maintenance request
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $query = "DELETE FROM maintenance WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Maintenance Request Deleted');</script>";
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Manage Maintenance Requests</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top: 4%">Manage Maintenance Requests</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Maintenance Requests</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>Name</th>
                                            <th>Requested By</th>
                                            <th>Requested Time</th>
                                            <th>Room No.</th>
                                            <th>Fee (Pesos)</th>
                                            <th>Status</th>
                                            <th>Payment Status</th>
                                            <th>Issue of Tenant</th>
                                            <th>Profession</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>Name</th>
                                            <th>Requested By</th>
                                            <th>Requested Time</th>
                                            <th>Room No.</th>
                                            <th>Fee (Pesos)</th>
                                            <th>Status</th>
                                            <th>Payment Status</th>
                                            <th>Issue of Tenant</th>
                                            <th>Profession</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT m.id, m.name, 
                   COALESCE(CONCAT(r.firstName, ' ', 
                                   COALESCE(r.middleName, ''), ' ', 
                                   r.lastName), 'N/A') AS requested_by_name, 
                   m.requested_time, m.room_no, 
                   m.fee, m.status, m.profession, 
                   m.description, m.issue, 
                   m.payment, m.payment_method 
            FROM maintenance m
            LEFT JOIN userregistration r ON m.requested_by = r.id
            ORDER BY m.requested_time DESC";

                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo htmlspecialchars($row->name); ?></td>
                                                <td><?php echo htmlspecialchars(trim($row->requested_by_name)); ?></td>
                                                <td><?php echo ($row->requested_time == "0000-00-00 00:00:00") ? "N/A" : $row->requested_time; ?>
                                                </td>
                                                <td><?php echo $row->room_no ?: 'N/A'; ?></td>
                                                <td><?php echo $row->fee; ?></td>
                                                <td><?php echo ucfirst($row->status) ?: 'N/A'; ?></td>
                                                <td>
                                                    <?php echo ucfirst($row->payment_method) ?: 'N/A'; ?> -
                                                    <?php echo ucfirst($row->payment) ?: 'N/A'; ?>
                                                    <?php echo ($row->fee == $row->payment) ? ' (Paid)' : ' (Pending)'; ?>
                                                </td>
                                                <td><?php echo ucfirst($row->issue) ?: 'N/A'; ?></td>
                                                <td><?php echo $row->profession; ?></td>
                                                <td><?php echo $row->description; ?></td>
                                                <td>
                                                    <a href="edit-maintenance.php?id=<?php echo $row->id; ?>"><i
                                                            class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                                    <a href="manage-maintenance.php?del=<?php echo $row->id; ?>"
                                                        onclick="return confirm('Do you want to delete?');">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                    </a>&nbsp;&nbsp;
                                                    <a href="done-maintenance.php?id=<?php echo $row->id; ?>"
                                                        onclick="return confirm('Mark as Done?');">
                                                        <i class="fa fa-check-circle" style="color: green;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
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