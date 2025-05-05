const tipoUsuario = localStorage.getItem('tipoUsuario');

function verificarEvaluacion() {
    if (tipoUsuario === "alumno") {
        window.location.href = "docentes.html";
    } else {
        window.location.href = "acceso_denegado.html";
    }
}

function verificarEstadisticas(){
    if (tipoUsuario === "alumno") {
        window.location.href = "estadisticas.html";
    } else if(tipoUsuario === "docente") {
        window.location.href = "estadisticas_docente.html";
    } else if(tipoUsuario === "administrador") {
        window.location.href = "estadisticas_admin.html";
    }
}