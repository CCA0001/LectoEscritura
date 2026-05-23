document.getElementById(

    "formActualizarLogro"

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

                nombre:
                    document.getElementById(
                        "nombre"
                    ).value,

                recompensa_xp:
                    document.getElementById(
                        "recompensa_xp"
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

                    "../controllers/LogroController.php?accion=actualizarLogros",

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
                    "Logro actualizado correctamente"
                );

                window.location.href =
                    "../views/gestionarLogros.php";

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