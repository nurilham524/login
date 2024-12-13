<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action']; // 'approve' or 'reject'

    $conn->begin_transaction();
    try {
        if ($action === 'approve') {
            // Update status top-up dan tambahkan saldo user
            $sql = "UPDATE topup_requests SET status = 'approved', approved_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $request_id);
            $stmt->execute();

            $sql = "UPDATE user_balance ub
                    JOIN topup_requests tr ON ub.user_id = tr.user_id
                    SET ub.balance = ub.balance + tr.amount
                    WHERE tr.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
        } elseif ($action === 'reject') {
            // Update status menjadi rejected
            $sql = "UPDATE topup_requests SET status = 'rejected', approved_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
        }
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Terjadi kesalahan, coba lagi.";
    }
}

// Fetch pending requests
$sql = "SELECT tr.id, u.nama, tr.amount, tr.created_at 
        FROM topup_requests tr 
        JOIN users u ON tr.user_id = u.id 
        WHERE tr.status = 'pending'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Requests</title>
    <link rel="stylesheet" href="./styles/admin_topup_request.css">
</head>
<body>
    <h2>Daftar Permintaan Top-Up</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <table border="1">
        <tr>
            <th>Nama User</th>
            <th>Nominal</th>
            <th>Tanggal Permintaan</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="approve">Approve</button>
                        <button type="submit" name="action" value="reject">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="dashboard.php">Kembali</a>
</body>
</html>
