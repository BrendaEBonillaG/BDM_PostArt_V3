// Esperamos a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function() {
    // Obtener los elementos del DOM
    const messageInput = document.getElementById("message-input");
    const sendButton = document.getElementById("send-btn");
    const chatBox = document.querySelector(".chat-box");
  
    // Función para agregar un nuevo mensaje al chat
    function sendMessage() {
      const messageText = messageInput.value.trim(); // Obtener el texto del mensaje
  
      if (messageText !== "") {
        // Crear un contenedor para el mensaje enviado
        const messageSent = document.createElement("div");
        messageSent.classList.add("message-sent");
  
        // Crear el contenido de la foto y el texto del mensaje
        const photoSent = document.createElement("img");
        photoSent.classList.add("photo-sent");
        photoSent.src = "https://i.pinimg.com/474x/27/96/cb/2796cbfdd164a96a581cc272a313548b.jpg"; // Foto de usuario
  
        const textSent = document.createElement("div");
        textSent.classList.add("text-sent");
        textSent.innerHTML = `<p>${messageText}</p>`; // Mostrar el mensaje
  
        // Añadir el contenido a la estructura
        messageSent.appendChild(photoSent);
        messageSent.appendChild(textSent);
  
        // Añadir el mensaje a la caja de chat
        chatBox.appendChild(messageSent);
  
        // Limpiar el campo de entrada de texto
        messageInput.value = "";
        
        // Asegurarnos de que el chat se desplace hacia abajo para ver el mensaje enviado
        chatBox.scrollTop = chatBox.scrollHeight;
      }
    }
  
    // Añadir el evento de clic al botón de enviar
    sendButton.addEventListener("click", sendMessage);
  
    // Opcional: permitir enviar el mensaje presionando Enter
    messageInput.addEventListener("keypress", function(e) {
      if (e.key === "Enter") {
        sendMessage();
      }
    });
  });
  