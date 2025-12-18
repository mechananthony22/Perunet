<?php
// Script to test admin status update logic

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'perunet');

try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Get a test sale
    $stmt = $db->query("SELECT id_ven, estado FROM venta LIMIT 1");
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        die("No sales found to test.\n");
    }

    $id = $venta['id_ven'];
    $oldEstado = $venta['estado'];
    echo "Testing with Sale ID: $id. Current Status: $oldEstado\n";

    // 2. Define next status
    $estados = ['pendiente', 'preparando', 'enviado', 'entregado'];
    $currentIndex = array_search(strtolower($oldEstado), $estados);
    $newIndex = ($currentIndex + 1) % count($estados);
    $newEstado = $estados[$newIndex];

    echo "Attempting to update to: $newEstado\n";

    // 3. Simulate Admin Update (Direct SQL as per Controller)
    $stmt = $db->prepare("UPDATE venta SET estado = :estado WHERE id_ven = :id");
    $stmt->execute(['estado' => $newEstado, 'id' => $id]);
    echo "Update query executed.\n";

    // 4. Verify Trigger (Check seguimiento_pedido)
    $stmt = $db->prepare("SELECT * FROM seguimiento_pedido WHERE id_venta = ? ORDER BY id_seguimiento DESC LIMIT 1");
    $stmt->execute([$id]);
    $seguimiento = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($seguimiento && $seguimiento['estado_nuevo'] === $newEstado) {
        echo "SUCCESS: Trigger executed. Seguimiento record found: " . print_r($seguimiento, true) . "\n";
    } else {
        echo "FAILURE: Trigger did not verify. Last seguimiento: " . print_r($seguimiento, true) . "\n";
    }

    // 5. Verify Venta Table Status
    $stmt = $db->prepare("SELECT estado FROM venta WHERE id_ven = ?");
    $stmt->execute([$id]);
    $updatedVenta = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Venta table status is now: " . $updatedVenta['estado'] . "\n";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
