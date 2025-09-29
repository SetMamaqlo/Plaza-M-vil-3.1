// Función global para desplazar la vista al último mensaje
function scrollToBottom() {
    const chatbox = document.getElementById("chatbot-body");
    // 💥 SOLUCIÓN DE SCROLL: Esto fuerza la vista al final del contenedor.
    // Esto hace visible la barra de desplazamiento cuando hay contenido extra.
    chatbox.scrollTop = chatbox.scrollHeight;
}

// Función para enviar mensaje (corregida para usar scrollToBottom)
function sendMessage() {
    const input = document.getElementById("chatbot-input");
    const userMessage = input.value.trim();

    if (!userMessage) return;

    appendUserMessage(userMessage);
    input.value = "";

    fetch("/Plaza-M-vil-3.1/controller/backend.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mensaje: userMessage }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Error HTTP: " + response.status);
            }
            return response.json();
        })
        .then((data) => {
            let botReply = data.respuesta || "🤖 Lo siento, no entendí tu mensaje.";
            appendBotMessage(botReply);
        })
        .catch((error) => {
            console.error("Error en chatbot:", error);
            // Agrega información de la respuesta
            appendBotMessage("❌ Error al conectar con el servidor: " + error.message);
        });
}

// Mostrar mensajes del usuario en el chat (CORREGIDA)
function appendUserMessage(message) {
    const chatbox = document.getElementById("chatbot-body");
    const msgDiv = document.createElement("div");

    msgDiv.classList.add("message", "user-message");

    // 💥 CORREGIDO: Usar innerHTML para interpretar emojis y posible formato
    msgDiv.innerHTML = message;
    
    chatbox.appendChild(msgDiv);
    
    // 💥 LLAMADA CLAVE: Asegura que el chat baje
    scrollToBottom();
}

// Mostrar mensajes del bot en el chat (CORREGIDA)
function appendBotMessage(message) {
    const chatbox = document.getElementById("chatbot-body");
    const msgDiv = document.createElement("div");

    msgDiv.classList.add("message", "bot-message");

    // 💥 CORREGIDO: Usar innerHTML para interpretar emojis y posible formato
    msgDiv.innerHTML = message;
    
    chatbox.appendChild(msgDiv);
    
    // 💥 LLAMADA CLAVE: Asegura que el chat baje
    scrollToBottom();
}

// Enviar mensaje con Enter
document.getElementById("chatbot-input").addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
        sendMessage();
    }
});

// Enviar mensaje con botón
document.getElementById("chatbot-send-btn").addEventListener("click", sendMessage);

// Cerrar chatbot con el botón ✖ (CORREGIDA)
document.getElementById("chatbot-close-btn").addEventListener("click", () => {
    document.getElementById("chatbot-container").style.display = "none";
});

// Arranca oculto (CORREGIDA: Usar 'flex' para ser consistente)
document.getElementById("chatbot-container").style.display = "none";

// ------------------------------------------------------------------
// 🛑 IMPORTANTE: Si quieres que el chat se desplace al final al cargar 
// (para ver el mensaje de bienvenida), llama a la función aquí:
document.addEventListener('DOMContentLoaded', scrollToBottom);
// ------------------------------------------------------------------