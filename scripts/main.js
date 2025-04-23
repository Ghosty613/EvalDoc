const nombreUsuario = localStorage.getItem('nombreUsuario');
if (nombreUsuario) {
    const nombreUsuarioElement = document.getElementById('nombreUsuario');
    if (nombreUsuarioElement) {
        nombreUsuarioElement.textContent = nombreUsuario;
    } else {
        console.error('El elemento con ID "nombreUsuario" no se encontró en el DOM.');
    }
}

const slides = document.querySelector('.carousel-slides');
if (slides) {
    const slideElements = document.querySelectorAll('.slide');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    const indicators = document.querySelectorAll('.indicator');

    let currentIndex = 0;
    const totalSlides = slideElements.length;

    function updateCarousel() {
        slides.style.transform = `translateX(-${currentIndex * 100}%)`;
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentIndex);
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateCarousel();
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        updateCarousel();
    }

    function goToSlide(index) {
        currentIndex = index;
        updateCarousel();
    }

    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => goToSlide(index));
    });
}