<?php
$title = "Perunet | Contacto";
ob_start();
?>
<div class="w-full max-w-5xl mx-auto py-10 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white rounded-xl shadow-lg p-8">
        <!-- Información de contacto -->
        <div class="space-y-6">
            <h2 class="text-2xl font-bold text-red-700 mb-2 flex items-center gap-2">
                <i class="fa fa-store text-black"></i> Visita Nuestra Tienda
            </h2>
            <p class="text-gray-700">📍 AV PEDRO RUIZ GALLO NRO. 920 INT. 879 CERCADO DE CHICLAYO</p>
            <p class="text-gray-700">📍 AV PEDRO RUIZ 920 INT. 649</p>
            <h2 class="text-2xl font-bold text-red-700 mt-6 mb-2 flex items-center gap-2">
                <i class="fa fa-phone text-black"></i> Central de telefónica
            </h2>
            <p class="text-gray-700">📞 978997728</p>
            <p class="text-gray-700">📞 959175668</p>
            <p class="text-gray-700">📞 965941380</p>
            <h2 class="text-2xl font-bold text-red-700 mt-6 mb-2 flex items-center gap-2">
                <i class="fa fa-envelope text-black"></i> Correos Corporativos
            </h2>
            <p class="text-gray-700">📧 servicioalcliente@perunet.pe</p>
            <p class="text-gray-700">📧 ventas@perunet.pe</p>
        </div>
        <!-- Formulario de contacto -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa fa-paper-plane text-red-600"></i> Envíanos un mensaje
            </h2>
            <form class="space-y-4">
                <input type="text" placeholder="Nombres y Apellidos" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                <input type="tel" placeholder="Teléfono/celular" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                <input type="email" placeholder="Correo electrónico" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                <textarea placeholder="Mensaje" rows="4" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="captcha" required>
                    <label for="captcha" class="text-gray-600">No soy un robot</label>
                </div>
                <small class="block text-gray-400 mb-2">reCAPTCHA Privacidad • Condiciones</small>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition">ENVIAR MENSAJE</button>
            </form>
        </div>
    </div>
</div>
<script src="/perunet/public/js/funciones.js" defer></script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/default.php';
?>