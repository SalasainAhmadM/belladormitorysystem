<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Update maintenance record: clear values and set status to 'available'
    $query = "UPDATE maintenance SET 
                requested_by = NULL, 
                requested_time = NULL, 
                room_no = NULL, 
                issue = NULL, 
                payment = NULL, 
                payment_method = NULL, 
                status = 'available'
              WHERE id = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Maintenance marked as Done');</script>";
    echo "<script>window.location.href='manage-maintenance.php';</script>";
    exit();
}
?>