fetch("../controllers/ModuloLecturaController.php?accion=obtenerTextoFacilAleatorio")
.then(res => res.json())
.then(data => {

    if(data.error){
        document.getElementById("preguntas").innerText =
            data.error;
        return;
    }

    window.textoFacilCargado = data;

    document.getElementById("titulo").innerText =
        data.texto.titulo;

    document.getElementById("contenido").innerText =
        data.texto.contenido;

    let htmlPreguntas = "";

    data.preguntas.forEach((pregunta, index) => {

        htmlPreguntas += `
            <div class="pregunta">
                <h3>${index + 1}. ${pregunta.texto_pregunta}</h3>
        `;

        pregunta.opciones.forEach(opcion => {

            htmlPreguntas += `
                <label>
                    <input
                        type="radio"
                        name="pregunta${pregunta.ID}"
                        value="${opcion.ID}"
                    >
                    ${opcion.texto_opcion}
                </label><br>
            `;
        });

        htmlPreguntas += `</div>`;
    });

    document.getElementById("preguntas").innerHTML =
        htmlPreguntas;
})
.catch(error => {

    console.error(error);

    document.getElementById("preguntas").innerText =
        "Error al cargar el texto.";
});