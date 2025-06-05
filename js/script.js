const AvatarMenu1 = document.getElementById('botonAvatarMenujs');
const AvatarMenu2 = document.getElementById('menuAvatarjs');
const Filtroblr1 = document.getElementById('pantallaBlurjs');

// Evento para mostrar y animar AvatarMenu1 al hacer clic en AvatarMenu2
AvatarMenu2.addEventListener('click', function() {
    if (AvatarMenu1.classList.contains('oculto')) {
        AvatarMenu1.classList.remove('oculto');
        AvatarMenu1.style.display = 'flex';
        void AvatarMenu1.offsetWidth;
        AvatarMenu1.classList.add('agrandado');
    }
});

// Evento para ocultar AvatarMenu1 y mostrar AvatarMenu2 al hacer clic en AvatarMenu1
AvatarMenu1.addEventListener('click', function() {
    this.classList.remove('agrandado');
    void this.offsetWidth;
    this.classList.add('agrandado');

    setTimeout(() => {
        this.classList.add('oculto');
        this.style.display = 'none';
        AvatarMenu2.classList.remove('oculto');
        AvatarMenu2.style.display = 'flex';
        Filtroblr1.classList.remove('oculto');
        Filtroblr1.style.display = 'flex';
    }, 100);
});

// Evento para ocultar AvatarMenu2 al hacer clic en AvatarMenu2
AvatarMenu2.addEventListener('click', function() {
    this.classList.remove('deslizar');
    void this.offsetWidth;
    this.classList.add('deslizar');

    setTimeout(() => {
        this.classList.add('oculto');
        this.style.display = 'none';
        Filtroblr1.classList.add('oculto');
        Filtroblr1.style.display = 'none';
    }, 100);
});

// Evento para los íconos de usuario
const Event1menu = document.querySelectorAll('.bx.bxs-user');

Event1menu.forEach(function(icon) {
    icon.addEventListener('click', function(event) {
        event.stopPropagation(); // Evita que el clic se propague
        window.location.href = './User.php'; // Redirige a usuario.html
    });
});

// Evento para los íconos de favoritos
const Event2menu = document.querySelectorAll('.bx.bxs-hot.menu-favoritos');

Event2menu.forEach(function(icon) { 
    icon.addEventListener('click', function(event) {
        event.stopPropagation(); // Evita que el clic se propague
        window.location.href = 'favoritos.html'; // Redirige a favoritos.html
    });
});

// Evento para los íconos de añadir
const Event3menu = document.querySelectorAll('.bx.bxs-add-to-queue');

Event3menu.forEach(function(icon) { 
    icon.addEventListener('click', function(event) {
        event.stopPropagation(); // Evita que el clic se propague
        window.location.href = 'Subir_post.php'; // Redirige a Publicar.php
    });
});

// Evento para los íconos de configuración
const Event4menu = document.querySelectorAll('.bx.bxs-cog');

Event4menu.forEach(function(icon) { 
    icon.addEventListener('click', function(event) {
        event.stopPropagation(); // Evita que el clic se propague
        window.location.href = 'donaciones_dash.html'; // Redirige a config.html
    });
});

// Evento para los íconos de cerrar sesión
const Event5menu = document.querySelectorAll('.bx.bx-log-out');

Event5menu.forEach(function(icon) { 
    icon.addEventListener('click', function(event) {
        event.stopPropagation(); // Evita que el clic se propague
        document.getElementById('confirmationModal').style.display = 'flex'; // Muestra la ventana modal
        
        // Cambia el color de fondo a --black-panel
        document.documentElement.style.setProperty('--blue-panel', 'var(--black-panel)');
    });
});

// Manejo del botón "No"
document.getElementById('noBtn').addEventListener('click', function() {
    document.getElementById('confirmationModal').style.display = 'none'; // Oculta la ventana modal
    
    // Restaura el color de fondo a --blue-panel
    document.documentElement.style.setProperty('--blue-panel', '#05a8ee5d'); // O usa 'var(--blue-panel)' si lo prefieres
    document.querySelector('.pantalla-blur').style.setProperty('mix-blend-mode', 'color');
});

