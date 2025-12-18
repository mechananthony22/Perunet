<?php
$title = "Catálogo de Servicios - Perunet";
$style = "builder";
ob_start();
?>

<style>
.service-card {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 1.5rem;
    padding: 2rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.service-card:hover::before {
    opacity: 1;
}

.service-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 50px rgba(59, 130, 246, 0.25);
}

.service-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    transition: all 0.4s ease;
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
}

.service-card:hover .service-icon {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 12px 30px rgba(59, 130, 246, 0.5);
}

.service-icon i {
    font-size: 2rem;
    color: white;
}

.gradient-text {
    background: linear-gradient(135deg, #1e293b 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.service-btn {
    margin-top: auto;
    padding-top: 1rem;
}
</style>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50 py-16">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 gradient-text">
                Catálogo de Servicios
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Descubre todos los servicios que PeruNet tiene para ti
            </p>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
            
            <!-- 1. Venta Presencial -->
            <a href="/perunet/sedes" class="service-card group">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="service-icon">
                        <i class="fa fa-store"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 text-center">Venta Presencial</h2>
                    <p class="text-gray-300 text-center mb-4 text-sm">
                        Visita nuestras tiendas físicas en Chiclayo
                    </p>
                    <ul class="text-gray-300 space-y-2 mb-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Atención personalizada</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Ver productos en físico</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Retiro inmediato</span>
                        </li>
                    </ul>
                    <div class="text-center service-btn">
                        <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition-all text-sm group-hover:shadow-lg">
                            Ver Ubicación <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

            <!-- 2. Venta B2C/B2B -->
            <a href="/perunet/productos" class="service-card group">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="service-icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 text-center">Venta al por Menor y Mayor</h2>
                    <p class="text-gray-300 text-center mb-4 text-sm">
                        Compras para particulares y empresas
                    </p>
                    <ul class="text-gray-300 space-y-2 mb-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Precios especiales por volumen</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Facturación corporativa</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Crédito empresarial</span>
                        </li>
                    </ul>
                    <div class="text-center service-btn">
                        <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition-all text-sm group-hover:shadow-lg">
                            Ver Productos <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

            <!-- 3. Asesoría en Compras -->
            <a href="#" id="open-asesoria" class="service-card group">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="service-icon">
                        <i class="fa fa-user-tie"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 text-center">Asesoría en Compras</h2>
                    <p class="text-gray-300 text-center mb-4 text-sm">
                        Expertos te ayudan a elegir lo mejor
                    </p>
                    <ul class="text-gray-300 space-y-2 mb-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Consulta con especialistas</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Recomendaciones personalizadas</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Comparativas de productos</span>
                        </li>
                    </ul>
                    <div class="text-center service-btn">
                        <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition-all text-sm group-hover:shadow-lg">
                            Consultar <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

            <!-- 4. Facturación y Pagos -->
            <a href="/perunet/pagos-facturacion" class="service-card group">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="service-icon">
                        <i class="fa fa-file-invoice-dollar"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 text-center">Facturación y Pagos</h2>
                    <p class="text-gray-300 text-center mb-4 text-sm">
                        Métodos de pago seguros y facturación electrónica
                    </p>
                    <ul class="text-gray-300 space-y-2 mb-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Factura electrónica SUNAT</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Pagos digitales seguros</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Yape, Plin, tarjetas</span>
                        </li>
                    </ul>
                    <div class="text-center service-btn">
                        <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition-all text-sm group-hover:shadow-lg">
                            Ver Info <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

            <!-- 5. Chatbot -->
            <a href="#" id="open-chatbot-service" class="service-card group">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="service-icon">
                        <i class="fa fa-comment-dots"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 text-center">Asistente Virtual</h2>
                    <p class="text-gray-300 text-center mb-4 text-sm">
                        Respuestas instantáneas a tus preguntas
                    </p>
                    <ul class="text-gray-300 space-y-2 mb-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Disponible 24/7</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Respuestas inmediatas</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Información sobre productos</span>
                        </li>
                    </ul>
                    <div class="text-center service-btn">
                        <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition-all text-sm group-hover:shadow-lg">
                            Abrir Chat <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

            <!-- 6. Seguimiento de Pedidos -->
            <a href="/perunet/usuario/perfil" class="service-card group">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="service-icon">
                        <i class="fa fa-truck"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 text-center">Seguimiento de Pedidos</h2>
                    <p class="text-gray-300 text-center mb-4 text-sm">
                        Rastrea tus compras en tiempo real
                    </p>
                    <ul class="text-gray-300 space-y-2 mb-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Estado en tiempo real</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Historial completo</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Notificaciones de envío</span>
                        </li>
                    </ul>
                    <div class="text-center service-btn">
                        <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition-all text-sm group-hover:shadow-lg">
                            Ver Pedidos <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

            <!-- 7. Soporte Técnico -->
            <a href="/perunet/soporte" class="service-card group">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="service-icon">
                        <i class="fa fa-headset"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 text-center">Soporte Técnico</h2>
                    <p class="text-gray-300 text-center mb-4 text-sm">
                        Asistencia profesional especializada
                    </p>
                    <ul class="text-gray-300 space-y-2 mb-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Diagnóstico y reparación</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Mantenimiento preventivo</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Garantías y servicio técnico</span>
                        </li>
                    </ul>
                    <div class="text-center service-btn">
                        <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition-all text-sm group-hover:shadow-lg">
                            Solicitar <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

            <!-- 8. Armador de PC -->
            <a href="/perunet/builder/pc?step=1" class="service-card group">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="service-icon">
                        <i class="fa fa-desktop"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 text-center">Armador de PC</h2>
                    <p class="text-gray-300 text-center mb-4 text-sm">
                        Personaliza tu computadora ideal
                    </p>
                    <ul class="text-gray-300 space-y-2 mb-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Elige cada componente</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Verifica compatibilidad</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Ve el precio total en vivo</span>
                        </li>
                    </ul>
                    <div class="text-center service-btn">
                        <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition-all text-sm group-hover:shadow-lg">
                            Comenzar <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

            <!-- 9. Armador de Setup -->
            <a href="/perunet/builder/setup?step=1" class="service-card group">
                <div class="relative z-10 flex flex-col h-full">
                    <div class="service-icon">
                        <i class="fa fa-gamepad"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 text-center">Armador de Setup</h2>
                    <p class="text-gray-300 text-center mb-4 text-sm">
                        Completa tu estación gamer
                    </p>
                    <ul class="text-gray-300 space-y-2 mb-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Monitor de alta frecuencia</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Periféricos gaming</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa fa-check-circle text-blue-400 mr-2 mt-1 flex-shrink-0"></i>
                            <span>Silla y accesorios</span>
                        </li>
                    </ul>
                    <div class="text-center service-btn">
                        <span class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-full transition-all text-sm group-hover:shadow-lg">
                            Comenzar <i class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

        </div>
    </div>
</div>

<script>
    // Abrir chatbot desde el servicio
    document.addEventListener('DOMContentLoaded', function() {
        const openChatbotService = document.getElementById('open-chatbot-service');
        const openAsesoria = document.getElementById('open-asesoria');
        
        if (openChatbotService) {
            openChatbotService.addEventListener('click', function(e) {
                e.preventDefault();
                const chatbotToggle = document.getElementById('chatbot-toggle');
                if (chatbotToggle) {
                    chatbotToggle.click();
                }
            });
        }

        if (openAsesoria) {
            openAsesoria.addEventListener('click', function(e) {
                e.preventDefault();
                // Abrir chatbot con mensaje predefinido de asesoría
                const chatbotToggle = document.getElementById('chatbot-toggle');
                if (chatbotToggle) {
                    chatbotToggle.click();
                    setTimeout(() => {
                        const userInput = document.getElementById('user-input');
                        if (userInput) {
                            userInput.value = 'Necesito asesoría para comprar';
                            document.getElementById('send-btn').click();
                        }
                    }, 500);
                }
            });
        }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/default.php';
?>
