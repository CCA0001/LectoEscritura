document.addEventListener(

    "DOMContentLoaded",

    () => {

        cargarNiveles();
    }
);

async function cargarNiveles(){

    try{

        const response =
            await fetch(

                "../controllers/NivelProgresoController.php?accion=listarNiveles"
            );

        const data =
            await response.json();

        if(!data.success){

            alert(
                data.mensaje
            );

            return;
        }

        renderizarTabla(
            data.niveles
        );

    }catch(error){

        console.error(error);

        alert(
            "Error cargando niveles"
        );
    }
}

function renderizarTabla(niveles){

    const contenedor =
        document.getElementById(
            "contenedorNiveles"
        );

    if(niveles.length === 0){

        contenedor.innerHTML = `

            <p>
                No hay niveles registrados
            </p>
        `;

        return;
    }

    contenedor.innerHTML = `

        <table>

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Nombre</th>
                    <th>XP</th>
                    <th>Descripción</th>
                    <th>Admin Responsable</th>
                    <th>Estado</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

                ${niveles.map(

                    nivel => `

                        <tr>

                            <td>
                                ${nivel.ID}
                            </td>

                            <td>
                                ${nivel.nombre}
                            </td>

                            <td>
                                ${nivel.puntos_requeridos}
                            </td>

                            <td>
                                ${nivel.descripcion}
                            </td>

                            <td>
                                ${nivel.ID_adminResponsable}
                            </td>                            
                            <td>
                                ${nivel.estado}
                            </td>



                            <td>

                                <button
                                    onclick="
                                        invertirEstado(
                                            ${nivel.ID},
                                            '${nivel.estado}'
                                        )
                                    "
                                >
                                    Cambiar estado
                                </button>

                                <button
                                    onclick="
                                        actualizarNivel(
                                            ${nivel.ID}
                                        )
                                    "
                                >
                                    Actualizar
                                </button>

                            </td>

                        </tr>
                    `
                ).join("")}

            </tbody>

        </table>
    `;
}

function actualizarNivel(id){

    window.location.href =

        `../views/actualizarNivel.php?id=${id}`;
}

async function invertirEstado(
    id,
    estado
){

    try{

        const response =
            await fetch(

                "../controllers/NivelProgresoController.php?accion=invertirEstadoNivel",

                {

                    method:"POST",

                    headers:{
                        "Content-Type":"application/json"
                    },

                    body:
                        JSON.stringify({

                            ID:id,

                            estado:estado
                        })
                }
            );

        const data =
            await response.json();

        if(!data.success){

            cargarNiveles();

        }else{

            alert(
                data.mensaje
            );
        }

    }catch(error){

        console.error(error);
    }
}
