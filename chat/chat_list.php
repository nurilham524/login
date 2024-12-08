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

// get data from database
$current_user = $_SESSION['username'];
$sql = "SELECT username FROM users WHERE username != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_user);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat with Users</title>
    <style>
        /* css belum dibuat */
    </style>
</head>
<body>
    <h2>Chat with Other Users</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li><a href="chat.php?user=<?php echo $row['username']; ?>"><?php echo $row['username']; ?></a></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
