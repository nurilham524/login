<?php
$host = 'localhost';
$user = 'ilham';
$pass = 'ilham123';
$db = 'user_data';


$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
