let datosOriginales = {};

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

            const response =
                await fetch(

                    `../controllers/AdminController.php?accion=obtenerAdmin&id=${id}`
                );

            const data =
                await response.json();

            if(!data.success){

                mostrarError(
                    data.mensaje
                );

                return;
            }

            const logro =
                data.logro;

            datosOriginales =
                logro;

            llenarFormulario(
                logro
            );

        }catch(error){

            console.error(error);

            mostrarError(
                "Error cargando logro"
            );
        }
    }
);

function llenarFormulario(logro){

    document.getElementById(
        "ID"
    ).value =
        logro.ID;

    document.getElementById(
        "nombre"
    ).value =
        logro.nombre;

    document.getElementById(
        "recompensa_xp"
    ).value =
        logro.recompensa_xp;

    document.getElementById(
        "descripcion"
    ).value =
        logro.descripcion;

    document.getElementById(
        "estado"
    ).value =
        logro.estado;
}

function restaurarValoresOriginales(){

    llenarFormulario(
        datosOriginales
    );
}

function mostrarError(mensaje){

    alert(mensaje);
}