<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/core/App.php';
require_once __DIR__ . '/../../app/core/Model.php';
require_once __DIR__ . '/../../app/models/VentaModel.php';
require_once __DIR__ . '/../../app/models/DetalleCarrito.php';
require_once __DIR__ . '/../../app/models/CarritoModel.php';
require_once __DIR__ . '/../../app/models/ProductoModel.php';

function sendResponse($status, $message, $data = [])
{
    http_response_code($status === 'error' ? 400 : 200);
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

$accion = $_POST['accion'] ?? '';

if ($accion == 'create') {
    header('Content-Type: application/json');

    try {
        $db = App::getInstance()->getDatabase();
        $ventaModel = new VentaModel($db);
        $detalleCarrito = new DetalleCarrito($db);
        $carritoModel = new CarritoModel($db);
        $productoModel = new ProductoModel($db);

        // Validación de datos
        $usuario = json_decode($_POST['usuario'] ?? '', true);
        $entrega = json_decode($_POST['entrega'] ?? '', true);
        $metodoPago = json_decode($_POST['metodoPago'] ?? '', true);

        if (!$usuario || !$entrega || !$metodoPago) {
            sendResponse('error', 'Datos incompletos', [
                'usuario' => $usuario,
                'entrega' => $entrega,
                'metodoPago' => $metodoPago
            ]);
        }

        // Validar tipo de entrega
        if (!isset($entrega['tipo']) || !in_array($entrega['tipo'], ['domicilio', 'tienda'])) {
            sendResponse('error', 'Tipo de entrega no válido');
        }

        // Validar método de pago (ahora siempre es obligatorio)
        if (!isset($metodoPago['seleccionado']) || !in_array($metodoPago['seleccionado'], [1, 2, 3])) {
            sendResponse('error', 'Método de pago no válido');
        }

        // Obtener total del carrito
        $total = $detalleCarrito->getTotal($usuario['id']);
        if ($total <= 0) {
            sendResponse('error', 'El carrito está vacío');
        }

        // Iniciar transacción
        $db->beginTransaction();

        try {

            // 1. Registrar venta principal (sin dirección al principio)
            $idVenta = $ventaModel->insertarVenta(
                $usuario['id'],
                null, // id_direccion se actualizará después
                $total,
                $metodoPago['seleccionado'],
                $entrega['tipo'],
                $entrega['tipo'] === 'tienda' ? $entrega['sucursal']['id'] : null
            );

            if (!$idVenta) {
                throw new Exception('No se pudo crear la venta (ID no devuelto)');
            }

            // 2. Registrar dirección de entrega (si aplica) y asociarla a la venta
            if ($entrega['tipo'] === 'domicilio') {
                $idDireccion = $ventaModel->guardarDireccion(
                    $usuario['id'], // <-- Este debe ser el id_us de la tabla usuario
                    $entrega['domicilio']['departamento'] ?? '',
                    $entrega['domicilio']['provincia'] ?? '',
                    $entrega['domicilio']['distrito'] ?? '',
                    $entrega['domicilio']['calle'] ?? '',
                    $entrega['domicilio']['numero'] ?? '',
                    $entrega['domicilio']['piso'] ?? '',
                    $entrega['domicilio']['referencia'] ?? ''
                );
                
                // 3. Actualizar la venta con el ID de la dirección
                if ($idDireccion) {
                    $ventaModel->updateDireccionVenta($idVenta, $idDireccion);
                }
            }

            // Registrar detalles de la venta
            $detalles_Carrito = $detalleCarrito->getItems($usuario['id']);

            // comprobar si hay carrito
            if (!$detalles_Carrito) {
                throw new Exception('No se ha creado el carrito');
            }

            // insertar y comprobar si hay detalles
            if (!$ventaModel->insertarDetalle($idVenta, $detalles_Carrito)) {
                throw new Exception('Error al registrar los detalles de la venta');
            }

            // actualizar stock productos del carrito
            foreach ($detalles_Carrito as $detalle) {
                $productoModel->actualizarStock($detalle['id_producto'], $detalle['stock_producto'] - $detalle['cantidad']);
            }

            // Registrar método de pago
            $ventaModel->guardarPago(
                $idVenta,
                $metodoPago['tarjeta']['numero'] ?? null,
                $metodoPago['celular']['numero'] ?? null
            );

            // Limpiar carrito
            $carritoModel->delete($usuario['id']);

            // Confirmar transacción
            $db->commit();

            // Respuesta exitosa
            sendResponse('success', 'Venta registrada exitosamente', [
                'idVenta' => $idVenta,
                'total' => $total
            ]);
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $db->rollBack();
            throw $e;
        }
    } catch (Exception $e) {
        sendResponse('error', $e->getMessage());
    }
}