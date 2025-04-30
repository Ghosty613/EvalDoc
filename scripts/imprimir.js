document.getElementById('imprimir').addEventListener('click', () => {
    const nombreUsuario = localStorage.getItem('nombreUsuario');
    if (!nombreUsuario) {
        alert('No se encontró el nombre del usuario en localStorage');
        return;
    }

    // Abrir nueva ventana para generar e imprimir el certificado
    window.open(`certificado.php?usuario=${encodeURIComponent(nombreUsuario)}`, '_blank');
});