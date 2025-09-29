/* Contenedor principal del chatbot */
.chatbot-container {
    position: fixed;
    bottom: 80px; 
    left: 20px;
    width: 360px;  /* ✅ MÁS ANCHO */
    height: 420px; /* un poquito más alto */
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); 
    z-index: 9999; 
    display: flex; 
    flex-direction: column;
}

/* Mensajes */
.message {
    padding: 8px 12px;
    margin-bottom: 8px;
    border-radius: 15px;
    clear: both; 
    max-width: 95%;   /* ✅ AHORA USAN MÁS ESPACIO */
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.bot-message {
    background-color: #E8F5E9; 
    color: #333;
    float: left;
    border-bottom-left-radius: 3px;
}

.user-message {
    background-color: #DCF8C6; 
    color: #333;
    float: right;
    border-bottom-right-radius: 3px;
}
