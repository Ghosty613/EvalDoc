document.getElementById("form_evaluacion").addEventListener("submit", async function(event) {
    event.preventDefault(); // Evitar el envío por defecto del formulario

    const preguntas = 30;
    let suma = 0;

    const mapaCalificacion = {
        "De acuerdo": 5,
        "Parcialmente de acuerdo": 4,
        "Neutral": 3,
        "Parcialmente en desacuerdo": 2,
        "En desacuerdo": 1
    };

    // Iterar sobre todas las preguntas y calcular la calificación
    for (let i = 1; i <= preguntas; i++) {
        const opciones = document.getElementsByName(`pregunta${i}`);

        for (const opcion of opciones) {
            if (opcion.checked) {
                suma += mapaCalificacion[opcion.value] || 0;
            }
        }
    }

    // Calcular el promedio
    const promedio = suma / preguntas;

    // Obtener el nombre de usuario desde localStorage
    const nombreUsuario = localStorage.getItem('nombreUsuario');
    const tokenDocente = localStorage.getItem('tokenDocente');

    // Crear un objeto FormData para enviar los datos al servidor
    const formData = new FormData();
    formData.append('nombreUsuario', nombreUsuario); // Enviar el nombre del usuario
    formData.append('calificacion', promedio); // Enviar la calificación
    formData.append('token_docente', tokenDocente); // Enviar el token del docente

    // Enviar los datos al servidor
    try {
        const response = await fetch('guardar_evaluacion.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            alert(data.message); 
            window.location.href = "docentes.html";
        } else {
            alert(data.message); 
        }
    } catch (error) {
        console.error('Error:', error);
        alert("Hubo un error al procesar la evaluación.");
    }
});