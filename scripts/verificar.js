function verificarTipo() {
    const tipoUsuario = localStorage.getItem('tipoUsuario');

    if (tipoUsuario === "alumno") {
        window.location.href = "anuncio.html";
    } else {
        window.location.href = "acceso_denegado.html";
    }
}