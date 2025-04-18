document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const userInput = document.getElementById('userText');
    const passwordInput = document.getElementById('passwordText');

    // Solo ejecuta el código relacionado con el formulario si existe en el DOM
    if (loginForm) {
        function validarCampos(usuario, password) {
            if (!usuario || !password) {
                alert("Por favor, completa todos los campos.");
                return false;
            }
            return true;
        }

        async function manejarSubmit(event) {
            event.preventDefault();

            const usuario = userInput.value.trim();
            const password = passwordInput.value.trim();

            if (!validarCampos(usuario, password)) return;

            const datos = { usuario, password };

            try {
                const response = await fetch('./login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(datos),
                });

                const result = await response.json();

                console.log(result);

                if (result.success) {
                    localStorage.setItem('nombreUsuario', result.nombre); // Guardar el nombre del usuario
                    window.location.href = result.redirect;
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Error en la solicitud:', error);
                alert('No se pudo conectar con el servidor.');
            }
        }

        loginForm.addEventListener('submit', manejarSubmit);
    } else {
        console.log('No se encontró el formulario de inicio de sesión en esta página.');
    }

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
});