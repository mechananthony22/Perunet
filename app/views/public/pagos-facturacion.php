<?php
$title = "Facturación y Pagos Digitales - Perunet";
ob_start();
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-16">
    <div class="container mx-auto px-4 max-w-5xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Facturación Electrónica y Pagos Digitales
            </h1>
            <p class="text-gray-600 text-lg">
                Métodos de pago seguros y facturación 100% digital
            </p>
        </div>

        <!-- Facturación Electrónica Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa fa-file-invoice text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Facturación Electrónica</h2>
                    <p class="text-gray-600">Integrada con SUNAT</p>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="font-semibold text-lg text-gray-800">Tipos de comprobantes:</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fa fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Boleta de Venta Electrónica</p>
                                <p class="text-sm text-gray-600">Para personas naturales</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Factura Electrónica</p>
                                <p class="text-sm text-gray-600">Para empresas con RUC</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Nota de Crédito/Débito</p>
                                <p class="text-sm text-gray-600">Para anulaciones y modificaciones</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="space-y-4">
                    <h3 class="font-semibold text-lg text-gray-800">Beneficios:</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fa fa-bolt text-yellow-500 mt-1"></i>
                            <p class="text-gray-700">Envío inmediato a tu correo</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa fa-shield-alt text-blue-500 mt-1"></i>
                            <p class="text-gray-700">100% válido ante SUNAT</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa fa-cloud text-green-500 mt-1"></i>
                            <p class="text-gray-700">Almacenamiento digital seguro</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa fa-download text-purple-500 mt-1"></i>
                            <p class="text-gray-700">Descarga en PDF y XML</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Métodos de Pago Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fa fa-credit-card text-green-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Métodos de Pago Disponibles</h2>
                    <p class="text-gray-600">Paga de la forma que prefieras</p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Tarjetas -->
                <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 transition-all">
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa fa-credit-card text-blue-600 text-3xl"></i>
                        </div>
                        <h3 class="font-bold text-lg text-gray-800">Tarjetas</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            Visa / Mastercard
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            American Express
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            Débito y Crédito
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            Pago en cuotas
                        </li>
                    </ul>
                </div>

                <!-- Pagos Digitales -->
                <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-purple-500 transition-all">
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa fa-mobile-alt text-purple-600 text-3xl"></i>
                        </div>
                        <h3 class="font-bold text-lg text-gray-800">Pagos Digitales</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            Yape
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            Plin
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            Transferencia bancaria
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            POS en tienda
                        </li>
                    </ul>
                </div>

                <!-- Efectivo -->
                <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-green-500 transition-all">
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa fa-money-bill-wave text-green-600 text-3xl"></i>
                        </div>
                        <h3 class="font-bold text-lg text-gray-800">Efectivo</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            En tienda física
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            Contra entrega
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            Depósito bancario
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa fa-check text-green-500"></i>
                            Agentes autorizados
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Seguridad Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg p-8 text-white">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-shield-alt text-white text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Seguridad Garantizada</h2>
                <p class="text-blue-100">Tus pagos están 100% protegidos</p>
            </div>

            <div class="grid md:grid-cols-4 gap-6 text-center">
                <div>
                    <i class="fa fa-lock text-4xl mb-3 text-blue-200"></i>
                    <p class="text-sm font-semibold">Encriptación SSL</p>
                </div>
                <div>
                    <i class="fa fa-check-circle text-4xl mb-3 text-blue-200"></i>
                    <p class="text-sm font-semibold">Verificación 3D Secure</p>
                </div>
                <div>
                    <i class="fa fa-user-shield text-4xl mb-3 text-blue-200"></i>
                    <p class="text-sm font-semibold">Datos Protegidos</p>
                </div>
                <div>
                    <i class="fa fa-award text-4xl mb-3 text-blue-200"></i>
                    <p class="text-sm font-semibold">Certificaciones PCI</p>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Preguntas Frecuentes</h2>
            
            <div class="space-y-4">
                <details class="group border-b border-gray-200 pb-4">
                    <summary class="flex justify-between items-center cursor-pointer font-semibold text-gray-800 hover:text-blue-600">
                        ¿Cuándo recibiré mi factura electrónica?
                        <i class="fa fa-chevron-down group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">
                        Tu factura electrónica se envía automáticamente a tu correo dentro de las 24 horas posteriores a tu compra. También puedes descargarla desde tu perfil en cualquier momento.
                    </p>
                </details>

                <details class="group border-b border-gray-200 pb-4">
                    <summary class="flex justify-between items-center cursor-pointer font-semibold text-gray-800 hover:text-blue-600">
                        ¿Puedo solicitar factura si compré con boleta?
                        <i class="fa fa-chevron-down group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">
                        Sí, puedes solicitar el cambio de boleta a factura dentro de los 7 días calendario posteriores a tu compra. Contáctanos al 978997728 o servicioalcliente@perunet.pe.
                    </p>
                </details>

                <details class="group border-b border-gray-200 pb-4">
                    <summary class="flex justify-between items-center cursor-pointer font-semibold text-gray-800 hover:text-blue-600">
                        ¿Es seguro pagar con tarjeta en línea?
                        <i class="fa fa-chevron-down group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">
                        Totalmente seguro. Utilizamos encriptación SSL de 256 bits y cumplimos con los estándares PCI DSS. Tus datos nunca son almacenados en nuestros servidores.
                    </p>
                </details>

                <details class="group pb-4">
                    <summary class="flex justify-between items-center cursor-pointer font-semibold text-gray-800 hover:text-blue-600">
                        ¿Puedo pagar en cuotas?
                        <i class="fa fa-chevron-down group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">
                        Sí, aceptamos pagos en cuotas con tarjetas de crédito participantes. Las opciones de cuotas se mostrarán al momento del pago dependiendo de tu banco emisor.
                    </p>
                </details>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="mt-8 text-center">
            <a href="/perunet/productos" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-full transition-all shadow-lg hover:shadow-xl">
                <i class="fa fa-shopping-cart mr-3"></i>
                Comenzar a Comprar
                <i class="fa fa-arrow-right ml-3"></i>
            </a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/default.php';
?>
