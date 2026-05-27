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

                    `../controllers/NivelProgresoController.php?accion=obtenerNivelPorID&id=${id}`
                );

            const data =
                await response.json();

            if(!data.success){

                mostrarError(
                    data.mensaje
                );

                return;
            }

            const nivel =
                data.nivel;

            datosOriginales =
                nivel;

            llenarFormulario(
                nivel
            );

        }catch(error){

            console.error(error);

            mostrarError(
                "Error cargando logro"
            );
        }
    }
);

function llenarFormulario(nivel){

    document.getElementById(
        "idNivel"
    ).value =
        nivel.ID;

    document.getElementById(
        "nombre"
    ).value =
        nivel.nombre;

    document.getElementById(
        "xp_requerida"
    ).value =
        nivel.puntos_requeridos;

    document.getElementById(
        "descripcion"
    ).value =
        nivel.descripcion;

    document.getElementById(
        "estado"
    ).value =
        nivel.estado;
}

function restaurarValoresOriginales(){

    llenarFormulario(
        datosOriginales
    );
}

function mostrarError(mensaje){

    alert(mensaje);
}