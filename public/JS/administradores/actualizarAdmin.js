document.getElementById(

    "formActualizarAdmin"

).addEventListener(

    "submit",

    async e => {

        e.preventDefault();

        try{

            const body = {

                ID:
                    document.getElementById(
                        "ID"
                    ).value,

                nombres:
                    document.getElementById(
                        "nombres"
                    ).value,

                apellidos:
                    document.getElementById(
                        "apellidos"
                    ).value,

                nombre_usuario:
                    document.getElementById(
                        "nombre_usuario"
                    ).value,

                correo:
                    document.getElementById(
                        "correo"
                    ).value,

                contrasenia:
                    document.getElementById(
                        "contrasenia"
                    ).value,

                confirmar_contrasenia:
                    document.getElementById(
                        "confirmar_contrasenia"
                    ).value,

                estado:
                    document.getElementById(
                        "estado"
                    ).value
            };

            const response =
                await fetch(

                    "../controllers/AdminController.php?accion=actualizarAdmin",

                    {

                        method:"POST",

                        headers:{

                            "Content-Type":
                                "application/json"
                        },

                        body:
                            JSON.stringify(
                                body
                            )
                    }
                );

            const data =
                await response.json();

            if(data.success){

                alert(
                    "Admin actualizado correctamente"
                );

                window.location.href =
                    "../views/gestionarAdministradores.php";

            }else{

                mostrarError(
                    data.mensaje
                );
            }

        }catch(error){

            console.error(error);

            mostrarError(
                "Error actualizando administrador"
            );
        }
    }
);