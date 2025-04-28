function obtenerDocentes() {
    const token = document.getElementById('tokenInput').value;
    localStorage.setItem('tokenDocente', token);
    console.log('Token guardado en localStorage:', localStorage.getItem('tokenDocente'));

    if (!token.trim()) {
        alert('Por favor, ingrese un token válido.');
        return;
    }

    fetch('obtener_docentes.php?token=' + encodeURIComponent(token))
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la petición');
            }
            return response.json();
        })
        .then(data => {
            const tabla = document.getElementById('tablaDocentes');
            const tbody = tabla.querySelector('tbody');
            tbody.innerHTML = ''; 

            if (data.length > 0) {
                tabla.style.display = 'table';
                data.forEach(docente => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td id="nombre-docente">${docente}</td> 
                        <td>
                            <button onclick="evaluarDocente('${encodeURIComponent(docente)}')" id="boton-docente">Evaluar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                });
            } else {
                tabla.style.display = 'none';
                alert('No se encontraron docentes con ese token.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema al cargar los docentes.');
        });
}

function evaluarDocente(nombre) {
    window.location.href = 'formulario.html?nombre=' + encodeURIComponent(nombre);
}
