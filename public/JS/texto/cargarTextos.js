document.addEventListener(

    "DOMContentLoaded",

    () => {

        cargarTextos();
    }
);

async function cargarTextos(){

    try{

        const response =
            await fetch(

                "../controllers/TextoController.php?accion=listarTextos"
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
            data.textos
        );

    }catch(error){

        console.error(error);

        alert(
            "Error cargando textos"
        );
    }
}

function renderizarTabla(textos){

    const contenedor =
        document.getElementById(
            "contenedorTablaTextos"
        );

    if(textos.length === 0){

        contenedor.innerHTML = `

            <p>
                No hay textos registrados
            </p>
        `;

        return;
    }

    contenedor.innerHTML = `

        <table>

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Dificultad</th>
                    <th>Tipo de Texto</th>
                    <th>Titulo</th>
                    <th>Contenido</th>
                    <th>Fuente</th>
                    <th>Admin Responsable</th>
                    <th>Fecha Registro</th>
                    <th>Estado</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

                ${textos.map(

                    texto => `

                        <tr>

                            <td>
                                ${texto.ID}
                            </td>

                            <td>
                                ${texto.ID_dificultad}
                            </td>

                            <td>
                                ${texto.ID_tipoTexto}
                            </td>

                            <td>
                                ${escaparHTML(texto.titulo)}
                            </td>

                            <td>
                                ${escaparHTML(texto.contenido)}
                            </td>                            
                            <td>
                                ${escaparHTML(texto.fuente)}
                            </td>
                            <td>
                                ${texto.ID_adminResponsable}
                            </td>
                            <td>
                                ${texto.fecha_registro}
                            </td>
                            <td>
                                ${escaparHTML(texto.estado)}
                            </td>                            

                            <td>

                                <button
                                    onclick="
                                        invertirEstado(
                                            ${texto.ID},
                                            '${escaparAtributo(texto.estado)}'
                                        )
                                    "
                                >
                                    Cambiar estado
                                </button>

                            </td>

                        </tr>
                    `
                ).join("")}

            </tbody>

        </table>
    `;
}
async function invertirEstado(
    id,
    estado
){

    try{

        const response =
            await fetch(

                "../controllers/TextoController.php?accion=invertirEstadoTexto",

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

            cargarTextos();

        }else{

            alert(
                data.mensaje
            );
        }

    }catch(error){

        console.error(error);
    }
}