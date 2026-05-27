document
.getElementById("btnVerificar")
.addEventListener("click", () => {

    const texto = window.textoFacilCargado.texto;

    if(!texto){
        alert("No hay texto cargado");
        return;
    }

    const respuestas = {};

    texto.preguntas.forEach(pregunta => {

        const seleccionada =
            document.querySelector(
                `input[name="pregunta${pregunta.ID}"]:checked`
            );

        respuestas[pregunta.ID] =
            seleccionada
            ? seleccionada.value
            : null;
    });

    fetch(
        "../controllers/ModuloLecturaController.php?accion=guardarIntentoLectura",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                textos: [
                    {
                        idTexto: texto.ID,
                        respuestas: respuestas                    
                    }
                ]

            })
        }
    )
    .then(res => res.json())
    .then(resultado => {

        document.getElementById("resultado").innerText =
            resultado.mensaje + "\n" + resultado.xp;
    })
    .catch(error => {

        console.error(error);

        document.getElementById("resultado").innerText =
            "Error al guardar respuestas";
    });
});