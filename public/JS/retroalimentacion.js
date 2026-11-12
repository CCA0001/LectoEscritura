function mostrarRetro(texto) {

    // La retroalimentación viene de la IA y se inserta en el DOM:
    // sin escapar, un <script> dentro del texto se ejecutaría en el
    // navegador del estudiante.
    document.getElementById('retroTexto').textContent = texto;

    document.getElementById('modalRetro').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalRetro').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('modalRetro');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}