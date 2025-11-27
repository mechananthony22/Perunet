<?php

class SoporteController
{
    private $model;

    public function __construct()
    {
        $this->model = new SoporteModel();
    }

    /**
     * Vista principal de soporte técnico (Usuario)
     */
    public function index()
    {
        require_once __DIR__ . '/../views/soporte/index.php';
    }

    /**
     * Ver mis solicitudes (Usuario)
     */
    public function misSolicitudes()
    {
        $usuarioId = $_SESSION['usuario']['id_us'] ?? null;
        
        if (!$usuarioId) {
            header('Location: /perunet/login');
            exit;
        }

        $solicitudes = $this->model->getSolicitudesByUser($usuarioId);
        require_once __DIR__ . '/../views/soporte/mis_solicitudes.php';
    }

    /**
     * Crear solicitud de soporte (Usuario)
     */
    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /perunet/soporte');
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id_us'] ?? null;
        
        if (!$usuarioId) {
            $_SESSION['mensaje_error'] = "Debes iniciar sesión para solicitar soporte.";
            header('Location: /perunet/login');
            exit;
        }

        try {
            $data = [
                'tipo_servicio' => $_POST['tipo_servicio'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'fecha_preferida' => $_POST['fecha_preferida'] ?? '',
                'hora_preferida' => $_POST['hora_preferida'] ?? '',
                'telefono_contacto' => $_POST['telefono_contacto'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];

            // Validaciones
            if (empty($data['tipo_servicio']) || empty($data['descripcion']) || 
                empty($data['fecha_preferida']) || empty($data['hora_preferida']) ||
                empty($data['telefono_contacto']) || empty($data['direccion'])) {
                $_SESSION['mensaje_error'] = "Todos los campos son obligatorios.";
                header('Location: /perunet/soporte');
                exit;
            }

            $solicitudId = $this->model->createSolicitud($usuarioId, $data);

            if ($solicitudId) {
                $_SESSION['mensaje_exito'] = "Solicitud enviada exitosamente. Nos pondremos en contacto pronto.";
                header('Location: /perunet/soporte/mis-solicitudes');
            } else {
                $_SESSION['mensaje_error'] = "Error al enviar la solicitud.";
                header('Location: /perunet/soporte');
            }
        } catch (Exception $e) {
            $_SESSION['mensaje_error'] = "Error: " . $e->getMessage();
            header('Location: /perunet/soporte');
        }
        exit;
    }

    /**
     * Panel de gestión de solicitudes (Admin)
     */
    public function admin()
    {
        $filtro = $_GET['estado'] ?? null;
        $solicitudes = $this->model->getAllSolicitudes($filtro);
        $estadisticas = $this->model->getEstadisticas();
        require_once __DIR__ . '/../views/admin/soporte/index.php';
    }

    /**
     * Cambiar estado de solicitud (Admin - AJAX)
     */
    public function cambiarEstado()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        try {
            $id = $_POST['id'] ?? null;
            $estado = $_POST['estado'] ?? null;
            $notas = $_POST['notas'] ?? null;

            if (!$id || !$estado) {
                echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
                exit;
            }

            $result = $this->model->updateEstado($id, $estado, $notas);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Ver detalle de solicitud (Admin)
     */
    public function detalle($id)
    {
        $solicitud = $this->model->getSolicitudById($id);
        
        if (!$solicitud) {
            header('Location: /perunet/admin/soporte');
            exit;
        }

        require_once __DIR__ . '/../views/admin/soporte/detalle.php';
    }
}
