 Chat Application with User Registration

 Deskripsi Proyek

    Aplikasi ini adalah platform chat sederhana berbasis PHP dan MySQL yang memungkinkan pengguna untuk:

    - Mendaftar akun baru dengan detail lengkap seperti nama, username, email, alamat, dan password.
    - Login untuk mengakses fitur chatting.
    - Chat dengan pengguna lain

Fitur Utama
    - User Registration: Pengguna dapat mendaftar dengan informasi seperti nama, username, email, alamat, dan password.
    - User Login: Sistem otentikasi untuk memvalidasi kredensial.
    - Chat System: Pengguna dapat saling mengirim pesan, dilengkapi dengan desain bubble chat.
    - Admin Dashboard: Halaman khusus untuk admin.
    
 Prasyarat
  Sebelum menjalankan aplikasi ini, pastikan Anda sudah menginstal:
    - PHP (versi 7.4 atau lebih baru)
    - MySQL
    - Apache/Nginx (disarankan menggunakan XAMPP/LAMP stack untuk pengembangan lokal)
    
 Instalasi
 
1. Clone Repository
        Gunakan :
        git clone https://github.com/nurilham524/login.git
        cd login
2.  Setup Database
    - Jalankan MySql dan Apache2 pada terminal(Disini saya menggunakan OS Linux)
    - Masuk pada MySQL dan jalankan perintah berikut untuk membuat database da table

    <!-- buat database -->
    CREATE DATABASE user_data;

    <!-- pilih database -->
    USE user_data;

    <!-- Buat table users -->
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

    <!-- buat table messages -->
    CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_username VARCHAR(50) NOT NULL,
    receiver_username VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_username) REFERENCES users(username),
    FOREIGN KEY (receiver_username) REFERENCES users(username)
    );

3.  Konfigurasi tabel database 
    - buat file baru dengan nama db.php seperti pada file diatas
    - pastikan untuk merubah user sesuai dengan username MySQL anda, brgitu juga dengan passwod MySQL dan nama database anda sendiri. 

4.  Konfigurasi Web Server
    - jangan lupa untuk meletakkan semua file diatas pada proyek folder root server. disini saya menggunakan linux maka file dsiimpan di /var/www/html/login
    - akses aplikasi melalui http://localhost/login

5. Jalankan aplikasi 
    - akses halaman registrasi melalui http://localhost/login/register.php
    - login menggunakan akun yang telah anda buat melalui http://localhost/login/login.php

Struktur Folder

Login/
│
├──chat
    ├── chat_list.php
    ├── chat.php
    └── send_messages.php
├── db.php                  
├── login.php               
├── register.php            
├── dashboard.php  
├── edit_profile.php
├── admin.php               
├── README.md
└── logout.php


