    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const previewImage = document.getElementById('previewImage');
    const formPublicacion = document.getElementById('formPublicacion');
    
    const categoriaSelect = document.getElementById('categoriaSelect');
    const tituloInput = document.getElementById('tituloInput');
    const contenidoInput = document.getElementById('contenidoInput');
    const tipoSelect = document.getElementById('tipoSelect');

    // Campos ocultos dentro del form
    const hiddenCategoria = document.getElementById('hiddenCategoria');
    const hiddenTitulo = document.getElementById('hiddenTitulo');
    const hiddenContenido = document.getElementById('hiddenContenido');
    const hiddenTipo = document.getElementById('hiddenTipo');

    // Abrir selector al click en dropZone
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag & drop eventos
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');

        if (e.dataTransfer.files.length) {
            const file = e.dataTransfer.files[0];
            if (file.type === "image/png" || file.type === "image/jpeg") {
                fileInput.files = e.dataTransfer.files; // asignar archivo al input
                showPreview(file);
            } else {
                alert("Solo archivos PNG o JPG permitidos");
            }
        }
    });

    // Mostrar preview de imagen
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            const file = fileInput.files[0];
            showPreview(file);
        }
    });

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }

    // Botón Crear envía el form con validación
    document.getElementById('btnCreate').addEventListener('click', (e) => {
        e.preventDefault();

        if (!fileInput.files.length) {
            alert('Por favor sube una imagen arrastrándola o seleccionándola.');
            return;
        }
        if (!categoriaSelect.value) {
            alert('Selecciona una categoría.');
            return;
        }
        if (!tituloInput.value.trim()) {
            alert('Escribe un título.');
            return;
        }
        if (!tipoSelect.value) {
            alert('Selecciona un tipo de publicación.');
            return;
        }

        // Pasar valores visibles a inputs ocultos del form real
        hiddenCategoria.value = categoriaSelect.value;
        hiddenTitulo.value = tituloInput.value.trim();
        hiddenContenido.value = contenidoInput.value.trim();
        hiddenTipo.value = tipoSelect.value;

        formPublicacion.submit();
    });
