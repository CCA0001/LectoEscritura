document.getElementById(
    "formActualizarTexto"
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

                dificultad:
                    document.getElementById(
                        "dificultad"
                    ).value,

                tipo_texto:
                    document.getElementById(
                        "tipo_texto"
                    ).value,

                titulo:
                    document.getElementById(
                        "titulo"
                    ).value.trim(),

                contenido:
                    document.getElementById(
                        "contenido"
                    ).value.trim(),

                fuente:
                    document.getElementById(
                        "fuente"
                    ).value.trim(),

                estado:
                    document.getElementById(
                        "estado"
                    ).value
            };

            const response =
                await fetch(

                    "../controllers/TextoController.php?accion=actualizarTexto",

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
                    "Texto actualizado correctamente"
                );

                window.location.href =
                    "../views/gestionarTextos.php";

            }else{

                mostrarError(
                    data.mensaje ??
                    "No se pudo actualizar el texto"
                );
            }

        }catch(error){

            console.error(error);

            mostrarError(
                "Error actualizando texto"
            );
        }
    }
);

// Botón "Restaurar": vuelve a los valores con los que se abrió el
// formulario, para descartar los cambios sin guardar.
document.getElementById(
    "btnRestaurar"
).addEventListener(
    "click",

    () => {

        if(
            typeof restaurarValoresOriginales ===
            "function"
        ){

            restaurarValoresOriginales();
        }
    }
);
