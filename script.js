fetch("obtenerTextoLectura.php?nocache=" + Date.now()).then(res => res.json())
.then(data => {

    document.getElementById("titulo").innerText = data.texto.titulo;
    document.getElementById("contenido").innerText = data.texto.contenido;

    let contenedor = document.getElementById("preguntas");

    if(data.error){
        contenedor.innerText = "Error: " + data.error;
        return;
    }

    let htmlTotal = "";

    data.preguntas.forEach((pregunta, i) => {

        htmlTotal += `<h3>${i+1}. ${pregunta.texto_pregunta}</h3>`;

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
        const respuestas = {};

        data.preguntas.forEach(pregunta => {
            const seleccionada = document.querySelector(`input[name="pregunta${pregunta.ID}"]:checked`);
            respuestas[pregunta.ID] = seleccionada ? seleccionada.value : null;
        });

        fetch("guardar.php", {
            method: "POST",
            headers: {"Content-Type": "application/json" },
            body: JSON.stringify({
                idTexto: data.texto.ID,
                respuestas: respuestas
            })
        })
        .then(res => res.json())
        .then(resultado => {
            document.getElementById("resultado").innerText = resultado.mensaje;
        });
    });

}).catch(err => {
    console.error(err);
    document.getElementById("preguntas").innerText = "No se pudo cargar el cuestionario";
});