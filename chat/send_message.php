<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$sender = $_SESSION['username'];
$receiver = isset($_GET['user']) ? $_GET['user'] : '';

// Ambil pesan antara pengguna saat ini dan penerima
$sql = "SELECT sender_username, receiver_username, message, timestamp FROM messages 
        WHERE (sender_username = ? AND receiver_username = ?) 
           OR (sender_username = ? AND receiver_username = ?)
        ORDER BY timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $sender, $receiver, $receiver, $sender);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .chat-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .messages {
            border: 1px solid #ddd;
            padding: 10px;
            height: 300px;
            overflow-y: scroll;
            margin-bottom: 10px;
        }
        .message {
            margin: 5px 0;
        }
        .sender {
            font-weight: bold;
            color: #007bff;
        }
        .receiver {
            font-weight: bold;
            color: #28a745;
        }
        .timestamp {
            font-size: 0.8em;
            color: #aaa;
        }
        form {
            display: flex;
            gap: 5px;
        }
        textarea {
            flex: 1;
            padding: 10px;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h2>Chat dengan <?php echo ($receiver); ?></h2>
        <div class="messages">
            <?php foreach ($messages as $msg): ?>
                <div class="message">
                    <span class="<?php echo $msg['sender_username'] === $sender ? 'sender' : 'receiver'; ?>">
                        <?php echo $msg['sender_username']; ?>:
                    </span>
                    <?php echo ($msg['message']); ?>
                    <div class="timestamp"><?php echo $msg['timestamp']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <form action="chat.php" method="POST">
            <input type="hidden" name="receiver" value="<?php echo ($receiver); ?>">
            <textarea name="message" rows="2" placeholder="Ketik pesan..." required></textarea>
            <button type="submit">Kirim</button>
        </form>
    </div>
</body>
</html>
