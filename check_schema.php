<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'perunet');

try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->query("DESCRIBE seguimiento_pedido estado_nuevo");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Column Type: " . $row['Type'] . "\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
