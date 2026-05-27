fetch(
    "../controllers/ModuloEscrituraController.php?accion=obtenerTiposTexto"
)
.then(res => res.json())
.then(data => {

    const select =
        document.getElementById("selectTipoTexto");


    data.tipos.forEach(d => {

        select.innerHTML += `
            <option value="${d.ID}">
                ${d.nombre}
            </option>
        `;
    });
});