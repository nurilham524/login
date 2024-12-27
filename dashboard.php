<?php
session_start();
include 'db.php'; 

// check login user
if ($_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// get data user from database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// make sure login user
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

// get user balance from database
$balance_sql = "SELECT balance FROM user_balance WHERE user_id = ?";
$balance_stmt = $conn->prepare($balance_sql);
$balance_stmt->bind_param("i", $user['id']);
$balance_stmt->execute();
$balance_result = $balance_stmt->get_result();

if ($balance_result->num_rows > 0) {
    $balance_row = $balance_result->fetch_assoc();
    $user_balance = $balance_row['balance'];
} else {
    $user_balance = 0.00; // Default balance if not found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="./styles/dashboard.css">
</head>
<body>
<div class="logo" style="position: absolute; top: 20px; right: 20px; font-size: 24px; font-weight: bold; color: #000000;">
    <h1>NemoSal</h1>
</div>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?></h2>
        <div class="profile-info">
            <p><label>Name:</label> <?php echo htmlspecialchars($user['nama']); ?></p>
            <p><label>Email:</label> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><label>Address:</label> <?php echo htmlspecialchars($user['alamat']); ?></p>
            <p><label>Role:</label> <?php echo htmlspecialchars($user['role']); ?></p>
            <p><label>Balance:</label> Rp <?php echo number_format($user_balance, 2, ',', '.'); ?></p>
        </div>
        <button onclick="window.location.href='edit_profile.php'">Edit Profile</button>
        <button onclick="window.location.href='./chat/chat_list.php'">Chat</button>
        <button onclick="window.location.href='topup.php'">Top Up</button>
        <button onclick="window.location.href='transfer.php'">Transfer Saldo</button>
        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>
