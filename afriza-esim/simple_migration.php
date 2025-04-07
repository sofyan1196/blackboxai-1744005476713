<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'afriza_esim';

// Coba koneksi dengan MySQLi
$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Tambahkan kolom reset_token dan reset_expires
$sql = "ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS reset_token VARCHAR(64) NULL,
        ADD COLUMN IF NOT EXISTS reset_expires DATETIME NULL";

if ($mysqli->query($sql) === TRUE) {
    echo "Migration completed successfully!";
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>