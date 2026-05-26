document.addEventListener(

    "DOMContentLoaded",

    async () => {

        try{

            const response =
                await fetch(

                    "../controllers/UsuarioController.php?accion=obtenerLogrosUsuario"
                );

            const data =
                await response.json();

            if(!data.success){

                alert(
                    "Error cargando logros"
                );

                return;
            }


            renderizarDesbloqueados(
                data.desbloqueados
            );

            renderizarTodos(
                data.todos,
                data.desbloqueados
            );

        }catch(error){

            console.error(error);
        }

        try{

            const response =
                await fetch(

                    "../controllers/UsuarioController.php?accion=obtenerPerfil"
                );

            const data =
                await response.json();

            if(!data.success){

                alert(
                    "Error cargando logros"
                );

                return;
            }

            cargarPerfil(
                data.usuario
            );        
        }catch(error){
            console.error(error);
        }
    }
);

function cargarPerfil(usuario){

    document.getElementById(
        "nombreUsuario"
    ).textContent =
        `👤 ${usuario.nombre}`;

    document.getElementById(
        "nivelUsuario"
    ).textContent =
        `🏅 ${usuario.nivel}`;

    document.getElementById(
        "rachaUsuario"
    ).textContent =
        `🔥 ${usuario.racha} días`;

    document.getElementById(
        "xpUsuario"
    ).textContent =
        `⭐ ${usuario.xp} XP`;
}

function renderizarDesbloqueados(logros){

    const contenedor =
        document.getElementById(
            "logrosDesbloqueados"
        );

    if(logros.length === 0){

        contenedor.innerHTML = `
            <p>
                Aún no tienes logros desbloqueados
            </p>
        `;

        return;
    }

    contenedor.innerHTML =
        logros.map(logro => `

            <div class="achievement-card unlocked">

                <div class="lock-badge">
                    ✅
                </div>

                <div class="achievement-icon">
                    🏆
                </div>

                <h3>
                    ${logro.nombre}
                </h3>

                <p>
                    ${logro.descripcion}
                </p>

                <div class="xp-badge">
                    +${logro.recompensa_xp} XP
                </div>

                <div class="date-unlocked">
                    🗓️
                    ${logro.fecha_desbloqueo}
                </div>

            </div>

        `).join("");
}

function renderizarTodos(
    todos,
    desbloqueados
){

    const contenedor =
        document.getElementById(
            "todosLosLogros"
        );

    const idsDesbloqueados =
        desbloqueados.map(
            l => l.ID
        );

    contenedor.innerHTML =
        todos.map(logro => {

            const desbloqueado =
                idsDesbloqueados.includes(
                    logro.ID
                );

            return `

                <div class="achievement-card ${desbloqueado ? 'unlocked' : 'locked'}">

                    <div class="lock-badge">
                        ${desbloqueado ? '✅' : '🔒'}
                    </div>

                    <div class="achievement-icon">
                        🏆
                    </div>

                    <h3>
                        ${logro.nombre}
                    </h3>

                    <p>
                        ${logro.descripcion}
                    </p>

                    <div class="xp-badge">
                        +${logro.recompensa_xp} XP
                    </div>

                </div>

            `;
        }).join("");
}