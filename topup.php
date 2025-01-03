<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

if ($_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT id, nama, email FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Ambil saldo user
$sql_balance = "SELECT balance FROM user_balance WHERE user_id=?";
$stmt_balance = $conn->prepare($sql_balance);
$stmt_balance->bind_param("i", $user['id']);
$stmt_balance->execute();
$balance_result = $stmt_balance->get_result();
$user_balance = $balance_result->fetch_assoc()['balance'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $uploadDir = "uploads/";
    $uploadFile = $uploadDir . basename($_FILES['proof']['name']);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    // Validasi jumlah nominal
    if (empty($amount) || $amount <= 0) {
        $error = "Nominal top-up tidak valid.";
    }
    // Validasi file upload
    elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $error = "Hanya file gambar dengan format JPG, JPEG, PNG, atau GIF yang diperbolehkan.";
    } 
    elseif ($_FILES['proof']['size'] > 5000000) { // Batas ukuran 5MB
        $error = "Ukuran file terlalu besar. Maksimal 5MB.";
    }
    // Proses upload file
    elseif (move_uploaded_file($_FILES['proof']['tmp_name'], $uploadFile)) {
        $sql = "INSERT INTO topup_requests (user_id, amount, images) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ids", $user['id'], $amount, $uploadFile);
        if ($stmt->execute()) {
            $success = "Permintaan top-up berhasil dikirim. Menunggu konfirmasi admin.";
        } else {
            $error = "Terjadi kesalahan, coba lagi.";
        }
    } else {
        $error = "Gagal mengunggah bukti transfer.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up</title>
    <script>
        // Function to show a popup notification and redirect to the dashboard page
        function showPopupAndRedirect(message, redirectUrl) {
            alert(message);
            window.location.href = redirectUrl;
        }
    </script>
    <link rel="stylesheet" href="./styles/topup.css">
</head>
<body>
<div class="logo" style="position: absolute; top: 20px; right: 20px; font-size: 24px; font-weight: bold; color: #000000;">
    <h1>NemoSal</h1>
</div>
    <h2>Top Up Saldo</h2>
    <p>Saldo Anda saat ini: <strong><?php echo number_format($user_balance, 2); ?></strong></p>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <script>
            // Show the success message in a popup and redirect after the popup closes
            showPopupAndRedirect("<?php echo $success; ?>", "dashboard.php");
        </script>
    <?php endif; ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="amount">Nominal Top-Up:</label>
        <input type="number" name="amount" id="amount" required>
        <br>
        <label for="proof">Unggah Bukti Transfer:</label>
        <input type="file" name="proof" id="proof" required>
        <br>
        <button type="submit">Kirim Permintaan</button>
    </form>
    <a href="dashboard.php">Kembali</a>
</body>
</html>
