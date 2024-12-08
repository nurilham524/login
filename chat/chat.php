<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../db.php';

// Cek login user
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil username saat ini
$current_user = $_SESSION['username'];

// Cek apakah user tujuan dipilih
$chat_user = $_GET['user'] ?? null;

if (!$chat_user) {
    echo "User tidak ditemukan.";
    exit();
}

// Ambil pesan antara user yang login dan user tujuan
$sql = "SELECT sender_username, receiver_username, message, timestamp 
        FROM messages 
        WHERE 
            (sender_username = ? AND receiver_username = ?) OR 
            (sender_username = ? AND receiver_username = ?)
        ORDER BY timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $current_user, $chat_user, $chat_user, $current_user);
$stmt->execute();
$messages = $stmt->get_result();

// Proses form untuk mengirim pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver = $_POST['receiver'];
    $message = $_POST['message'];

    if (!empty($receiver) && !empty($message)) {
        $sql_insert = "INSERT INTO messages (sender_username, receiver_username, message) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sss", $current_user, $receiver, $message);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Pesan berhasil dikirim!');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengirim pesan.');</script>";
        }
    } else {
        echo "<script>alert('Pesan atau penerima tidak boleh kosong.');</script>";
    }
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
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .chat-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 10px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .chat-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .chat-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            height: 400px;
            overflow-y: scroll;
            background-color: #f9f9f9;
        }
        .message {
            display: flex;
            margin: 5px 0;
            padding: 10px;
            border-radius: 15px;
            max-width: 70%;
        }
        .message.left {
            justify-content: flex-start;
            background-color: #e0e0e0;
            align-self: flex-start;
        }
        .message.right {
            justify-content: flex-end;
            background-color: #007bff;
            color: #fff;
            align-self: flex-end;
        }
        .chat-form {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        textarea {
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2>Chat dengan <?php echo $chat_user; ?></h2>
        </div>
        <div class="chat-box">
            <?php while ($msg = $messages->fetch_assoc()): ?>
                <div class="message <?php echo $msg['sender_username'] === $current_user ? 'right' : 'left'; ?>">
                    <p><?php echo $msg['message']; ?></p>
                </div>
            <?php endwhile; ?>
        </div>
        <form method="POST" class="chat-form">
            <input type="hidden" name="receiver" value="<?php echo $chat_user; ?>">
            <textarea name="message" rows="2" placeholder="Ketik pesan..." required></textarea>
            <button type="submit">Kirim</button>
        </form>
    </div>
</body>
</html>
