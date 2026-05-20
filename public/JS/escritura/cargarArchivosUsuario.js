fetch(
    "../controllers/ModuloEscrituraController.php?accion=obtenerArchivosUsuario"
)
.then(res => res.json())
.then(data => {

    const contenedor =
        document.getElementById("contenedorArchivos");

    if(
        !data.archivos ||
        data.archivos.length === 0
    ){
        contenedor.innerHTML = `
            <p class="vacio">
                Aún no has subido ningún archivo.
            </p>
        `;

        return;
    }

    let html = `
        <div class="tabla-responsive">

            <table class="tabla-archivos">

                <thead>

                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Dificultad</th>
                        <th>Fecha</th>
                        <th>Archivo</th>
                        <th>Puntaje</th>
                        <th>IA</th>
                        <th>Retroalimentación</th>
                        <th>Estado</th>
                    </tr>

                </thead>

                <tbody>
    `;

    data.archivos.forEach(archivo => {

        html += `

            <tr>

                <td>
                    ${archivo.nombre_archivo}
                </td>

                <td>
                    ${archivo.tipo_nombre ?? '—'}
                </td>

                <td>
                    ${archivo.dificultad_nombre ?? '—'}
                </td>

                <td>
                    ${archivo.fecha_subida}
                </td>

                <td>

                    <a
                        href="${archivo.url_archivo}"
                        target="_blank"
                        class="btn-ver"
                    >
                        Ver PDF
                    </a>

                </td>

                <td>

                    ${
                        archivo.puntaje_promedio
                        ?
                        `<span class="puntaje">
                            ${archivo.puntaje_promedio}/10
                        </span>`
                        :
                        `<span class="pendiente">
                            Pendiente
                        </span>`
                    }

                </td>

                <td>

                    ${
                        !archivo.puntaje_promedio
                        ?
                        `
                        <button
                            class="btn-ia"
                            onclick="evaluarArchivo(${archivo.ID})"
                        >
                            🤖 Evaluar
                        </button>
                        `
                        :
                        `
                        <span class="completado">
                            Evaluado
                        </span>
                        `
                    }

                </td>

                <td>

                    ${
                        archivo.retroalimentacion
                        ?
                        `
                        <button
                            class="retro-tooltip"
                            onclick="mostrarRetro(
                                \`${archivo.retroalimentacion}\`
                            )"
                        >
                            Ver feedback
                        </button>
                        `
                        :
                        `<span class="sin-retro">—</span>`
                    }

                </td>

                <td>

                    ${
                        archivo.puntaje_promedio
                        ?
                        `<span class="completado">
                            Evaluado
                        </span>`
                        :
                        `<span class="espera">
                            En revisión
                        </span>`
                    }

                </td>

            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>
    `;

    contenedor.innerHTML = html;
});