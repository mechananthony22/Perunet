<?php
$title = "Mis Solicitudes de Soporte";
$style = "soporte";
ob_start();
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fa fa-history text-red-600 mr-3"></i>
                        Mis Solicitudes de Soporte
                    </h1>
                    <p class="text-gray-600">Historial y estado de tus solicitudes</p>
                </div>
                <a href="/perunet/soporte" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-lg transition-colors">
                    <i class="fa fa-plus mr-2"></i>Nueva Solicitud
                </a>
            </div>
        </div>

        <?php if (empty($solicitudes)): ?>
            <!-- Sin solicitudes -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fa fa-inbox text-gray-300 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-700 mb-2">No tienes solicitudes</h2>
                <p class="text-gray-500 mb-6">Aún no has realizado ninguna solicitud de soporte técnico</p>
                <a href="/perunet/soporte" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-3 rounded-lg transition-colors">
                    <i class="fa fa-plus mr-2"></i>Crear Primera Solicitud
                </a>
            </div>
        <?php else: ?>
            <!-- Tabla de solicitudes -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Servicio</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha Preferida</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha Solicitud</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Detalles</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($solicitudes as $solicitud): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">#<?= $solicitud['id'] ?></span>
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
                                        <div class="text-sm text-gray-500 truncate max-w-xs">
                                            <?= htmlspecialchars(substr($solicitud['descripcion'], 0, 50)) ?>...
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <i class="fa fa-calendar mr-1"></i>
                                            <?= date('d/m/Y', strtotime($solicitud['fecha_preferida'])) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fa fa-clock mr-1"></i>
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
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $estadoClasses[$solicitud['estado']] ?>">
                                            <?= $estadoNombres[$solicitud['estado']] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y H:i', strtotime($solicitud['fecha_creacion'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button onclick="verDetalle(<?= htmlspecialchars(json_encode($solicitud)) ?>)" class="text-red-600 hover:text-red-900 font-semibold">
                                            <i class="fa fa-eye mr-1"></i>Ver
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de detalles -->
<div id="modal-detalle" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-red-600 text-white p-6 flex justify-between items-center rounded-t-xl">
            <h3 class="text-2xl font-bold">Detalle de Solicitud</h3>
            <button onclick="cerrarModal()" class="text-white hover:text-gray-200 text-2xl font-bold">&times;</button>
        </div>
        <div id="modal-content" class="p-6 space-y-4">
            <!-- El contenido se llena dinámicamente -->
        </div>
    </div>
</div>

<script>
function verDetalle(solicitud) {
    const servicios = {
        'instalacion_camaras': 'Instalación de Cámaras',
        'mantenimiento': 'Mantenimiento',
        'soporte_tecnico': 'Soporte Técnico',
        'configuracion_redes': 'Configuración de Redes',
        'otro': 'Otro'
    };

    const estadoClasses = {
        'pendiente': 'bg-yellow-100 text-yellow-800',
        'aceptada': 'bg-green-100 text-green-800',
        'rechazada': 'bg-red-100 text-red-800',
        'en_proceso': 'bg-blue-100 text-blue-800',
        'completada': 'bg-gray-100 text-gray-800'
    };

    const estadoNombres = {
        'pendiente': 'Pendiente',
        'aceptada': 'Aceptada',
        'rechazada': 'Rechazada',
        'en_proceso': 'En Proceso',
        'completada': 'Completada'
    };

    const content = `
        <div class="space-y-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-600">ID de Solicitud</p>
                    <p class="text-lg font-bold text-gray-900">#${solicitud.id}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold ${estadoClasses[solicitud.estado]}">
                    ${estadoNombres[solicitud.estado]}
                </span>
            </div>

            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 mb-1">Tipo de Servicio</p>
                <p class="text-lg font-semibold text-gray-900">${servicios[solicitud.tipo_servicio]}</p>
            </div>

            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 mb-1">Descripción</p>
                <p class="text-gray-800">${solicitud.descripcion}</p>
            </div>

            <div class="border-t pt-4 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Fecha Preferida</p>
                    <p class="text-gray-900 font-semibold">
                        <i class="fa fa-calendar text-red-600 mr-2"></i>
                        ${new Date(solicitud.fecha_preferida).toLocaleDateString('es-PE')}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Hora Preferida</p>
                    <p class="text-gray-900 font-semibold">
                        <i class="fa fa-clock text-red-600 mr-2"></i>
                        ${solicitud.hora_preferida}
                    </p>
                </div>
            </div>

            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 mb-1">Teléfono de Contacto</p>
                <p class="text-gray-900 font-semibold">
                    <i class="fa fa-phone text-red-600 mr-2"></i>
                    ${solicitud.telefono_contacto}
                </p>
            </div>

            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 mb-1">Dirección del Servicio</p>
                <p class="text-gray-900">
                    <i class="fa fa-map-marker-alt text-red-600 mr-2"></i>
                    ${solicitud.direccion}
                </p>
            </div>

            ${solicitud.notas_admin ? `
                <div class="border-t pt-4 bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-800 font-semibold mb-2">
                        <i class="fa fa-comment text-blue-600 mr-2"></i>
                        Notas del Administrador
                    </p>
                    <p class="text-blue-900">${solicitud.notas_admin}</p>
                </div>
            ` : ''}

            <div class="border-t pt-4 text-sm text-gray-500">
                <p>Solicitud creada el: ${new Date(solicitud.fecha_creacion).toLocaleString('es-PE')}</p>
                <p>Última actualización: ${new Date(solicitud.fecha_actualizacion).toLocaleString('es-PE')}</p>
            </div>
        </div>
    `;

    document.getElementById('modal-content').innerHTML = content;
    document.getElementById('modal-detalle').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-detalle').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
document.getElementById('modal-detalle').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/default.php';
?>
