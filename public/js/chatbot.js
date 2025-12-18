// FAQ Database - Respuestas predefinidas
const faqDatabase = {
    // Productos
    productos: {
        keywords: ['producto', 'venden', 'venta', 'ofrecen', 'laptop', 'computadora', 'pc', 'monitor', 'teclado', 'mouse', 'componente', 'hardware', 'catálogo'],
        response: '🖥️ En PeruNet vendemos una amplia variedad de productos tecnológicos:\n\n• Laptops y Computadoras\n• Componentes de PC (procesadores, motherboards, RAM, etc.)\n• Monitores y Periféricos\n• Accesorios gaming\n• Equipos de redes\n\n¿Te gustaría ver nuestro catálogo completo? 👉 <a href="/perunet/productos" class="chat-link">Ver Productos</a>'
    },
    
    // Métodos de pago
    pago: {
        keywords: ['pago', 'pagar', 'tarjeta', 'efectivo', 'yape', 'plin', 'transferencia', 'visa', 'mastercard', 'cuotas'],
        response: '💳 Aceptamos los siguientes métodos de pago:\n\n• Efectivo en tienda\n• Tarjetas de crédito/débito (Visa, Mastercard)\n• Transferencias bancarias\n• Yape / Plin\n• POS en tienda\n\nTambién ofrecemos opciones de financiamiento. ¿Necesitas más información sobre pagos?'
    },
    
    // Envíos y entregas
    envio: {
        keywords: ['envío', 'entrega', 'delivery', 'despacho', 'tiempo', 'demora', 'provincia', 'costo', 'enviar', 'llevar'],
        response: '🚚 Información sobre envíos:\n\n• Delivery en Chiclayo: 24-48 horas\n• Envíos a provincias: 3-5 días hábiles\n• Costos de envío según zona\n• Seguimiento en tiempo real\n\nPara calcular el costo exacto, puedes agregar productos al carrito y ver las opciones de envío disponibles.'
    },
    
    // Garantías
    garantia: {
        keywords: ['garantía', 'garantia', 'reparación', 'reparacion', 'defecto', 'falla', 'cambio', 'devolución', 'devolucion'],
        response: '✅ Todos nuestros productos cuentan con garantía:\n\n• Garantía del fabricante (varía según producto)\n• Soporte técnico post-venta\n• Cambios por defectos de fábrica\n• Asesoría técnica gratuita\n\nLa garantía específica depende del producto. ¿Tienes algún producto en mente?'
    },
    
    // Ubicación y sucursales
    ubicacion: {
        keywords: ['ubicación', 'ubicacion', 'dirección', 'direccion', 'sucursal', 'tienda', 'dónde', 'donde', 'están', 'encuentro', 'queda'],
        response: '📍 Estamos ubicados en:\n\n<strong>Tienda Principal:</strong>\nAV. PEDRO RUIZ GALLO NRO. 920 INT. 879\nCercado de Chiclayo\n\n<strong>Segunda Tienda:</strong>\nAV. PEDRO RUIZ 920 INT. 649\n\n👉 <a href="/perunet/public/sedes" class="chat-link">Ver mapa y horarios</a>'
    },
    
    // Horarios
    horario: {
        keywords: ['horario', 'hora', 'abierto', 'abren', 'cierran', 'atención', 'atencion', 'abre'],
        response: '🕒 Nuestro horario de atención:\n\n• Lunes a Viernes: 9:00 AM - 7:00 PM\n• Sábados: 9:00 AM - 5:00 PM\n• Domingos: Cerrado\n\n¡Te esperamos!'
    },
    
    // Configurador de PC
    configurador: {
        keywords: ['configurador', 'armar', 'personalizar', 'construir', 'pc gamer', 'setup', 'ensamblar'],
        response: '🎮 ¡Nuestro Configurador de PC es increíble!\n\nPuedes:\n• Armar tu PC a medida\n• Seleccionar componentes compatibles\n• Ver el precio total en tiempo real\n• Obtener asesoría sobre compatibilidad\n\n👉 <a href="/perunet/builder" class="chat-link">Ir al Configurador</a>'
    },
    
    // Contacto
    contacto: {
        keywords: ['contacto', 'teléfono', 'telefono', 'celular', 'whatsapp', 'correo', 'email', 'llamar', 'escribir'],
        response: '📞 Puedes contactarnos por:\n\n<strong>Teléfonos:</strong>\n• 978997728\n• 959175668\n• 965941380\n\n<strong>Correos:</strong>\n• servicioalcliente@perunet.pe\n• ventas@perunet.pe\n\n👉 <a href="/perunet/public/contacto" class="chat-link">Formulario de contacto</a>'
    },
    
    // Cotizaciones
    cotizacion: {
        keywords: ['cotización', 'cotizacion', 'presupuesto', 'precio', 'costo', 'cuanto', 'cuánto', 'vale'],
        response: '📋 ¿Necesitas una cotización?\n\nPuedes:\n1. Usar nuestro configurador de PC para armar tu equipo\n2. Agregarcualquier producto al carrito para ver el precio\n3. Contactarnos directamente para cotizaciones personalizadas\n\n¿En qué producto estás interesado?'
    },
    
    // Saludos
    saludo: {
        keywords: ['hola', 'buenos días', 'buenas tardes', 'buenas noches', 'hey', 'hi', 'hello', 'saludos'],
        response: '¡Hola! 👋 Bienvenido a PeruNet, tu tienda de tecnología de confianza. ¿En qué puedo ayudarte hoy?'
    },
    
    // Agradecimientos
    gracias: {
        keywords: ['gracias', 'thank', 'agradezco', 'excelente', 'perfecto', 'ok'],
        response: '¡De nada! 😊 Estoy aquí para ayudarte. Si tienes más preguntas, no dudes en escribirme.'
    }
};

