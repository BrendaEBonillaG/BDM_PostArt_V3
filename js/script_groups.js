const cards = document.querySelectorAll('.card');
const nextButton = document.getElementById('next');
const prevButton = document.getElementById('prev');
let currentIndex = 0;

function updateCarousel() {
    cards.forEach((card, index) => {
        card.style.display = (index >= currentIndex && index < currentIndex + 3) ? 'flex' : 'none';
    });
}

nextButton.addEventListener('click', () => {
    if (currentIndex < cards.length - 3) {
        currentIndex++;
        updateCarousel();
    }
});

prevButton.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        updateCarousel();
    }
});

// Inicializa el carrusel
updateCarousel();