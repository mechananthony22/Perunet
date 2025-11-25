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
} 