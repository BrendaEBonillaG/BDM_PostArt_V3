document.getElementById('imageUpload').addEventListener('change', function() {
    const fileInput = document.getElementById('imageUpload');
    const userImage = document.getElementById('userImage');

    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const cropModal = document.getElementById('cropModal');
            const imageToCrop = document.getElementById('imageToCrop');
            const confirmCropButton = document.getElementById('confirmCropButton');
            const closeModalButton = document.getElementById('closeModal');

            // Mostrar el modal
            cropModal.style.display = 'block';
            imageToCrop.src = e.target.result;

            // Inicializar Cropper.js
            const cropper = new Cropper(imageToCrop, {
                aspectRatio: 1,
                viewMode: 1,
            });

            // Confirmar recorte
            confirmCropButton.onclick = function() {
                // Cerrar el modal
                cropModal.style.display = 'none'; // Oculta el modal
            
                // Esperar antes de iniciar la animación
                setTimeout(function() {
                    // Agregar la clase de animación al div que contiene la imagen
                    const avatarPerfil = document.querySelector('.avatar-perfil');
                    avatarPerfil.classList.add('jump');
            
                    // Esperar 1 segundo para la animación antes de cambiar la imagen
                    setTimeout(function() {
                        const canvas = cropper.getCroppedCanvas({
                            width: 320,
                            height: 320,
                        });
                        userImage.src = canvas.toDataURL(); // Cambia la imagen del avatar
                        avatarPerfil.classList.remove('jump'); // Eliminar la clase de animación
                        cropper.destroy(); // Destruye el cropper
                    }, 500); // 1000 ms = 1 segundo para la animación
            
                }, 500); 
            };

            // Cerrar el modal
            closeModalButton.onclick = function() {
                cropModal.style.display = 'none'; // Oculta el modal
                cropper.destroy(); // Destruye el cropper
            };

            // Cerrar el modal al hacer clic fuera del contenido
            window.onclick = function(event) {
                if (event.target === cropModal) {
                    cropModal.style.display = 'none'; // Oculta el modal
                    cropper.destroy(); // Destruye el cropper
                }
            };
        }
        reader.readAsDataURL(fileInput.files[0]); // Lee la imagen como URL
    }
});

