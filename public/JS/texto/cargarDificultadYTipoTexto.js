document.addEventListener(
    "DOMContentLoaded",
    cargarCombos
);

async function cargarCombos() {

    try {

        const respuesta = await fetch(
            "../controllers/TextoController.php?accion=obtenerCombos"
        );

        const datos = await respuesta.json();

        cargarDificultades(
            datos.dificultades
        );

        cargarTiposTexto(
            datos.tiposTexto
        );

    } catch(error) {

        console.error(error);

    }

}

function cargarDificultades(
    dificultades
) {

    const select =
        document.getElementById(
            "dificultad"
        );

    select.innerHTML =
        '<option value="">Seleccione...</option>';

    dificultades.forEach(
        dificultad => {

            select.innerHTML += `
                <option value="${dificultad.ID}">
                    ${dificultad.nombre}
                </option>
            `;

        }
    );

}

function cargarTiposTexto(
    tiposTexto
) {

    const select =
        document.getElementById(
            "tipo_texto"
        );

    select.innerHTML =
        '<option value="">Seleccione...</option>';

    tiposTexto.forEach(
        tipo => {

            select.innerHTML += `
                <option value="${tipo.ID}">
                    ${tipo.nombre}
                </option>
            `;

        }
    );

}