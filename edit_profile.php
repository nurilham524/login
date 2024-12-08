<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $update_sql = "UPDATE users SET name=?, email=?, address=? WHERE username=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssss", $name, $email, $address, $username);
    $update_stmt->execute();

    header("Location: user_dashboard.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        /* belum dibuat */
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Edit Profile</h2>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo ($user['name']); ?>" required><br>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo ($user['email']); ?>" required><br>
            <label>Address:</label>
            <textarea name="address" required><?php echo ($user['address']); ?></textarea><br>
            <button onclick="window.location.href='dashboard.php'">Save Changes</button>
        </form>
    </div>
</body>
</html>
