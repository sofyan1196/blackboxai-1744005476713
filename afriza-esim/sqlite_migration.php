<?php
try {
    // Buat koneksi SQLite sementara
    $db = new PDO('sqlite:afriza_esim_temp.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
    
    // Simpan status migrasi (hanya untuk demo)
    file_put_contents('migration_status.txt', 
        "MySQL migration pending. Please manually add these columns to your MySQL database:\n"
        . "ALTER TABLE users ADD COLUMN reset_token VARCHAR(64) NULL;\n"
        . "ALTER TABLE users ADD COLUMN reset_expires DATETIME NULL;");
    
    echo "Migration instructions saved to migration_status.txt";
} catch (PDOException $e) {
    die("SQLite Error: " . $e->getMessage());
}
?>