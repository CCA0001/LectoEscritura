function mostrarRetro(texto) {
    document.getElementById('retroTexto').innerHTML = texto;
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