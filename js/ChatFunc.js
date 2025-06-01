// Guardar el chat activo globalmente
window.getChatActivo = function () {
    return window.idChatActivo || null;
};

function obtenerMensajes() {
    const idChat = getChatActivo();
    if (!idChat) return;

    fetch('PHP/Obtener_Mensajes.php?id_chat=' + idChat)
        .then(res => res.text())
        .then(html => {
            const chatContainer = document.querySelector('.chat-box');
            chatContainer.innerHTML = html;
            chatContainer.scrollTop = chatContainer.scrollHeight;
        })
        .catch(err => {
            console.error('Error al obtener mensajes:', err);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    const inputMensaje = document.getElementById('message-input');
    const btnEnviar = document.getElementById('send-btn');
    const nombreUsuario = document.getElementById('nombre-usuario');
    const fotoUsuario = document.getElementById('foto-usuario');
    const chatBox = document.querySelector('.chat-box');

    // Escuchar clic en cada usuario para activar el chat y actualizar nombre/foto
    const usuarios = document.querySelectorAll('.usuario-chat');
    usuarios.forEach(usuario => {
        usuario.addEventListener('click', () => {
            const nombre = usuario.dataset.nombre;
            const foto = usuario.dataset.foto;
            const idChat = usuario.dataset.idChat;

            window.idChatActivo = idChat;

            // Mostrar nombre y foto del usuario en el header
            if (nombreUsuario) nombreUsuario.textContent = nombre;
            if (fotoUsuario) fotoUsuario.src = foto;

            obtenerMensajes();
        });
    });

    // Enviar mensaje
    if (inputMensaje && btnEnviar) {
        btnEnviar.addEventListener('click', function () {
            const mensaje = inputMensaje.value.trim();
            const idChat = getChatActivo();

            if (!idChat) {
                console.warn('No hay chat activo definido.');
                return;
            }

            if (mensaje === '') {
                console.warn('El mensaje está vacío.');
                return;
            }

            fetch('PHP/Insertar_Mensaje.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'mensaje=' + encodeURIComponent(mensaje) + '&id_chat=' + idChat
            })
            .then(res => res.text())
            .then(response => {
                inputMensaje.value = '';
                obtenerMensajes();
            })
            .catch(err => {
                console.error('Error al enviar mensaje:', err);
            });
        });
    }

    // Recargar mensajes si estás en el fondo del chat
    setInterval(() => {
        if (!chatBox) return;
        const isBottom = chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 5;
        if (isBottom) {
            obtenerMensajes();
        }
    }, 1000);
});
