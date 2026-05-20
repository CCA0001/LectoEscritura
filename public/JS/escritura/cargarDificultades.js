fetch(
    "../controllers/ModuloEscrituraController.php?accion=obtenerDificultades"
)
.then(res => res.json())
.then(data => {

    const select =
        document.getElementById("selectDificultad");


    data.dificultades.forEach(d => {

        select.innerHTML += `
            <option value="${d.ID}">
                ${d.nombre}
            </option>
        `;
    });
});