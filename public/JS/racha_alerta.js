window.onload = function() {
    const helper = document.getElementById('data-helper');
    if (!helper) return;

    const info = helper.dataset;

    if (info.mensajeRacha && info.mensajeRacha.trim() !== "") {
        Swal.fire({
            title: '¡Hola De Nuevo!',
            html: `
                <div style="font-size: 1.1rem; margin: 10px 0;">
                    ${info.mensajeRacha}
                </div>
            `,
            icon: 'success',
            confirmButtonText: '¡A estudiar!',
            confirmButtonColor: '#4a8d6e',
            allowOutsideClick: false
        });
    }

    if (info.logroNombre && info.logroNombre.trim() !== "") {
        Swal.fire({
            title: '🏆 ¡NUEVO LOGRO! 🏆',
            html: `
                <div style="padding: 10px;">
                    <span style="font-size: 3rem;">🏅</span>
                    <div style="font-size: 1.2rem; margin: 10px 0;">
                        Has desbloqueado:<br>
                        <strong style="color: #d4af37;">${info.logroNombre}</strong>
                    </div>
                    <div style="color: #4a8d6e; font-weight: bold;">
                        +${info.logroXp} XP extra
                    </div>
                </div>
            `,
            icon: 'success',
            confirmButtonText: '¡Genial!',
            confirmButtonColor: '#4a8d6e',
            allowOutsideClick: false
        });
    }
};