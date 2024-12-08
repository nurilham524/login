<?php
session_start();
include 'db.php'; 

// Cek user login
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // cek username and passsword
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: dashboard.php");
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            padding: 40px 30px;
            width: 350px;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .login-container h2 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #fff;
            text-transform: uppercase;
        }

        .login-container label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            color: #ddd;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            background-color: rgba(255, 255, 255, 0.4);
            outline: none;
            box-shadow: 0 0 8px rgba(38, 170, 255, 0.6);
        }

        .login-container button {
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

        .login-container button:hover {
            background-color: #6a11cb;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .error {
            color: #ff6666;
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
