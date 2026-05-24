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

            const admin =
                data.admin;

            datosOriginales =
                admin;

            llenarFormulario(
                admin
            );

        }catch(error){

            console.error(error);

            mostrarError(
                "Error cargando administrador"
            );
        }
    }
);

function llenarFormulario(admin){

    document.getElementById(
        "ID"
    ).value =
        admin.ID;

    document.getElementById(
        "nombres"
    ).value =
        admin.nombres;

    document.getElementById(
        "apellidos"
    ).value =
        admin.apellidos;

    document.getElementById(
        "nombre_usuario"
    ).value =
        admin.nombre_usuario;

    document.getElementById(
        "correo"
    ).value =
        admin.correo_electronico;

    document.getElementById(
        "contrasenia"
    ).value = 
        null

    document.getElementById(
        "estado"
    ).value = 
        admin.estado
}

function restaurarValoresOriginales(){

    llenarFormulario(
        datosOriginales
    );
}

function mostrarError(mensaje){

    alert(mensaje);
}