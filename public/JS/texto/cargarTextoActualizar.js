let datosOriginales = {};
let dificultades = [];
let tiposTexto = [];

document.addEventListener(
    "DOMContentLoaded",

    async () => {

        const params =
            new URLSearchParams(
                window.location.search
            );

        const id =
            params.get("id");

        if(!id){

            mostrarError(
                "ID no encontrado"
            );

            return;
        }

        try{

            // Se cargan en paralelo el texto y los combos de dificultad
            // y tipo, que son datos independientes.
            const [respuestaTexto, respuestaCombos] =
                await Promise.all([

                    fetch(
                        `../controllers/TextoController.php?accion=obtenerTextoPorId&id=${id}`
                    ),

                    fetch(
                        "../controllers/TextoController.php?accion=obtenerCombos"
                    )
                ]);

            const data =
                await respuestaTexto.json();

            if(!data.success){

                mostrarError(
                    data.mensaje
                );

                return;
            }

            const combos =
                await respuestaCombos.json();

            dificultades =
                combos.dificultades ?? [];

            tiposTexto =
                combos.tiposTexto ?? [];

            llenarSelects();

            datosOriginales =
                data.texto;

            llenarFormulario(
                data.texto
            );

        }catch(error){

            console.error(error);

            mostrarError(
                "Error cargando texto"
            );
        }
    }
);

function llenarSelects(){

    const selectDificultad =
        document.getElementById(
            "dificultad"
        );

    selectDificultad.innerHTML =
        '<option value="">Seleccione...</option>';

    dificultades.forEach(dificultad => {

        const option =
            document.createElement("option");

        option.value =
            dificultad.ID;

        option.textContent =
            dificultad.nombre;

        selectDificultad.appendChild(
            option
        );

    });

    const selectTipo =
        document.getElementById(
            "tipo_texto"
        );

    selectTipo.innerHTML =
        '<option value="">Seleccione...</option>';

    tiposTexto.forEach(tipo => {

        const option =
            document.createElement("option");

        option.value =
            tipo.ID;

        option.textContent =
            tipo.nombre;

        selectTipo.appendChild(
            option
        );

    });
}

function llenarFormulario(texto){

    document.getElementById(
        "ID"
    ).value =
        texto.ID;

    document.getElementById(
        "dificultad"
    ).value =
        texto.ID_dificultad;

    document.getElementById(
        "tipo_texto"
    ).value =
        texto.ID_tipoTexto;

    document.getElementById(
        "titulo"
    ).value =
        texto.titulo;

    document.getElementById(
        "contenido"
    ).value =
        texto.contenido;

    document.getElementById(
        "fuente"
    ).value =
        texto.fuente ?? "";

    document.getElementById(
        "estado"
    ).value =
        texto.estado;
}

function restaurarValoresOriginales(){

    llenarFormulario(
        datosOriginales
    );
}

function mostrarError(mensaje){

    alert(mensaje);
}
