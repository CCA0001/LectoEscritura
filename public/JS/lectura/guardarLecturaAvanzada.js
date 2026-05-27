document
    .getElementById("btnTermineAvanzado")

    .addEventListener("click", () => {

        const textosEnviar = [];

        window.textosAvanzadosCargados
            .forEach((texto, index) => {

                const respuestas = {};

                texto.preguntas.forEach(pregunta => {

                    const seleccionada =
                        document.querySelector(
                            `input[name="ej${index}-p${pregunta.ID}"]:checked`
                        );

                    respuestas[pregunta.ID] =
                        seleccionada
                        ? seleccionada.value
                        : null;
                });

                textosEnviar.push({

                    idTexto: texto.ID,

                    respuestas: respuestas
                });
            });

        fetch(
            "../controllers/ModuloLecturaController.php?accion=guardarIntentoLectura",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({
                    textos: textosEnviar
                })
            }
        )

        .then(res => res.json())

        .then(resultado => {

            document.getElementById(
                "resultado-avanzado-global"
            ).innerText =
                resultado.mensaje + "\n" + resultado.xp;
        });

    });