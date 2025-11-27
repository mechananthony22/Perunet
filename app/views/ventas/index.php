<?php
$title = "Confirmar Compra";
$style = "venta";
ob_start();
?>
<main class="min-h-screen flex flex-col items-center justify-center bg-gray-50">
    <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg p-8 mt-8">
        <h2 class="text-2xl font-bold text-red-700 mb-6 flex items-center gap-2">
            <i class="fa fa-user text-black"></i> Datos del Cliente
        </h2>
        <form id="formCliente" class="space-y-4">
            <input type="hidden" name="id" id="usuario_id" value="<?= htmlspecialchars(isset(
                                                                        $usuario['id_us']
                                                                    ) ? $usuario['id_us'] : '') ?>">
            <div>
                <label for="usuario_nombre" class="block text-gray-700 font-semibold mb-1">Nombres:</label>
                <input id="usuario_nombre" type="text" name="nombre" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" value="<?= htmlspecialchars(isset($usuario['nombre']) ? $usuario['nombre'] : '') ?>" required>
            </div>
            <div>
                <label for="usuario_apellidos" class="block text-gray-700 font-semibold mb-1">Apellidos:</label>
                <input id="usuario_apellidos" type="text" name="apellidos" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" value="<?= htmlspecialchars(isset($usuario['apellidos']) ? $usuario['apellidos'] : '') ?>" required>
            </div>
            <div>
                <label for="usuario_correo" class="block text-gray-700 font-semibold mb-1">Correo electrónico:</label>
                <input id="usuario_correo" type="email" name="correo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" value="<?= htmlspecialchars(isset($usuario['correo']) ? $usuario['correo'] : '') ?>" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="usuario_dni" class="block text-gray-700 font-semibold mb-1">DNI:</label>
                    <input id="usuario_dni" type="text" name="dni" maxlength="8" pattern="\d{8}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" value="<?= htmlspecialchars(isset($usuario['dni']) ? $usuario['dni'] : '') ?>" required>
                </div>
                <div>
                    <label for="usuario_telefono" class="block text-gray-700 font-semibold mb-1">Teléfono:</label>
                    <input id="usuario_telefono" type="tel" name="telefono" maxlength="9" pattern="\d{9}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" value="<?= htmlspecialchars(isset($usuario['telefono']) ? $usuario['telefono'] : '') ?>" required>
                </div>
            </div>
        </form>
        <div class="flex justify-end mt-8">
            <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition" onclick="irPaso(2)">Siguiente</button>
        </div>
    </div>
    <!-- PASO 2 -->
    <div id="paso2" class="w-full max-w-3xl bg-white rounded-xl shadow-lg p-8 mt-8 hidden">
        <h2 class="text-2xl font-bold text-red-700 mb-6 flex items-center gap-2">
            <i class="fa fa-truck text-black"></i> Tipo de Entrega
        </h2>
        <div class="flex flex-col md:flex-row gap-6 mb-6">
            <button id="domicilio-button" type="button" class="flex-1 bg-red-200 hover:bg-red-100 text-red-700 font-bold py-3 px-6 rounded-lg transition" onclick="seleccionarEntrega('domicilio')">Domicilio</button>
            <button id="tienda-button" type="button" class="flex-1 bg-gray-200 hover:bg-gray-100 text-gray-700 font-bold py-3 px-6 rounded-lg transition" onclick="seleccionarEntrega('tienda')">Recojo en Tienda</button>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Método de Pago</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <?php foreach ($metodos as $m): ?>
                    <div class="pago-item bg-gray-100 rounded-lg p-4 flex flex-col items-center cursor-pointer hover:bg-red-100 transition"
                        onclick="seleccionarMetodo(<?= $m['id_met'] ?>, '<?= $m['tipo'] ?>')"
                        data-id="<?= $m['id_met'] ?>"
                        data-tipo="<?= $m['tipo'] ?>">
                        <img src="/perunet/public/img/EMPRESA/PAGOS/<?= strtoupper($m['nombre']) ?>.png" alt="<?= $m['tipo'] ?>" class="h-10 mb-2">
                        <p class="text-xs font-semibold text-gray-700"><?= strtoupper($m['nombre']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="metodo_pago_id" id="metodo_pago_id">
            <div id="datos-pago"></div>
        </div>
        
        <!-- DOMICILIO -->
        <div id="domicilio-section" class="mb-6 hidden">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Dirección de Entrega</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="departamento" class="block text-gray-700 font-semibold mb-1">Departamento:</label>
                        <input id="departamento" type="text" name="departamento" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label for="provincia" class="block text-gray-700 font-semibold mb-1">Provincia:</label>
                        <input id="provincia" type="text" name="provincia" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label for="distrito" class="block text-gray-700 font-semibold mb-1">Distrito:</label>
                        <input id="distrito" type="text" name="distrito" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label for="calle" class="block text-gray-700 font-semibold mb-1">Calle:</label>
                        <input id="calle" type="text" name="calle" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label for="numero" class="block text-gray-700 font-semibold mb-1">Número:</label>
                        <input id="numero" type="text" name="numero" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label for="piso" class="block text-gray-700 font-semibold mb-1">Piso:</label>
                        <input id="piso" type="text" name="piso" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="referencia" class="block text-gray-700 font-semibold mb-1">Referencia:</label>
                        <input id="referencia" type="text" name="referencia" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>
            </div>
        </div>
        <!-- TIENDA -->
        <div id="tienda-section" class="mb-6 hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Sedes disponibles</h3>
            <div class="space-y-2">
                <?php foreach ($sucursales as $s): ?>
                    <label class="flex items-center gap-2 bg-gray-100 rounded-lg p-3 cursor-pointer hover:bg-red-100 transition">
                        <input type="radio" name="sucursal" value="<?= $s['id_sucur'] ?>">
                        <span class="text-gray-700">📍 <?= $s['nombre'] ?> - <?= $s['direccion'] ?> - <?= $s['ciudad'] ?> - <?= $s['departamento'] ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="flex justify-between mt-8">
            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition" onclick="irPaso(1)">Regresar</button>
            <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition" onclick="irPaso(3)">Siguiente</button>
        </div>
    </div>
    <!-- PASO 3 -->
    <div id="paso3" class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-8 mt-8 hidden flex flex-col items-center">
        <h2 class="text-2xl font-bold text-green-700 mb-6 flex items-center gap-2">
            <i class="fa fa-check-circle text-green-600"></i> Confirmación
        </h2>
        <p class="text-lg text-gray-700 mb-8">¿Deseas confirmar la compra?</p>
        <div class="flex justify-center gap-4">
            <!-- <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition" onclick="irPaso(2)">Regresar</button> -->
            <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition" id="comprar">Confirmar y Comprar</button>
            <button class="bg-gray-100 hover:bg-gray-200 text-red-600 font-bold py-2 px-6 rounded-lg transition" onclick="window.location.href='/perunet/'">Cancelar Todo</button>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/default.php';
?>

<script type="module" src="/perunet/public/js/venta.js"></script>