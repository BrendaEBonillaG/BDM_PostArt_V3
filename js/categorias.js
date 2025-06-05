document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("selectCategoria");

    if (!select) return;

    fetch("PHP/obtener_categorias.php")
        .then(response => response.text())
        .then(data => {
            select.innerHTML = data;
        })
        .catch(error => {
            console.error("Error al cargar categorías:", error);
        });
});
