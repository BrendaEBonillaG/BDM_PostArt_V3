    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('imagen');
    const fileList = document.getElementById('filelist');

    // Prevenir el comportamiento por defecto de arrastrar y soltar
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Resaltar el área de arrastre al arrastrar un archivo
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropArea.classList.add('highlight');
    }

    function unhighlight() {
        dropArea.classList.remove('highlight');
    }

    // Manejar el evento de soltar
    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        handleFiles(files);
    }

    // Manejar archivos seleccionados
    fileInput.addEventListener('change', (e) => {
        const files = e.target.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        [...files].forEach(file => {
            const div = document.createElement('div');
            div.textContent = file.name;
            fileList.appendChild(div);
        });
    }

    // Hacer clic en el área de arrastre para abrir el selector de archivos
    dropArea.addEventListener('click', () => {
        fileInput.click();
    });

