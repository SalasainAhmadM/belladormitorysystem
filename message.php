<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

$userid = $_SESSION['id'];
$admin_id = 1; // Assuming admin has ID 1

if (isset($_POST['submit'])) {
    $message = $_POST['message'];
    $sender_type = 'user';

    $query = "INSERT INTO messages (sender_id, receiver_id, message, sender_type, is_read) VALUES (?, ?, ?, ?, 0)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iiss', $userid, $admin_id, $message, $sender_type);
    $stmt->execute();
}

$messages_query = "SELECT * FROM messages WHERE (sender_id = $userid AND receiver_id = $admin_id) OR (sender_id = $admin_id AND receiver_id = $userid) ORDER BY timestamp ASC";
$messages = $mysqli->query($messages_query);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message Admin</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
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
    <script type="text/javascript"></script>
    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            margin-top: 30px;
        }

        .chat-box {
            width: 60%;
            padding: 15px;
        }

        .message-left {
            text-align: left;
            background: #f1f1f1;
            padding: 10px;
            border-radius: 10px;
            margin: 5px;
        }

        .message-right {
            text-align: right;
            background: #2c3136;
            color: white;
            padding: 10px;
            border-radius: 10px;
            margin: 5px;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="chat-container">
                <h4>Chat with Admin</h4>
                <div class="chat-box">
                    <div class="chat-messages">
                        <?php while ($msg = $messages->fetch_assoc()) { ?>
                            <div class="<?php echo ($msg['sender_id'] == $userid) ? 'message-right' : 'message-left'; ?>">
                                <?php echo $msg['message']; ?>
                                <span class="text-muted small"> <?php echo $msg['timestamp']; ?> </span>
                            </div>
                        <?php } ?>
                    </div>
                    <form method="post">
                        <textarea name="message" class="form-control" required></textarea>
                        <button type="submit" name="submit" class="btn btn-primary mt-2">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>