document.addEventListener(

    "DOMContentLoaded",

    () => {

        cargarAdmins();
    }
);

async function cargarAdmins(){

    try{

        const response =
            await fetch(

                "../controllers/AdminController.php?accion=listarAdministradores"
            );

        const data =
            await response.json();

        renderizarTabla(
            data.admins
        );

    }catch(error){

        console.error(error);
    }
}

function renderizarTabla(admins){

    const contenedor =
        document.getElementById(
            "contenedorTablaAdmins"
        );

    if(admins.length === 0){

        contenedor.innerHTML = `
            <div class="tabla-vacia">
                <p>
                    No hay administradores registrados aún.
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
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Nombre de Usuario</th>
                    <th>Correo Electrónico</th>
                    <th>Estado</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>
    `;

    admins.forEach(admin => {

        html += `

            <tr>

                <td class="td-id">
                    ${admin.ID}
                </td>

                <td class="td-nombres">
                    ${escaparHTML(admin.nombres)}
                </td>

                <td class="td-apellidos">
                    ${escaparHTML(admin.apellidos)}
                </td>

                <td class="td-nombreU">
                    ${escaparHTML(admin.nombre_usuario)} 
                </td>

                <td class="td-correo">
                    ${escaparHTML(admin.correo_electronico)}
                </td>

                <td class="td-estado">
                    ${escaparHTML(admin.estado)}
                <td>

                    <button
                        class="btn-estado"
                        onclick="invertirEstado(${admin.ID},'${escaparAtributo(admin.estado)}')"
                    >
                        Cambiar estado
                    </button>

                    <a
                        href="../controllers/AdminController.php?accion=mostrarVistaActualizar&id=${admin.ID}"
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
        "totalAdmins"
    ).innerHTML = `
        Total:
        <strong>
            ${admins.length}
        </strong>
    `;
}

async function invertirEstado(id, estado){

    if(
        !confirm(
            "¿Invertir estado del administrador?"
        )
    ){
        return;
    }

    try{

        const response =
            await fetch(

                "../controllers/AdminController.php?accion=invertirEstadoAdmin",

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

            cargarAdmins();
        }

    }catch(error){

        console.error(error);
    }
}