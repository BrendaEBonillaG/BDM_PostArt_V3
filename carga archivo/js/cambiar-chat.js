document.addEventListener('DOMContentLoaded', function() {
    const usuarios = document.querySelectorAll('.usuario-chat');
    const userNameSpan = document.getElementById('nombre-usuario');
    const userAvatarImg = document.getElementById('foto-usuario');
    const mainContent = document.querySelector('.main-content');
    const btnVendedor = document.getElementById('btnOpcionesVendedor');

    window.idChatActivo = null;

    usuarios.forEach(function(usuario) {
        usuario.addEventListener('click', function(e) {
            e.preventDefault();

            const nombreUsuario = usuario.getAttribute('data-nombre');
            const fotoUsuario = usuario.getAttribute('data-foto');
            const idChat = usuario.getAttribute('data-id-chat');

            // ✅ Seguridad para evitar errores
            if (userNameSpan) userNameSpan.textContent = nombreUsuario;
            if (userAvatarImg) userAvatarImg.src = fotoUsuario;

            window.idChatActivo = parseInt(idChat);

            if (mainContent) mainContent.style.display = 'flex';

            if (btnVendedor) {
                btnVendedor.classList.remove('d-none');
            }

            obtenerMensajes();
        });
    });
});
