<?php
// Konfigurasi database - sesuaikan dengan environment Anda
$db_host = 'localhost';
$db_name = 'afriza_esim';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Baca file SQL
    $sql = file_get_contents(__DIR__.'/setup.sql');
    
    // Eksekusi perintah SQL
    $pdo->exec($sql);
    
    echo "Database migration completed successfully.\n";
} catch (PDOException $e) {
    die("Database migration failed: " . $e->getMessage() . "\n");
}
?>