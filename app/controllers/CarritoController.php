<?php

class CarritoController
{
    private $detalleCarrito;
    private $metodoPago;

    public function __construct()
    {
        $this->detalleCarrito = new DetalleCarrito();
        $this->metodoPago = new MetodoPagoModel();
    }

    public function aplicarCupon()
    {
        header('Content-Type: application/json');
        $codigo = $_GET['codigo'] ?? '';
        require_once __DIR__ . '/../core/App.php';
        $db = App::getInstance()->getDatabase();
        try {
            $query = "SELECT * FROM cupon WHERE codigo = '$codigo' AND activo = 1";
            $stmt = $db->query($query);
            $cupon = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($cupon ?: ['error' => 'Cupón no válido']);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function index()
    {
        $id_usuario = isset($_SESSION['usuario']['id']) ? $_SESSION['usuario']['id'] : null;

        // si el usuario esta logueado
        if ($id_usuario != null) {

            // eliminar productos sin stock del carrito del usuario
            $remove_out_of_stock = $this->detalleCarrito->removeOutOfStockItems($id_usuario);

            // mensaje si los productos ya no cumplen con stock suficiente
            if ($remove_out_of_stock > 0) {
                $_SESSION['mensaje'] = "Se eliminaron {$remove_out_of_stock} productos sin stock suficiente.";
            }

            // obtener los productos del carrito del usuario
            $carrito = $this->detalleCarrito->getItems($id_usuario);
            $precio_total = $this->detalleCarrito->getTotal($id_usuario);
        }

        $metodos = $this->metodoPago->getAll();
        include(__DIR__ . '/../views/ventas/carrito.php');
    }

    public function vaciarCarrito()
    {
        $this->detalleCarrito->delete($_SESSION['usuario']['id']);
        header("Location: /perunet/carrito");
    }
}
