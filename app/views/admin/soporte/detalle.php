<?php
$title = "Detalle de Solicitud #" . ($solicitud['id'] ?? '');
include __DIR__ . '/../../components/adminHead.php';
?>

<body class="bg-gray-50 min-h-screen">
    <?php include __DIR__ . '/../../components/adminNavBar.php'; ?>
    <?php include __DIR__ . '/../../components/adminMenuNav.php'; ?>

    <main class="p-4 md:ml-64">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Detalle de Solicitud #<?= $solicitud['id'] ?>
                </h1>
                <p class="text-gray-600">Información completa de la solicitud</p>
            </div>
            <a href="/perunet/admin/soporte" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-6 py-3 rounded-lg transition">
                <i class="fa fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información de la solicitud -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del servicio -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fa fa-tools text-red-600 mr-2"></i>
                        Información del Servicio
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tipo de Servicio</p>
                            <p class="text-lg font-semibold text-gray-900">
                                <?php
                                $servicios = [
                                    'instalacion_camaras' => 'Instalación de Cámaras de Seguridad',
                                    'mantenimiento' => 'Mantenimiento de Equipos',
                                    'soporte_tecnico' => 'Soporte Técnico General',
                                    'configuracion_redes' => 'Configuración de Redes',
                                    'otro' => 'Otro Servicio'
                                ];
                                echo $servicios[$solicitud['tipo_servicio']] ?? $solicitud['tipo_servicio'];
                                ?>
                            </p>
                        </div>

                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-600 mb-1">Descripción</p>
                            <p class="text-gray-800"><?= nl2br(htmlspecialchars($solicitud['descripcion'])) ?></p>
                        </div>

                        <div class="border-t pt-4 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Fecha Preferida</p>
                                <p class="text-gray-900 font-semibold">
                                    <i class="fa fa-calendar text-red-600 mr-2"></i>
                                    <?= date('d/m/Y', strtotime($solicitud['fecha_preferida'])) ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Hora Preferida</p>
                                <p class="text-gray-900 font-semibold">
                                    <i class="fa fa-clock text-red-600 mr-2"></i>
                                    <?= date('H:i', strtotime($solicitud['hora_preferida'])) ?>
                                </p>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-600 mb-1">Dirección del Servicio</p>
                            <p class="text-gray-900">
                                <i class="fa fa-map-marker-alt text-red-600 mr-2"></i>
                                <?= htmlspecialchars($solicitud['direccion']) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Información del cliente -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fa fa-user text-blue-600 mr-2"></i>
                        Información del Cliente
                    </h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Nombre Completo</p>
                                <p class="text-gray-900 font-semibold">
                                    <?= htmlspecialchars($solicitud['nombre'] . ' ' . $solicitud['apellidos']) ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">DNI</p>
                                <p class="text-gray-900 font-semibold"><?= htmlspecialchars($solicitud['dni']) ?></p>
                            </div>
                        </div>

                        <div class="border-t pt-4 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Correo Electrónico</p>
                                <p class="text-gray-900">
                                    <i class="fa fa-envelope text-blue-600 mr-2"></i>
                                    <?= htmlspecialchars($solicitud['correo']) ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Teléfono de Contacto</p>
                                <p class="text-gray-900">
                                    <i class="fa fa-phone text-blue-600 mr-2"></i>
                                    <?= htmlspecialchars($solicitud['telefono_contacto']) ?>
                                </p>
                            </div>
                        </div>

                        <div class="border-t pt-4 flex gap-3">
                            <a href="tel:<?= $solicitud['telefono_contacto'] ?>" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg text-center transition">
                                <i class="fa fa-phone mr-2"></i>Llamar
                            </a>
                            <a href="mailto:<?= $solicitud['correo'] ?>" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 rounded-lg text-center transition">
                                <i class="fa fa-envelope mr-2"></i>Enviar Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar con estado y acciones -->
            <div class="space-y-6">
                <!-- Estado actual -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Estado Actual</h2>
                    
                    <?php
                    $estadoClasses = [
                        'pendiente' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                        'aceptada' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                        'rechazada' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle'],
                        'en_proceso' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-cog'],
                        'completada' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-check-double']
                    ];
                    $estadoNombres = [
                        'pendiente' => 'Pendiente',
                        'aceptada' => 'Aceptada',
                        'rechazada' => 'Rechazada',
                        'en_proceso' => 'En Proceso',
                        'completada' => 'Completada'
                    ];
                    $currentEstado = $estadoClasses[$solicitud['estado']];
                    ?>

                    <div class="<?= $currentEstado['bg'] ?> <?= $currentEstado['text'] ?> p-4 rounded-lg text-center">
                        <i class="fa <?= $currentEstado['icon'] ?> text-4xl mb-2"></i>
                        <p class="text-xl font-bold"><?= $estadoNombres[$solicitud['estado']] ?></p>
                    </div>

                    <?php if (!empty($solicitud['notas_admin'])): ?>
                        <div class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-800 font-semibold mb-2">
                                <i class="fa fa-comment mr-1"></i>Notas del Administrador
                            </p>
                            <p class="text-blue-900 text-sm"><?= nl2br(htmlspecialchars($solicitud['notas_admin'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Acciones rápidas -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Acciones</h2>
                    
                    <div class="space-y-3">
                        <?php if ($solicitud['estado'] === 'pendiente'): ?>
                            <button onclick="cambiarEstado('aceptada')" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition">
                                <i class="fa fa-check mr-2"></i>Aceptar Solicitud
                            </button>
                            <button onclick="cambiarEstado('rechazada')" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition">
                                <i class="fa fa-times mr-2"></i>Rechazar Solicitud
                            </button>
                        <?php elseif ($solicitud['estado'] === 'aceptada'): ?>
                            <button onclick="cambiarEstado('en_proceso')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                                <i class="fa fa-cog mr-2"></i>Marcar en Proceso
                            </button>
                            <button onclick="cambiarEstado('completada')" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 rounded-lg transition">
                                <i class="fa fa-check-double mr-2"></i>Marcar Completada
                            </button>
                        <?php elseif ($solicitud['estado'] === 'en_proceso'): ?>
                            <button onclick="cambiarEstado('completada')" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 rounded-lg transition">
                                <i class="fa fa-check-double mr-2"></i>Marcar Completada
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Fecha de solicitud -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Fechas</h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600">Creada el:</p>
                            <p class="text-gray-900 font-semibold">
                                <?= date('d/m/Y H:i', strtotime($solicitud['fecha_creacion'])) ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">Última actualización:</p>
                            <p class="text-gray-900 font-semibold">
                                <?= date('d/m/Y H:i', strtotime($solicitud['fecha_actualizacion'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function cambiarEstado(estado) {
        const estadoNombres = {
            'aceptada': 'Aceptar',
            'rechazada': 'Rechazar',
            'en_proceso': 'Marcar en Proceso',
            'completada': 'Marcar como Completada'
        };

        Swal.fire({
            title: `${estadoNombres[estado]} solicitud`,
            text: estado === 'rechazada' ? "Debes agregar una nota explicando el motivo:" : "Puedes agregar notas adicionales:",
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
                formData.append('id', <?= $solicitud['id'] ?>);
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
