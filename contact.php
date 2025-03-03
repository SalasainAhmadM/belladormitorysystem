<?php
session_start();
include('includes/config.php');

// Fetch the number of occupied rooms
$query = "SELECT COUNT(DISTINCT roomno) AS occupied_rooms FROM registration WHERE roomno IS NOT NULL";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$occupiedRooms = $row['occupied_rooms'];
$totalRooms = 15;
$availableRooms = $totalRooms - $occupiedRooms;


if (isset($_POST['contact'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $mysqli->prepare("INSERT INTO contactus(name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $email, $subject, $message);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        echo "<script>alert('Your message has been sent successfully.');</script>";
    } else {
        echo "<script>alert('There was an error. Please try again later.');</script>";
    }
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
    <title>Contact Us</title>
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

                        <h2 class="page-title">Contact Us</h2>
                        <h4><?php echo "We have $availableRooms rooms available"; ?></h4>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="well row pt-2x pb-3x bk-light">
                                    <div class="col-md-8 col-md-offset-2">
                                        <form action="" class="mt" method="post">
                                            <label for="" class="text-uppercase text-sm">Your Name</label>
                                            <input type="text" placeholder="Name" name="name" class="form-control mb"
                                                required>
                                            <label for="" class="text-uppercase text-sm">Your Email</label>
                                            <input type="email" placeholder="Email" name="email" class="form-control mb"
                                                required>
                                            <label for="" class="text-uppercase text-sm">Subject</label>
                                            <input type="text" placeholder="Subject" name="subject"
                                                class="form-control mb" required>
                                            <label for="" class="text-uppercase text-sm">Message</label>
                                            <textarea placeholder="Message" name="message" class="form-control mb"
                                                rows="5" required></textarea>
                                            <input type="submit" name="contact" class="btn btn-primary btn-block"
                                                value="Send Message">
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
ss