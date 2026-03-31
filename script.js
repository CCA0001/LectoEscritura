fetch("obtenerTextoLectura.php?nocache=" + Date.now()).then(res => res.json())
.then(data => {

    document.getElementById("titulo").innerText = data.texto.titulo;
    document.getElementById("contenido").innerText = data.texto.contenido;

    let contenedor = document.getElementById("preguntas");

    data.preguntas.forEach((pregunta, i) => {

        let html = `<h3>${i+1}. ${pregunta.texto_pregunta}</h3>`;

        pregunta.opciones.forEach(opcion => {

            html += `
            <label>
            <input type="radio" name="pregunta${pregunta.ID}">
            ${opcion.texto_opcion}
            </label><br>
            `;

        });

        contenedor.innerHTML += html;

    });

});