document.addEventListener(

    "DOMContentLoaded",

    () => {

        cargarLogros();
    }
);

async function cargarLogros(){

    try{

        const response =
            await fetch(

                "../controllers/LogroController.php?accion=listarLogros"
            );

        const data =
            await response.json();

        renderizarTabla(
            data.logros
        );

    }catch(error){

        console.error(error);
    }
}

function renderizarTabla(logros){

    const contenedor =
        document.getElementById(
            "contenedorTablaLogros"
        );

    if(logros.length === 0){

        contenedor.innerHTML = `
            <div class="tabla-vacia">
                <p>
                    No hay logros registrados aún.
                </p>
            </div>
        `;

        return;
    }

    let html = `

        <table>

            <thead>

                <tr>

                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>XP</th>
                    <th>Estado</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>
    `;

    logros.forEach(logro => {

        html += `

            <tr>

                <td class="td-id">
                    ${logro.ID}
                </td>

                <td class="td-nombre">
                    ${logro.nombre}
                </td>

                <td class="td-desc">
                    ${logro.descripcion}
                </td>

                <td class="td-xp">
                    +${logro.recompensa_xp} XP
                </td>

                <td class="td-estado">
                    ${logro.estado}
                </td>

                <td>

                    <button
                        class="btn-estado"
                        onclick="invertirEstado(${logro.ID},'${logro.estado}')"
                    >
                        Cambiar estado
                    </button>

                    <a
                        href="../controllers/LogroController.php?accion=mostrarVistaActualizar&id=${logro.ID}"
                        class="btn-estado"
                    >
                        Actualizar
                    </a>

                </td>

            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
    `;

    contenedor.innerHTML =
        html;

    document.getElementById(
        "totalLogros"
    ).innerHTML = `
        Total:
        <strong>
            ${logros.length}
        </strong>
    `;
}

async function invertirEstado(id, estado){

    if(
        !confirm(
            "¿Invertir estado del logro?"
        )
    ){
        return;
    }

    try{

        const response =
            await fetch(

                "../controllers/LogroController.php?accion=invertirEstado",

                {
                    method: "POST",

                    headers:{
                        "Content-Type":"application/json"
                    },

                    body: JSON.stringify({
                        ID:id,
                        estadoActual: estado
                    })
                }
            );

        const data =
            await response.json();

        if(data.success){

            cargarLogros();
        }

    }catch(error){

        console.error(error);
    }
}