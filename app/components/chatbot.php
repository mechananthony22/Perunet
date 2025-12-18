<!-- Chatbot Widget -->
<link rel="stylesheet" href="/perunet/public/css/chatbot.css?v=<?= time() ?>">

<!-- Botón flotante del chatbot -->
<button id="chatbot-toggle" class="chatbot-float-button" aria-label="Abrir chat de ayuda">
    <i class="fas fa-comment-dots"></i>
    <span>¿Necesitas ayuda?</span>
</button>

<!-- Contenedor del chatbot -->
<div id="chatbot-container" class="chatbot-container">
    <div id="chatbot-header" class="chatbot-header">
        <div class="chatbot-header-content">
            <div class="chatbot-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div>
                <h3>Asistente PeruNet</h3>
                <p class="chatbot-status">En línea</p>
            </div>
        </div>
        <button id="close-chatbot-btn" class="chatbot-close-btn" aria-label="Cerrar chat">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div id="chatbot-body" class="chatbot-body">
        <!-- Mensaje de bienvenida inicial -->
        <div class="chatbot-message bot-message">
            <div class="message-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">
                <p>¡Hola! 👋 Soy el asistente virtual de PeruNet. ¿En qué puedo ayudarte hoy?</p>
            </div>
        </div>
        
        <!-- Opciones rápidas -->
        <div class="quick-options" id="quick-options">
            <p class="quick-options-title">Preguntas frecuentes:</p>
            <button class="quick-option-btn" data-question="¿Qué productos venden?">
                <i class="fas fa-laptop"></i> Productos
            </button>
            <button class="quick-option-btn" data-question="¿Qué métodos de pago aceptan?">
                <i class="fas fa-credit-card"></i> Pagos
            </button>
            <button class="quick-option-btn" data-question="¿Hacen entregas?">
                <i class="fas fa-shipping-fast"></i> Envíos
            </button>
            <button class="quick-option-btn" data-question="¿Dónde están ubicados?">
                <i class="fas fa-map-marker-alt"></i> Ubicación
            </button>
        </div>
    </div>
    
    <div id="chatbot-input" class="chatbot-input">
        <input 
            type="text" 
            id="user-input" 
            class="chatbot-input-field"
            placeholder="Escribe tu pregunta aquí..."
            autocomplete="off"
        >
        <button id="send-btn" class="chatbot-send-btn" aria-label="Enviar mensaje">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script src="/perunet/public/js/chatbot.js"></script>
