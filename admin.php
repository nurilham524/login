<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// connect database
include 'db.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// get data
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - User Data</title>
    <link rel="stylesheet" href="./styles/admin.css">
</head>
<body>
<div class="logo" style="position: absolute; top: 20px; right: 20px; font-size: 24px; font-weight: bold; color: #000000;">
    <h1>NemoSal</h1>
</div>
    <div class="container">
        <h2>User Data</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Alamat</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="admin_topup_requests.php" class="topup-btn">View Top-up Requests</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
