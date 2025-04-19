document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const userInput = document.getElementById('userText');
    const passwordInput = document.getElementById('passwordText');

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
                    localStorage.setItem('nombreUsuario', result.nombre); 
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

    function validateForm() {
        const email = document.getElementById('email-register').value;
        const password = document.getElementById('password-register').value;
        const confirmPassword = document.getElementById('confirmPassword-register').value;

        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden');
            return false;
        }

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;
        if (!passwordRegex.test(password)) {
            alert('La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial');
            return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Por favor, ingresa un correo electrónico válido');
            return false;
        }

        return true;
    }

    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (validateForm()) {
                const formData = new FormData();
                formData.append('email', document.getElementById('email-register').value);
                formData.append('user', document.getElementById('user-register').value);
                formData.append('password', document.getElementById('password-register').value);

                try {
                    const response = await fetch('registro.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    alert(data.message);

                    location.reload();

                    // Redirigir al login si el registro es exitoso
                    // if (data.success) {
                    //     window.location.href = 'login.html';
                    // }
                } catch (error) {
                    alert('Error al conectar con el servidor.');
                    console.error(error);
                }
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const success = urlParams.get('success');

    if (error) {
        alert("Error: " + error);
    } else if (success) {
        alert("Éxito: " + success);
    }
});