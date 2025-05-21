// Variables para almacenar el estado seleccionado
let selectedRole = null; // 'Visitante' o 'Artista'

// Obtener los elementos de los botones
const leftButton = document.querySelector('.container-selector-L');
const rightButton = document.querySelector('.container-selector-R');
const continueButton = document.querySelector('.submitButton');

// Obtener los elementos del estado-selector
const leftSelector = document.querySelector('.estado-selector-L');
const rightSelector = document.querySelector('.estado-selector-R');

// Función para manejar la selección de roles
function toggleSelection(role) {
  // Reiniciar estilos de selección
  leftSelector.classList.remove('selected');
  rightSelector.classList.remove('selected');

  // Cambiar el estado según el rol seleccionado
  if (role === 'Visitante') {
    leftSelector.classList.add('selected');
    selectedRole = 'Visitante';
  } else if (role === 'Artista') {
    rightSelector.classList.add('selected');
    selectedRole = 'Artista';
  }
}

// Manejar clic en cada botón de selección
leftButton.addEventListener('click', () => {
  toggleSelection('Visitante');
});

rightButton.addEventListener('click', () => {
  toggleSelection('Artista');
});

// Manejar clic en el botón Continuar
continueButton.addEventListener('click', () => {
  if (selectedRole) {
    alert(`Has seleccionado: ${selectedRole}`);
    // Aquí puedes redirigir según el rol seleccionado si es necesario
    // Por ejemplo:
    // window.location.href = selectedRole === 'Visitante' ? 'Visitante.html' : 'Artista.html';
  } else {
    alert('Por favor selecciona una opción antes de continuar.');
  }
});