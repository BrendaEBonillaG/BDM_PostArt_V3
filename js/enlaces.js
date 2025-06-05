// Selecciona todas las cartas de usuario
const cardImagePosts = document.querySelectorAll('.card-image-post');

cardImagePosts.forEach(cardImagePost => {
    // Selecciona el elemento de la imagen dentro de la carta
    const cardImage = cardImagePost.querySelector('.imag');
    const tagArtistInfo = cardImagePost.querySelector('.tag-artist-info');

    // Agrega evento de clic a la imagen
    cardImage.addEventListener('click', function() {
        window.location.href = 'Picture.php'; // Redirige a la página de la imagen
    });

    // Agrega evento de clic a la información del artista
    tagArtistInfo.addEventListener('click', function() {
        window.location.href = 'Perfil.php'; // Redirige a la página del perfil
    });
});

// Selecciona todos los botones de "paw"
const buttons = document.querySelectorAll('.paw-button');

buttons.forEach(button => {
    const icon = button.querySelector('i');

    // Variable para controlar el estado del color
    let isRed = false;

    // Agrega un evento de clic
    button.addEventListener('click', function() {
        // Cambia el color del icono
        if (isRed) {
            icon.style.color = 'rgba(0, 0, 0, 0.26)'; // Color original
        } else {
            icon.style.color = '#bb5353'; // Color rojo
        }
        // Cambia el estado
        isRed = !isRed;
    });
});

