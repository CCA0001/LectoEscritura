fetch("../controllers/obtenerTextoFacil.php?nocache=" + Date.now())
    .then(res => res.json())
    .then(data => {

        document.getElementById("titulo").innerText = data.texto.titulo;
        document.getElementById("contenido").innerText = data.texto.contenido;

        let contenedor = document.getElementById("preguntas");

        if (data.error) {
            contenedor.innerText = "Error: " + data.error;
            return;
        }

        let htmlTotal = "";

        data.preguntas.forEach((pregunta, i) => {
            htmlTotal += `<h3>${i + 1}. ${pregunta.texto_pregunta}</h3>`;
            pregunta.opciones.forEach(opcion => {
                htmlTotal += `
                <label>
                    <input type="radio" name="pregunta${pregunta.ID}" value="${opcion.ID}">
                    ${opcion.texto_opcion}
                </label><br>
                `;
            });
        });

        document.getElementById("preguntas").innerHTML = htmlTotal;

        document.getElementById("btnVerificar").addEventListener("click", () => {
            verificarRespuestas(data, "resultado");
        });

    })
    .catch(err => {
        console.error(err);
        document.getElementById("preguntas").innerText = "No se pudo cargar el cuestionario";
    });


/* ──────────────────────────────────────────────────────────
   SECCIÓN 2 — Textos intermedios y avanzados
────────────────────────────────────────────────────────── */

let textosAvanzadosCargados = [];

fetch("../controllers/obtenerTextosAvanzados.php?nocache=" + Date.now())
    .then(res => res.json())
    .then(data => {

        const lista = document.getElementById("lista-ejercicios-avanzados");

        if (!data.textos || data.textos.length === 0) {
            lista.innerHTML = `
                <p style="text-align:center; color:#6b8c7a; padding:40px 0;">
                    No hay textos de nivel intermedio o avanzado disponibles.
                </p>`;
            return;
        }

        textosAvanzadosCargados = data.textos;

        data.textos.forEach((texto, index) => {

            const esDificultad = texto.dificultad_nombre
                ? texto.dificultad_nombre.toLowerCase()
                : "intermedio";
            const esBadgeClass = esDificultad.includes("avanzado") ? "avanzado" : "intermedio";
            const badgeLabel   = esDificultad.includes("avanzado") ? "Avanzado" : "Intermedio";

            const idContenido = `contenido-ej${index}`;
            const idPreguntas = `preguntas-ej${index}`;

            let htmlPreguntas = "";

            texto.preguntas.forEach((pregunta, pi) => {
                htmlPreguntas += `
                    <div class="ejercicio-pregunta">
                        <h4>${pi + 1}. ${pregunta.texto_pregunta}</h4>`;
                pregunta.opciones.forEach(opcion => {
                    htmlPreguntas += `
                        <label>
                            <input type="radio"
                                name="ej${index}-p${pregunta.ID}"
                                value="${opcion.ID}">
                            ${opcion.texto_opcion}
                        </label>`;
                });
                htmlPreguntas += `</div>`;
            });

            const tarjeta = document.createElement("article");
            tarjeta.className = "ejercicio-avanzado";
            tarjeta.innerHTML = `
                <div class="ejercicio-avanzado-header">
                    <span class="badge-dificultad ${esBadgeClass}">${badgeLabel}</span>
                    <h3>${texto.titulo}</h3>
                </div>
                <div class="ejercicio-avanzado-body">
                    <p class="ejercicio-texto" id="${idContenido}">${texto.contenido}</p>
                    <p class="ejercicio-preguntas-label">📝 Preguntas</p>
                    <div id="${idPreguntas}">${htmlPreguntas}</div>
                </div>
            `;

            lista.appendChild(tarjeta);
        });

        // Un solo listener. Al hacer clic, recoge TODAS las respuestas de todos
        // los textos y las manda en una sola llamada a guardar_avanzado.php.
        document.getElementById("btnTermineAvanzado").addEventListener("click", () => {

            // Armar un array con { idTexto, respuestas } por cada texto
            const todosLosTextos = textosAvanzadosCargados.map((texto, index) => {
                const respuestas = {};
                texto.preguntas.forEach(pregunta => {
                    const seleccionada = document.querySelector(
                        `input[name="ej${index}-p${pregunta.ID}"]:checked`
                    );
                    respuestas[pregunta.ID] = seleccionada ? seleccionada.value : null;
                });
                return { idTexto: texto.ID, respuestas };
            });

            fetch("../controllers/guardar_avanzado.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ textos: todosLosTextos })
            })
            .then(res => res.json())
            .then(resultado => {
                document.getElementById("resultado-avanzado-global").innerText =
                    `✅ ${resultado.mensaje} — Puntaje global: ${resultado.puntaje}%`;
            })
            .catch(err => {
                console.error(err);
                document.getElementById("resultado-avanzado-global").innerText =
                    "❌ Error al guardar las respuestas.";
            });
        });

    })
    .catch(err => {
        console.error(err);
        document.getElementById("lista-ejercicios-avanzados").innerHTML =
            `<p style="text-align:center; color:#721c24; padding:40px 0;">
                No se pudieron cargar los ejercicios avanzados.
            </p>`;
    });


/* ──────────────────────────────────────────────────────────
   HELPERS — Verificación de respuestas (texto fácil, sección 1)
────────────────────────────────────────────────────────── */

function verificarRespuestas(data, idResultado) {

    const respuestas = {};

    data.preguntas.forEach(pregunta => {
        const seleccionada = document.querySelector(
            `input[name="pregunta${pregunta.ID}"]:checked`
        );
        respuestas[pregunta.ID] = seleccionada ? seleccionada.value : null;
    });

    fetch("../controllers/guardar.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            idTexto: data.texto.ID,
            respuestas: respuestas
        })
    })
    .then(res => res.json())
    .then(resultado => {
        document.getElementById(idResultado).innerText = resultado.mensaje;
    });
}