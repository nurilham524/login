<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

if ($_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("Session user_id tidak ditemukan. Pastikan pengguna sudah login dengan benar.");
}

$current_user_id = $_SESSION['user_id'];

// Ambil saldo terkini dari user
$sql_balance = "SELECT balance FROM user_balance WHERE user_id = ?";
$stmt_balance = $conn->prepare($sql_balance);
$stmt_balance->bind_param("i", $current_user_id);
$stmt_balance->execute();
$result_balance = $stmt_balance->get_result();
$current_balance = $result_balance->fetch_assoc()['balance'] ?? 0;

// Ambil daftar user kecuali user yang sedang login
$sql_users = "SELECT id, nama FROM users WHERE id != ?";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->bind_param("i", $current_user_id);
$stmt_users->execute();
$result_users = $stmt_users->get_result();

if ($result_users->num_rows === 0) {
    die("Tidak ada user lain yang tersedia untuk transfer.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_user_id = $_POST['target_user'];
    $amount = $_POST['amount'];

    if (empty($target_user_id) || empty($amount) || $amount <= 0) {
        $error = "Masukkan user tujuan dan jumlah yang valid.";
    } elseif ($amount > $current_balance) {
        $error = "Saldo Anda tidak mencukupi untuk melakukan transfer.";
    } else {
        $conn->begin_transaction();
        try {
            // Kurangi saldo pengirim
            $sql_deduct = "UPDATE user_balance SET balance = balance - ? WHERE user_id = ?";
            $stmt_deduct = $conn->prepare($sql_deduct);
            $stmt_deduct->bind_param("di", $amount, $current_user_id);
            $stmt_deduct->execute();

            // Tambahkan saldo ke penerima
            $sql_add = "UPDATE user_balance SET balance = balance + ? WHERE user_id = ?";
            $stmt_add = $conn->prepare($sql_add);
            $stmt_add->bind_param("di", $amount, $target_user_id);
            $stmt_add->execute();

            $conn->commit();
            $success = "Saldo berhasil ditransfer.";
            // Perbarui saldo terkini setelah transfer
            $current_balance -= $amount;
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Terjadi kesalahan saat melakukan transfer.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Saldo</title>
    <link rel="stylesheet" href="./styles/transfer.css">
</head>
<body>
    <form method="POST" action="">
        <h2>Transfer Saldo</h2>
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green; text-align: center;"><?php echo $success; ?></p>
        <?php endif; ?>

        <p>Saldo Anda saat ini: <strong><?php echo number_format($current_balance, 2); ?></strong></p>

        <label for="target_user">Pilih User Tujuan:</label>
        <select name="target_user" id="target_user" required>
            <option value="">-- Pilih User --</option>
            <?php while ($user = $result_users->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($user['id']); ?>">
                    <?php echo htmlspecialchars($user['nama']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="amount">Jumlah Transfer:</label>
        <input type="number" name="amount" id="amount" required min="1">

        <button type="submit">Transfer</button>
        <br><br>
        <a href="dashboard.php">Kembali</a>
    </form>
</body>
</html>
