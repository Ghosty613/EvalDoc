const loginForm = document.getElementById('loginForm');
const userInput = document.getElementById('userText');
const passwordInput = document.getElementById('passwordText');

function validarCampos(usuario, password) {
    if (!usuario || !password) {
        alert("Por favor, completa todos los campos.");
        return false;
    }
    if (usuario.length < 3) {
        alert("El nombre de usuario debe tener al menos 3 caracteres.");
        return false;
    } else if(usuario.length > 50){
        alert("El longitud del nombre de usuario debe ser igual o menor a 50 caracteres");
        return false;
    }
    if (password.length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres.");
        return false;
    } else if(password.length > 100){
        alert("La longitud contraseña debe ser igual o menor a 100 caracteres");
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
        const response = await fetch('login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos),
        });

        if (response.ok) {
            const mensaje = await response.text();
            alert(mensaje);
        } else {
            alert('Hubo un error en el servidor.');
        }
    } catch (error) {
        console.error('Error en la solicitud:', error);
        alert('No se pudo conectar con el servidor.');
    }
}

loginForm.addEventListener('submit', manejarSubmit);