// Estado del chatbot
let isOpen = false;
let isTyping = false;

// Elementos del DOM
let chatbotContainer, chatbotToggle, closeChatbotBtn, userInput, sendBtn, chatbotBody, quickOptions;

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar elementos
    chatbotContainer = document.getElementById('chatbot-container');
    chatbotToggle = document.getElementById('chatbot-toggle');
    closeChatbotBtn = document.getElementById('close-chatbot-btn');
    userInput = document.getElementById('user-input');
    sendBtn = document.getElementById('send-btn');
    chatbotBody = document.getElementById('chatbot-body');
    quickOptions = document.getElementById('quick-options');

    // Validar que los elementos existan
    if (!chatbotContainer || !chatbotToggle) {
        console.error('Chatbot elements not found');
        return;
    }

    // Event listeners
    chatbotToggle.addEventListener('click', toggleChatbot);
    
    if (closeChatbotBtn) {
        closeChatbotBtn.addEventListener('click', closeChatbot);
    }

    if (sendBtn && userInput) {
        sendBtn.addEventListener('click', sendMessage);
        userInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }

    // Quick options
    const quickOptionBtns = document.querySelectorAll('.quick-option-btn');
    quickOptionBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const question = this.getAttribute('data-question');
            if (question) {
                userInput.value = question;
                sendMessage();
            }
        });
    });
});

// Toggle chatbot
function toggleChatbot() {
    if (isOpen) {
        closeChatbot();
    } else {
        openChatbot();
    }
}

// Abrir chatbot
function openChatbot() {
    chatbotContainer.classList.add('active');
    chatbotToggle.classList.add('hidden');
    isOpen = true;
    
    // Focus en el input
    setTimeout(() => {
        if (userInput) userInput.focus();
    }, 300);
}

// Cerrar chatbot
function closeChatbot() {
    chatbotContainer.classList.remove('active');
    chatbotToggle.classList.remove('hidden');
    isOpen = false;
}

// Enviar mensaje
function sendMessage() {
    if (!userInput || !chatbotBody) return;
    
    const userMessage = userInput.value.trim();
    if (userMessage === '' || isTyping) return;

    // Agregar mensaje del usuario
    appendUserMessage(userMessage);
    userInput.value = '';

    // Ocultar opciones rápidas después del primer mensaje
    if (quickOptions) {
        quickOptions.style.display = 'none';
    }

    // Buscar respuesta en FAQ
    const response = findFAQResponse(userMessage);
    
    // Simular typing y responder
    showTypingIndicator();
    setTimeout(() => {
        hideTypingIndicator();
        appendBotMessage(response);
    }, 1000 + Math.random() * 500); // Delay aleatorio para parecer más natural
}

// Agregar mensaje del usuario
function appendUserMessage(message) {
    const messageElement = document.createElement('div');
    messageElement.classList.add('chatbot-message', 'user-message');
    messageElement.innerHTML = `
        <div class="message-content">
            <p>${escapeHtml(message)}</p>
        </div>
    `;
    chatbotBody.appendChild(messageElement);
    scrollToBottom();
}

// Agregar mensaje del bot
function appendBotMessage(message) {
    const messageElement = document.createElement('div');
    messageElement.classList.add('chatbot-message', 'bot-message');
    messageElement.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="message-content">
            <p>${message}</p>
        </div>
    `;
    chatbotBody.appendChild(messageElement);
    scrollToBottom();
}

// Mostrar indicador de escritura
function showTypingIndicator() {
    isTyping = true;
    const typingElement = document.createElement('div');
    typingElement.classList.add('chatbot-message', 'bot-message', 'typing-indicator');
    typingElement.id = 'typing-indicator';
    typingElement.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="message-content">
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    `;
    chatbotBody.appendChild(typingElement);
    scrollToBottom();
}

// Ocultar indicador de escritura
function hideTypingIndicator() {
    isTyping = false;
    const typingElement = document.getElementById('typing-indicator');
    if (typingElement) {
        typingElement.remove();
    }
}

// Buscar respuesta en FAQ
function findFAQResponse(message) {
    const messageLower = message.toLowerCase();
    
    // Buscar coincidencias en la base de datos FAQ
    for (const [key, faq] of Object.entries(faqDatabase)) {
        for (const keyword of faq.keywords) {
            if (messageLower.includes(keyword.toLowerCase())) {
                return faq.response;
            }
        }
    }
    
    // Respuesta por defecto si no se encuentra coincidencia
    return `No estoy seguro de cómo responder a eso. 🤔\n\nPuedes:\n• Revisar nuestras <a href="/perunet/productos" class="chat-link">Productos</a>\n• Visitar nuestra página de <a href="/perunet/public/contacto" class="chat-link">Contacto</a>\n• Llamarnos al 978997728\n\n¿Hay algo más en lo que pueda ayudarte?`;
}

// Scroll al final del chat
function scrollToBottom() {
    if (chatbotBody) {
        chatbotBody.scrollTop = chatbotBody.scrollHeight;
    }
}

// Escapar HTML para prevenir XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
