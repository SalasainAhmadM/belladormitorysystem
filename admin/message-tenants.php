<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

$userid = $_SESSION['id'];

if (isset($_POST['submit'])) {

    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];
    $sender_type = 'admin';

    $query = "INSERT INTO messages (sender_id, receiver_id, message, sender_type, is_read) VALUES (?, ?, ?, ?, 0)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iiss', $userid, $receiver_id, $message, $sender_type);
    $stmt->execute();
}

$users = $mysqli->query("SELECT id, firstName, middleName, lastName FROM userregistration");
$messages = [];
if (isset($_GET['chat_with'])) {
    $chat_with = $_GET['chat_with'];
    $messages_query = "SELECT * FROM messages WHERE (sender_id = $userid AND receiver_id = $chat_with) OR (sender_id = $chat_with AND receiver_id = $userid) ORDER BY timestamp ASC";
    $messages = $mysqli->query($messages_query);
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Message Tenants</title>
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
    <style>
        .chat-container {
            display: flex;
            margin-top: 30px;
        }

        .users-list {
            width: 30%;
            border-right: 1px solid #ddd;
            padding: 15px;
        }

        .chat-box {
            width: 70%;
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
            margin: 5px;
            border-radius: 10px;
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
        }

        .user-item:hover,
        .user-item.active {
            background: #007bff;
            color: white;
        }

        .user-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="chat-container">
                <div class="users-list">
                    <h4>Tenants</h4>
                    <?php while ($row = $users->fetch_assoc()) { ?>
                        <div class="user-item <?php echo (isset($_GET['chat_with']) && $_GET['chat_with'] == $row['id']) ? 'active' : ''; ?>"
                            onclick="window.location='?chat_with=<?php echo $row['id']; ?>'">
                            <img src="img/ts-avatar.png" alt="Profile Picture">
                            <span><?php echo $row['firstName'] . " " . $row['middleName'] . " " . $row['lastName']; ?></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="chat-box">
                    <?php if (isset($_GET['chat_with'])) { ?>
                        <h4>Chat with Tenant</h4>
                        <div class="chat-messages">
                            <?php while ($msg = $messages->fetch_assoc()) { ?>
                                <div class="<?php echo ($msg['sender_id'] == $userid) ? 'message-right' : 'message-left'; ?>">
                                    <?php echo $msg['message']; ?>
                                    <span class="text-muted small"> <?php echo $msg['timestamp']; ?> </span>
                                </div>
                            <?php } ?>
                        </div>
                        <form method="post">
                            <input type="hidden" name="receiver_id" value="<?php echo $_GET['chat_with']; ?>">
                            <textarea name="message" class="form-control" required></textarea>
                            <button type="submit" name="submit" class="btn btn-primary mt-2">Send</button>
                        </form>
                    <?php } else { ?>
                        <p>Select a tenant to start chatting.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>