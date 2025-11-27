<?php
$title = "Soporte Técnico";
$style = "soporte";
ob_start();
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header con banner informativo -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-xl shadow-lg p-8 mb-8 text-white">
            <div class="flex items-center mb-4">
                <i class="fa fa-tools text-5xl mr-4"></i>
                <div>
                    <h1 class="text-3xl font-bold">Soporte Técnico</h1>
                    <p class="text-red-100">Servicios profesionales de instalación y mantenimiento</p>
                </div>
            </div>
            <p class="text-lg leading-relaxed">
                ¿Necesitas instalación de cámaras, soporte técnico o mantenimiento?
                Nuestro equipo especializado está listo para ayudarte. Contáctanos y recibe atención inmediata.
            </p>
        </div>

        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p class="font-semibold"><?= $_SESSION['mensaje_exito'] ?></p>
            </div>
            <?php unset($_SESSION['mensaje_exito']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p class="font-semibold"><?= $_SESSION['mensaje_error'] ?></p>
            </div>
            <?php unset($_SESSION['mensaje_error']); ?>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulario de solicitud -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Solicitar Servicio</h2>
                    <a href="/perunet/soporte/mis-solicitudes" class="text-red-600 hover:text-red-700 font-semibold">
                        <i class="fa fa-history mr-2"></i>Ver Mis Solicitudes
                    </a>
                </div>

                <form action="/perunet/soporte/crear" method="POST" class="space-y-6">
                    <!-- Tipo de servicio -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa fa-wrench mr-2 text-red-600"></i>Tipo de Servicio *
                        </label>
                        <select name="tipo_servicio" required class="w-full rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 px-4 py-3">
                            <option value="">Selecciona un servicio</option>
                            <option value="instalacion_camaras">Instalación de Cámaras de Seguridad</option>
                            <option value="mantenimiento">Mantenimiento de Equipos</option>
                            <option value="soporte_tecnico">Soporte Técnico General</option>
                            <option value="configuracion_redes">Configuración de Redes</option>
                            <option value="otro">Otro Servicio</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa fa-comment mr-2 text-red-600"></i>Descripción del Servicio *
                        </label>
                        <textarea name="descripcion" required rows="4" placeholder="Describe detalladamente el servicio que necesitas..." class="w-full rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 px-4 py-3"></textarea>
                    </div>

                    <!-- Fecha y hora -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-calendar mr-2 text-red-600"></i>Fecha Preferida *
                            </label>
                            <input type="date" name="fecha_preferida" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="w-full rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-clock mr-2 text-red-600"></i>Hora Preferida *
                            </label>
                            <select name="hora_preferida" required class="w-full rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 px-4 py-3">
                                <option value="">Selecciona una hora</option>
                                <option value="08:00:00">8:00 AM</option>
                                <option value="09:00:00">9:00 AM</option>
                                <option value="10:00:00">10:00 AM</option>
                                <option value="11:00:00">11:00 AM</option>
                                <option value="12:00:00">12:00 PM</option>
                                <option value="13:00:00">1:00 PM</option>
                                <option value="14:00:00">2:00 PM</option>
                                <option value="15:00:00">3:00 PM</option>
                                <option value="16:00:00">4:00 PM</option>
                                <option value="17:00:00">5:00 PM</option>
                            </select>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa fa-phone mr-2 text-red-600"></i>Teléfono de Contacto *
                        </label>
                        <input type="tel" name="telefono_contacto" required pattern="[0-9]{9}" maxlength="9" placeholder="987654321" class="w-full rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 px-4 py-3">
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa fa-map-marker-alt mr-2 text-red-600"></i>Dirección del Servicio *
                        </label>
                        <textarea name="direccion" required rows="2" placeholder="Av. Lima 123, Chiclayo..." class="w-full rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 px-4 py-3"></textarea>
                    </div>

                    <!-- Botón enviar -->
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-lg transition-colors shadow-lg">
                        <i class="fa fa-paper-plane mr-2"></i>Enviar Solicitud
                    </button>
                </form>
            </div>

            <!-- Sidebar con información -->
            <div class="space-y-6">
                <!-- Horarios de atención -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fa fa-clock text-red-600 mr-2"></i>
                        Horarios de Atención
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="font-semibold text-gray-700">Lunes - Viernes</span>
                            <span class="text-gray-600">8:00 AM - 6:00 PM</span>
                        </li>
                        <li class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="font-semibold text-gray-700">Sábados</span>
                            <span class="text-gray-600">9:00 AM - 2:00 PM</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="font-semibold text-gray-700">Domingos</span>
                            <span class="text-red-600 font-semibold">Cerrado</span>
                        </li>
                    </ul>
                </div>

                <!-- Servicios disponibles -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fa fa-list-check text-red-600 mr-2"></i>
                        Servicios Disponibles
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-green-600 mr-2 mt-1"></i>
                            <span class="text-gray-700">Instalación de cámaras de seguridad</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-green-600 mr-2 mt-1"></i>
                            <span class="text-gray-700">Mantenimiento preventivo y correctivo</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-green-600 mr-2 mt-1"></i>
                            <span class="text-gray-700">Configuración de redes</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-green-600 mr-2 mt-1"></i>
                            <span class="text-gray-700">Soporte técnico especializado</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-green-600 mr-2 mt-1"></i>
                            <span class="text-gray-700">Asesoría técnica gratuita</span>
                        </li>
                    </ul>
                </div>

                <!-- Contacto directo -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-lg p-6 text-white">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fa fa-headset mr-2"></i>
                        ¿Necesitas ayuda inmediata?
                    </h3>
                    <p class="text-gray-300 mb-4">Contáctanos por WhatsApp o llámanos</p>
                    <a href="https://wa.me/51987654321" target="_blank" class="block bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg text-center mb-3 transition-colors">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                    <a href="tel:+51987654321" class="block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg text-center transition-colors">
                        <i class="fa fa-phone mr-2"></i>Llamar Ahora
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/default.php';
?>
