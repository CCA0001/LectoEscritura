document.addEventListener(
    "DOMContentLoaded",
    cargarPerfilUsuario
);

async function cargarPerfilUsuario()
{
    try {

        const response =
            await fetch(
                "../controllers/UsuarioController.php?accion=obtenerPerfil"
            );

        const data =
            await response.json();

        if(!data.success){
            return;
        }

        const usuario =
            data.usuario;

        document.getElementById(
            "rangoUsuario"
        ).textContent =
            usuario.nivel;

        document.getElementById(
            "rangoPanel"
        ).textContent =
            usuario.nivel;

        document.getElementById(
            "nombreUsuario"
        ).textContent =
            usuario.nombre;

        document.getElementById(
            "tituloBienvenida"
        ).textContent =
            `Bienvenido, ${usuario.nombre}`;

        document.getElementById(
            "rachaUsuario"
        ).textContent =
            `${usuario.racha} días`;

        document.getElementById(
            "rachaBadge"
        ).textContent =
            usuario.racha;

        document.getElementById(
            "xpUsuario"
        ).textContent =
            `${usuario.xp} XP`;

        document.getElementById(
            "logrosUsuario"
        ).textContent =
            `${usuario.logros.length} desbloqueados`;

    } catch(error){

        console.error(error);

    }
}