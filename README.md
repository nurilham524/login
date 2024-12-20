![alt text](https://github.com/nurilham524/login/blob/master/public/Login.png?raw=true)
# Manajemen Keuangan & Chat Application with User Registration


## Deskripsi Proyek
Aplikasi ini adalah platform manajemen keuangan & chat sederhana berbasis PHP dan MySQL yang memungkinkan pengguna untuk:
- Mendaftar akun baru dengan detail lengkap seperti nama, username, email, alamat, dan password.
- Login untuk mengakses fitur chatting dan top-up saldo.
- Chat dengan pengguna lain
- transfer saldo sesama user

## Fitur Utama

1. User Registration: Pengguna dapat mendaftar dengan informasi seperti nama, username, email, alamat, dan password.
2. User Login: Sistem otentikasi untuk memvalidasi kredensial.
3. Chat System: Pengguna dapat saling mengirim pesan, dilengkapi dengan desain bubble chat.
4. Topup Saldo: User dapat melakukan topup dengan persetujuan admin
5. Transfer Saldo: User dapat mengirim saldo ke user lain.
6. Admin Dashboard: Halaman khusus untuk admin.

## Panduan Instalasi (prasyarat)

1. Web Server: Apache atau Nginx.
2. PHP: Versi 7.4 atau lebih baru.
3. Database: MySQL atau MariaDB.
4. Composer: Untuk mengelola dependensi (opsional).

## Langkah - langkah


1. Clone Repository

```sh
git clone https://github.com/nurilham524/login.git
cd LOGIN
```

2. KOnfigurasi Database
- Jalankan MySQL dan Apache2 pada terminal (Disini saya menggunakan linux)
- Jalankan perintah berikut untuk membuat database dan tabel:

```sql
-- Buat database
CREATE DATABASE user_data;

-- Pilih database
USE user_data;

-- Buat tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    alamat TEXT NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Buat tabel messages
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_username VARCHAR(50) NOT NULL,
    receiver_username VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_username) REFERENCES users(username),
    FOREIGN KEY (receiver_username) REFERENCES users(username)
);
```
3. Konfigurasi file database
edit file db.php dengan detail koneksi database anda:
```php
<?php
$host = 'localhost';
$user = 'ilham'; // Ubah sesuai dengan username MySQL Anda
$password = ''; // Ubah sesuai password MySQL Anda
$database = 'user_data'; // Nama database

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```
## Konfigurasi Web Server

- Jangan lupa untuk meletakkan semua file diatas pada proyek folder root server, disini saya menggunakan linux maka file disimpan pada:
```bash
/var/www/html/LOGIN
```
## Jalankan Aplikasi
- Akses halaman registrasi melalui http://localhost/LOGIN/registrasi.php untuk mendaftar akun baru
  ![alt text](https://github.com/nurilham524/login/blob/master/public/Registrasi.png?raw=true)
- Login menggunakan akun yang telah dibuat melalui http://localhost/LOGIN/login.php

## Struktur Folder
pastikan struktur folder seperti berikut:
```
📦LOGIN
|--chat
|    |-- chat_list.php
|    |-- chat.php
|    |__ send_messages.php
|
|--Public
|--Styles
|    |-- admin_topup_request.css
|    |-- Admin.css
|    |-- dashboard.css
|    |-- login.css
|    |-- registrasi.css
|    |-- topup.css
|    |__ transfer.css
|
|--Uploads
|-- admin.php
|-- dashboard.php
|-- db.php
|-- edit_profile.php
|-- login.php
|-- logout.php
|-- register.php
|__ README.md

```
## Tampilan User
![alt text](https://github.com/nurilham524/login/blob/master/public/User_Dashboard.png?raw=true)
![alt text](https://github.com/nurilham524/login/blob/master/public/Chat_display.png?raw=true)
![alt text](https://github.com/nurilham524/login/blob/master/public/Topup_Display.png?raw=true)
