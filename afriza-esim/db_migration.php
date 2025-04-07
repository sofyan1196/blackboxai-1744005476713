<?php
// File migrasi alternatif
require __DIR__.'/config/db.php';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Tambahkan kolom untuk reset password
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS reset_token VARCHAR(64) NULL");
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS reset_expires DATETIME NULL");
    
    echo "Database migration completed successfully!\n";
} catch (PDOException $e) {
    die("Migration failed: " . $e->getMessage() . "\n");
}
?>