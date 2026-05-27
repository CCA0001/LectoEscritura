document.getElementById(

    "formActualizarNivel"

).addEventListener(

    "submit",

    async e => {

        e.preventDefault();

        try{

            const body = {

                ID:
                    document.getElementById(
                        "idNivel"
                    ).value,

                nombre:
                    document.getElementById(
                        "nombre"
                    ).value,

                recompensa_xp:
                    document.getElementById(
                        "xp_requerida"
                    ).value,

                descripcion:
                    document.getElementById(
                        "descripcion"
                    ).value,

                estado:
                    document.getElementById(
                        "estado"
                    ).value
            };

            const response =
                await fetch(

                    "../controllers/NivelProgresoController.php?accion=actualizarNivel",

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
                    "Nivel actualizado correctamente"
                );

                window.location.href =
                    "../views/gestionarNivelesProgreso.php";

            }else{

                mostrarError(
                    data.mensaje
                );
            }

        }catch(error){

            console.error(error);

            mostrarError(
                "Error actualizando logro"
            );
        }
    }
);