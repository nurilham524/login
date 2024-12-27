<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi form
    if (empty($nama) || empty($username) || empty($email) || empty($alamat) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan Konfirmasi Password tidak cocok.";
    } else {
        // Cek username agar tidak ada user yang tabrakan
        $check_sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username sudah digunakan.";
        } else {
            // Tambahkan user baru ke database
            $sql = "INSERT INTO users (nama, username, email, alamat, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nama, $username, $email, $alamat, $password);

            if ($stmt->execute()) {
                // kembali ke login
                header("Location: login.php?success=1");
                exit();
            } else {
                $error = "Terjadi kesalahan, coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./styles/registrasi.css">
</head>
<body>
<div class="logo" style="position: absolute; top: 20px; right: 20px; font-size: 24px; font-weight: bold; color: #000000;">
    <h1>NemoSal</h1>
</div>
    <div class="container">
        <h2>Register</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="alamat" placeholder="Alamat" rows="3" required></textarea>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
