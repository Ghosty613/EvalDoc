function cargarDocentes() {
    const token = document.getElementById('tokenInput').value;
    fetch('obtener_docentes.php?token=' + encodeURIComponent(token))
        .then(response => response.json())
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
                            <button onclick="evaluarDocente('${docente}')" id="boton-docente">Evaluar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                });
            } else {
                tabla.style.display = 'none';
                alert('No se encontraron docentes con ese token.');
            }
        });
}

function evaluarDocente(nombre) {
    window.location.href = 'evaluar.php?nombre=' + encodeURIComponent(nombre);
}
