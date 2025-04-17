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
                    console.log('Nombre recibido del servidor:', result.nombre);
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

    // Mostrar el nombre del usuario en la página principal
    const nombreUsuario = localStorage.getItem('nombreUsuario');
    console.log('Nombre de usuario:', nombreUsuario);
    if (nombreUsuario) {
        const nombreUsuarioElement = document.getElementById('nombreUsuario');
        if (nombreUsuarioElement) {
            nombreUsuarioElement.textContent = nombreUsuario;
        } else {
            console.error('El elemento con ID "nombreUsuario" no se encontró en el DOM.');
        }
    }
});