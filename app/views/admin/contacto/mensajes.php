<!DOCTYPE html>
<html lang="es">

<?php
$title = "Mensajes de Contacto";
include __DIR__ . '/../../../components/adminHead.php';
?>

<body class="bg-gray-50 min-h-screen">
    <?php include __DIR__ . '/../../../components/adminNavBar.php'; ?>
    <?php include __DIR__ . '/../../../components/adminMenuNav.php'; ?>

    <main class="p-4 md:ml-64">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Mensajes de Contacto</h1>
        </div>

        <div class="bg-white rounded-xl shadow-md p-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-red-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Nombre</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Teléfono</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Correo</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Mensaje</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($mensajes)): ?>
                        <?php foreach ($mensajes as $m): ?>
                            <tr class="hover:bg-red-50 transition">
                                <td class="px-4 py-2 text-gray-700"><?= $m['id_contacto'] ?></td>
                                <td class="px-4 py-2 text-gray-700"><?= $m['nombre'] ?></td>
                                <td class="px-4 py-2 text-gray-700"><?= $m['telefono'] ?></td>
                                <td class="px-4 py-2 text-gray-700"><?= $m['correo'] ?></td>
                                <td class="px-4 py-2 text-gray-700"><?= $m['mensaje'] ?></td>
                                <td class="px-4 py-2 text-gray-500 text-sm"><?= $m['fecha_creacion'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400">No hay mensajes de contacto.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