// Manejo del botón "Yes"
document.getElementById('yesBtn').addEventListener('click', function() {
    // Aquí puedes agregar la lógica para cerrar sesión
    alert('Logged out!'); // Ejemplo de acción al hacer clic en "Yes"
    document.getElementById('confirmationModal').style.display = 'none'; // Oculta la ventana modal
    
    // Redirige a login.html o realiza la acción de cierre de sesión
    window.location.href = 'login.html'; // Redirige a login.html
});

// Evento para el botón del menú de perfil
$(".menu-perfil-btn").click(function(event) {
    event.stopPropagation();
    $(this).toggleClass("active");
    $(".btns-menu-profile").toggleClass("open");
});

// Evento para ocultar el menú de perfil al hacer clic fuera de él
$(document).click(function() {
    $(".btns-menu-profile").removeClass("open");
    $(".menu-perfil-btn").removeClass("active");
});


// Perfil card
const showSocial = (toggleCard, socialCard) => {
    const toggle = document.getElementById(toggleCard),
          social = document.getElementById(socialCard);
    let hideTimeout;

    toggle.addEventListener('mouseover', () => {
        clearTimeout(hideTimeout); // Limpiar el temporizador si está activo
        social.classList.add('animation');
    });

    social.addEventListener('mouseover', () => {
        clearTimeout(hideTimeout); // Limpiar el temporizador si está activo
        social.classList.add('animation');
    });

    const handleMouseLeave = (event) => {
        const relatedTarget = event.relatedTarget; // Elemento relacionado al salir
        if (!toggle.contains(relatedTarget) && !social.contains(relatedTarget)) {
            hideTimeout = setTimeout(() => {
                social.classList.remove('animation');
            }, 100); // Ajusta el tiempo según sea necesario
        }
    };

    toggle.addEventListener('mouseleave', handleMouseLeave);
    social.addEventListener('mouseleave', handleMouseLeave);
}

showSocial('perfil-info-toggle', 'perfil-info-social');

const container = document.querySelector('.container-perfil-info');
const menu = document.querySelector('.menu-add-perfil');

// Suponiendo que tienes esta variable de biografía
var biografia = "Agregar"; // Aquí debería ir el valor real de la biografía

// Cuando el perfil se carga o se edita, verificar si es "Agregar"
document.addEventListener("DOMContentLoaded", function() {
    if (biografia === "Agregar") {
        // Si el valor de biografía es "Agregar", mostramos "Agregar biografía" en la ventana
        document.querySelector(".details-perfil-info h4").innerText = "Agregar biografía";
    } else {
        // Si ya hay una biografía, mostramos el texto actual
        document.querySelector(".details-perfil-info h4").innerText = biografia;
    }
});


// Al pasar el mouse sobre el contenedor, se oculta el menú
container.addEventListener('mouseover', () => {
    menu.style.opacity = '0';
});

// Al salir el mouse del contenedor, se muestra el menú nuevamente
container.addEventListener('mouseout', () => {
    menu.style.opacity = '1'; // o '1' si quieres que sea completamente visible
});


// Obtener el modal para editar datos de usuario


var modalEditUser = document.getElementById("editUser");

// Obtener el botón que abre el modal
var btnEditUser = document.getElementById("editButton");

// Obtener el elemento <span> que cierra el modal
var spanEditUser = document.getElementsByClassName("close")[0];

// Cuando el usuario hace clic en el botón, se abre el modal
btnEditUser.onclick = function() {
    modalEditUser.style.display = "block";
}

// Cuando el usuario hace clic en <span> (x), se cierra el modal
spanEditUser.onclick = function() {
    modalEditUser.style.display = "none";
}

// Cuando el usuario hace clic en cualquier parte fuera del modal, se cierra
window.onclick = function(event) {
    if (event.target == modalEditUser) {
        modalEditUser.style.display = "none";
    }
}

// Manejar el envío del formulario
document.getElementById("editForm").onsubmit = function(event) {
    event.preventDefault(); // Evitar el envío del formulario
    // Aquí puedes agregar la lógica para guardar la información
    alert("Información guardada");
    modalEditUser.style.display = "none"; // Cerrar el modal
}