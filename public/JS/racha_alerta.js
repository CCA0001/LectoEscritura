/**
 * Alerta de racha y de logro desbloqueado.
 *
 * Los datos llegan de dos formas posibles:
 *   1. Un elemento #data-helper con data-mensaje-racha / data-logro-nombre /
 *      data-logro-xp (HTML ya renderizado por el servidor).
 *   2. sessionStorage["alertaRacha"], que deja el login
 *      (public/JS/Auth/obtenerDatosLoginUsuario.js) para que la alerta se
 *      vea ya dentro del panel y no en la pantalla de login.
 *
 * Se borra de sessionStorage al mostrarla para que no reaparezca al recargar.
 */
document.addEventListener('DOMContentLoaded', function() {

    const info = obtenerDatos();

    if(!info) {
        return;
    }

    // Un nombre de logro viene de la BD y se pinta con innerHTML: se escapa.
    const logroNombre = typeof escaparHTML === "function"
        ? escaparHTML(info.logroNombre || "")
        : (info.logroNombre || "");

    const mensajeRacha = typeof escaparHTML === "function"
        ? escaparHTML(info.mensajeRacha || "")
        : (info.mensajeRacha || "");

    if(mensajeRacha && mensajeRacha.trim() !== "") {

        Swal.fire({
            title: '¡Hola de nuevo!',
            html: `
                <div style="font-size: 1.1rem; margin: 10px 0;">
                    ${mensajeRacha}
                </div>
            `,
            icon: 'success',
            confirmButtonText: '¡A estudiar!',
            confirmButtonColor: '#4a8d6e',
            allowOutsideClick: false
        });
    }

    if(logroNombre && logroNombre.trim() !== "") {

        Swal.fire({
            title: '🏆 ¡NUEVO LOGRO! 🏆',
            html: `
                <div style="padding: 10px;">
                    <span style="font-size: 3rem;">🏅</span>
                    <div style="font-size: 1.2rem; margin: 10px 0;">
                        Has desbloqueado:<br>
                        <strong style="color: #d4af37;">${logroNombre}</strong>
                    </div>
                    <div style="color: #4a8d6e; font-weight: bold;">
                        +${parseInt(info.logroXp, 10) || 0} XP extra
                    </div>
                </div>
            `,
            icon: 'success',
            confirmButtonText: '¡Genial!',
            confirmButtonColor: '#4a8d6e',
            allowOutsideClick: false
        });
    }
});

function obtenerDatos() {

    const helper = document.getElementById('data-helper');

    if(helper) {

        return {
            mensajeRacha: helper.dataset.mensajeRacha,
            logroNombre: helper.dataset.logroNombre,
            logroXp: helper.dataset.logroXp
        };
    }

    let guardado = null;

    try {
        guardado = sessionStorage.getItem("alertaRacha");
    } catch(error) {
        return null;
    }

    if(!guardado) {
        return null;
    }

    try {
        sessionStorage.removeItem("alertaRacha");
    } catch(error) {
        // Sin sessionStorage (modo privado) la alerta simplemente no sale.
    }

    try {
        return JSON.parse(guardado);
    } catch(error) {
        return null;
    }
}
