<?php

class ContactoController
{
    public function index()
    {
        include(__DIR__ . '/../views/public/contacto.php');
    }

    public function adminMensajes()
    {
        require_once __DIR__ . '/../core/App.php';
        $db = App::getInstance()->getDatabase();
        try {
            $query = "SELECT * FROM contacto ORDER BY fecha_creacion DESC";
            $stmt = $db->query($query);
            $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $mensajes = [];
        }
        include __DIR__ . '/../views/admin/contacto/mensajes.php';
    }

    public function enviar()
    {
        header('Content-Type: application/json');
        $nombre = $_POST['nombre'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $mensaje = $_POST['mensaje'] ?? '';
        require_once __DIR__ . '/../core/App.php';
        $db = App::getInstance()->getDatabase();
        try {
            $query = "INSERT INTO contacto (nombre, telefono, correo, mensaje, fecha_creacion) VALUES ('$nombre', '$telefono', '$correo', '$mensaje', NOW())";
            $db->query($query);
            echo json_encode(['success' => 'Mensaje enviado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
}
