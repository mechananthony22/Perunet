<?php
class UsuarioController {
    public function perfil() {
        // Verificar sesión
        $usuarioId = $_SESSION['usuario']['id_us'] ?? null;
        if (!$usuarioId) {
            header('Location: /perunet/login');
            exit;
        }
        require_once __DIR__ . '/../models/UsuarioModel.php';
        require_once __DIR__ . '/../models/VentaModel.php';
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->getById($usuarioId);
        $ventaModel = new VentaModel();
        $compras = $ventaModel->getByUsuario($usuarioId);
        require __DIR__ . '/../views/usuarios/perfil.php';
    }

    public function detalleCompra($id) {
        $usuarioId = $_SESSION['usuario']['id_us'] ?? null;
        if (!$usuarioId) {
            header('Location: /perunet/login');
            exit;
        }
        require_once __DIR__ . '/../models/VentaModel.php';
        $ventaModel = new VentaModel();
        $detalle = $ventaModel->getDetalleById($id, $usuarioId);
        if (!$detalle) {
            echo "No tienes acceso a este comprobante.";
            exit;
        }
        require __DIR__ . '/../views/usuarios/detalle_compra.php';
    }

    public function apiUsuario($id) {
        header('Content-Type: application/json');
        require_once __DIR__ . '/../core/App.php';
        $db = App::getInstance()->getDatabase();
        try {
            $query = "SELECT id_us, nombre, apellidos, correo, dni, telefono, contrasena, id_rol FROM usuario WHERE id_us = $id";
            $stmt = $db->query($query);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($usuario ?: ['error' => 'Usuario no encontrado']);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function tracking($id) {
        $usuarioId = $_SESSION['usuario']['id_us'] ?? null;
        if (!$usuarioId) {
            header('Location: /perunet/login');
            exit;
        }
        require_once __DIR__ . '/../models/VentaModel.php';
        $ventaModel = new VentaModel();
        $detalle = $ventaModel->getDetalleById($id, $usuarioId);
        
        if (!$detalle || empty($detalle)) {
            // Redirigir o mostrar error si no se encuentra la venta
            header('Location: /perunet/perfil');
            exit;
        }

        // Extraer datos generales de la venta del primer registro
        $venta = $detalle[0];
        
        require __DIR__ . '/../views/usuarios/tracking.php';
    }
}