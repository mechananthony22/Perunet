<?php
$title = "Gestión de Soporte Técnico - Admin";
include __DIR__ . '/../../components/adminHead.php';
?>

<body class="bg-gray-50 min-h-screen">
    <?php include __DIR__ . '/../../components/adminNavBar.php'; ?>
    <?php include __DIR__ . '/../../components/adminMenuNav.php'; ?>

    <main class="p-4 md:ml-64">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fa fa-tools text-red-600 mr-3"></i>
                Gestión de Soporte Técnico
            </h1>
            <p class="text-gray-600">Administra las solicitudes de servicio técnico</p>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-gray-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $estadisticas['total'] ?? 0 ?></p>
                    </div>
                    <i class="fa fa-list text-gray-400 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pendientes</p>
                        <p class="text-3xl font-bold text-yellow-600"><?= $estadisticas['pendientes'] ?? 0 ?></p>
                    </div>
                    <i class="fa fa-clock text-yellow-400 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Aceptadas</p>
                        <p class="text-3xl font-bold text-green-600"><?= $estadisticas['aceptadas'] ?? 0 ?></p>
                    </div>
                    <i class="fa fa-check-circle text-green-400 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">En Proceso</p>
                        <p class="text-3xl font-bold text-blue-600"><?= $estadisticas['en_proceso'] ?? 0 ?></p>
                    </div>
                    <i class="fa fa-cog text-blue-400 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completadas</p>
                        <p class="text-3xl font-bold text-gray-700"><?= $estadisticas['completadas'] ?? 0 ?></p>
                    </div>
                    <i class="fa fa-check-double text-gray-400 text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-3">
                <a href="/perunet/admin/soporte" class="px-4 py-2 rounded-lg font-semibold transition <?= !isset($_GET['estado']) ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                    Todas
                </a>
                <a href="/perunet/admin/soporte?estado=pendiente" class="px-4 py-2 rounded-lg font-semibold transition <?= ($_GET['estado'] ?? '') === 'pendiente' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                    Pendientes
                </a>
                <a href="/perunet/admin/soporte?estado=aceptada" class="px-4 py-2 rounded-lg font-semibold transition <?= ($_GET['estado'] ?? '') === 'aceptada' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                    Aceptadas
                </a>
                <a href="/perunet/admin/soporte?estado=en_proceso" class="px-4 py-2 rounded-lg font-semibold transition <?= ($_GET['estado'] ?? '') === 'en_proceso' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                    En Proceso
                </a>
                <a href="/perunet/admin/soporte?estado=completada" class="px-4 py-2 rounded-lg font-semibold transition <?= ($_GET['estado'] ?? '') === 'completada' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                    Completadas
                </a>
                <a href="/perunet/admin/soporte?estado=rechazada" class="px-4 py-2 rounded-lg font-semibold transition <?= ($_GET['estado'] ?? '') === 'rechazada' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                    Rechazadas
                </a>
            </div>
        </div>

        <!-- Tabla de solicitudes -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <?php if (empty($solicitudes)): ?>
                <div class="p-12 text-center">
                    <i class="fa fa-inbox text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No hay solicitudes</h3>
                    <p class="text-gray-500">No se encontraron solicitudes con los filtros seleccionados</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Fecha/Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($solicitudes as $solicitud): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">#<?= $solicitud['id'] ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($solicitud['nombre'] . ' ' . $solicitud['apellidos']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($solicitud['correo']) ?></div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fa fa-phone mr-1"></i><?= htmlspecialchars($solicitud['telefono_contacto']) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php
                                            $servicios = [
                                                'instalacion_camaras' => 'Instalación de Cámaras',
                                                'mantenimiento' => 'Mantenimiento',
                                                'soporte_tecnico' => 'Soporte Técnico',
                                                'configuracion_redes' => 'Configuración de Redes',
                                                'otro' => 'Otro'
                                            ];
                                            echo $servicios[$solicitud['tipo_servicio']] ?? $solicitud['tipo_servicio'];
                                            ?>
                                        </div>
                                        <div class="text-sm text-gray-500 max-w-xs truncate">
                                            <?= htmlspecialchars(substr($solicitud['descripcion'], 0, 50)) ?>...
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?= date('d/m/Y', strtotime($solicitud['fecha_preferida'])) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= date('H:i', strtotime($solicitud['hora_preferida'])) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $estadoClasses = [
                                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                                            'aceptada' => 'bg-green-100 text-green-800',
                                            'rechazada' => 'bg-red-100 text-red-800',
                                            'en_proceso' => 'bg-blue-100 text-blue-800',
                                            'completada' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $estadoNombres = [
                                            'pendiente' => 'Pendiente',
                                            'aceptada' => 'Aceptada',
                                            'rechazada' => 'Rechazada',
                                            'en_proceso' => 'En Proceso',
                                            'completada' => 'Completada'
                                        ];
                                        ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $estadoClasses[$solicitud['estado']] ?>">
                                            <?= $estadoNombres[$solicitud['estado']] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="/perunet/admin/soporte/detalle/<?= $solicitud['id'] ?>" class="text-blue-600 hover:text-blue-900">
                                            <i class="fa fa-eye mr-1"></i>Ver
                                        </a>
                                        <?php if ($solicitud['estado'] === 'pendiente'): ?>
                                            <button onclick="cambiarEstado(<?= $solicitud['id'] ?>, 'aceptada')" class="text-green-600 hover:text-green-900">
                                                <i class="fa fa-check mr-1"></i>Aceptar
                                            </button>
                                            <button onclick="cambiarEstado(<?= $solicitud['id'] ?>, 'rechazada')" class="text-red-600 hover:text-red-900">
                                                <i class="fa fa-times mr-1"></i>Rechazar
                                            </button>
                                        <?php elseif ($solicitud['estado'] === 'aceptada' || $solicitud['estado'] === 'en_proceso'): ?>
                                            <button onclick="cambiarEstado(<?= $solicitud['id'] ?>, 'completada')" class="text-gray-600 hover:text-gray-900">
                                                <i class="fa fa-check-double mr-1"></i>Completar
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function cambiarEstado(id, estado) {
        const estadoNombres = {
            'aceptada': 'aceptar',
            'rechazada': 'rechazar',
            'completada': 'completar'
        };

        Swal.fire({
            title: `¿${estadoNombres[estado].charAt(0).toUpperCase() + estadoNombres[estado].slice(1)} solicitud?`,
            text: estado === 'rechazada' ? "Puedes agregar una nota explicando el motivo:" : "Puedes agregar notas adicionales:",
            input: 'textarea',
            inputPlaceholder: 'Escribe tus notas aquí...',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: estado === 'rechazada' ? '#dc2626' : '#16a34a',
            inputValidator: (value) => {
                if (estado === 'rechazada' && !value) {
                    return 'Debes proporcionar un motivo para rechazar'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('estado', estado);
                formData.append('notas', result.value || '');

                fetch('/perunet/admin/soporte/cambiar-estado', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Éxito', 'Estado actualizado correctamente', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Error al actualizar', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error de conexión', 'error');
                });
            }
        });
    }
    </script>
</body>
</html>
