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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #2b5876, #4e4376);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .dashboard-container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            padding: 40px 30px;
            width: 400px;
            text-align: center;
            backdrop-filter: blur(15px);
            transition: all 0.3s ease;
        }

        .dashboard-container h2 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #fff;
            text-transform: uppercase;
        }

        .dashboard-container .profile-info {
            margin-bottom: 20px;
            font-size: 18px;
            color: #ddd;
        }

        .dashboard-container .profile-info p {
            margin: 8px 0;
        }

        .dashboard-container .profile-info label {
            font-weight: bold;
            color: #b0c4de;
        }

        .dashboard-container button {
            width: 100%;
            padding: 15px;
            background-color: #2575fc;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .dashboard-container button:hover {
            background-color: #6a11cb;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            font-size: 18px;
            text-decoration: none;
            color: #f0f4f8;
        }

        .logout:hover {
            color: #2575fc;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo ($user['username']); ?></h2>
        <div class="profile-info">
            <p><label>Name:</label> <?php echo ($user['name']); ?></p>
            <p><label>Email:</label> <?php echo ($user['email']); ?></p>
            <p><label>Address:</label> <?php echo ($user['address']); ?></p>
            <p><label>Role:</label> <?php echo ($user['role']); ?></p>
        </div>
        <button onclick="window.location.href='edit_profile.php'">Edit Profile</button>
        <button onclick="window.location.href='./chat/chat_list.php'">Chat</button>
        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>
