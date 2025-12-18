<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'perunet');

try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Updating 'venta' table schema...\n";
    // Add 'preparando' to the ENUM
    $sql = "ALTER TABLE venta MODIFY COLUMN estado ENUM('pendiente', 'preparando', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente'";
    $db->exec($sql);
    echo "Schema updated successfully.\n";

    // Verify
    $stmt = $db->query("DESCRIBE venta estado");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "New Column Type: " . $row['Type'] . "\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
