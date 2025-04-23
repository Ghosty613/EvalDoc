window.addEventListener('DOMContentLoaded', () => {
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
                    const response = await fetch('register.php', {
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