window.textosAvanzadosCargados = [];

fetch(
    "../controllers/ModuloLecturaController.php?accion=obtenerTextosDificilesAleatorios"
)

.then(res => res.json())

.then(data => {

    const lista =
        document.getElementById(
            "lista-ejercicios-avanzados"
        );

    lista.innerHTML = "";

    if(!data.textos || data.textos.length === 0){

        lista.innerHTML = `
            <p class="sin-textos">
                No hay textos avanzados disponibles.
            </p>
        `;

        return;
    }

    window.textosAvanzadosCargados =
        data.textos;

    data.textos.forEach((texto, index) => {

        let htmlPreguntas = "";

        texto.preguntas.forEach((pregunta, pi) => {

            htmlPreguntas += `

                <div class="ejercicio-pregunta">

                    <h4>
                        ${pi + 1}. ${pregunta.texto_pregunta}
                    </h4>

            `;

            pregunta.opciones.forEach(opcion => {

                htmlPreguntas += `

                    <label class="opcion-respuesta">

                        <input
                            type="radio"
                            name="ej${index}-p${pregunta.ID}"
                            value="${opcion.ID}"
                        >

                        <span>
                            ${opcion.texto_opcion}
                        </span>

                    </label>

                `;
            });

            htmlPreguntas += `
                </div>
            `;
        });

        lista.innerHTML += `

            <article class="ejercicio-avanzado">

                <div class="ejercicio-avanzado-header">

                    <span class="badge-dificultad">

                        ${texto.dificultad_nombre || "Avanzado"}

                    </span>

                    <h3>
                        ${texto.titulo}
                    </h3>

                </div>

                <div class="ejercicio-avanzado-body">

                    <p class="ejercicio-texto">
                        ${texto.contenido}
                    </p>

                    <p class="ejercicio-preguntas-label">
                        📝 Preguntas
                    </p>

                    <div class="contenedor-preguntas">

                        ${htmlPreguntas}

                    </div>

                </div>

            </article>

        `;
    });

})

.catch(error => {

    console.error(error);

    document.getElementById(
        "lista-ejercicios-avanzados"
    ).innerHTML = `

        <p class="error-carga">
            Error al cargar textos avanzados.
        </p>

    `;
});