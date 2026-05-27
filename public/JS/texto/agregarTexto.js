document.getElementById(
    "formTexto"
).addEventListener(

    "submit",

    async e => {

        e.preventDefault();

        try {

            const body = {

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
                    ).value,

                contenido:
                    document.getElementById(
                        "contenido"
                    ).value,

                fuente:
                    document.getElementById(
                        "fuente"
                    ).value,

                estado:
                    document.getElementById(
                        "estado"
                    ).value

            };

            const response =
                await fetch(

                    "../controllers/TextoController.php?accion=agregarTextoManualmente",

                    {

                        method: "POST",

                        headers: {
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

            if (data.success) {

                alert(
                    data.mensaje ??
                    "Texto agregado correctamente"
                );

                document.getElementById(
                    "formTexto"
                ).reset();

                if (
                    typeof cargarTextos ===
                    "function"
                ) {

                    cargarTextos();

                }

            } else {

                alert(
                    data.mensaje ??
                    "No se pudo agregar el texto"
                );

            }

        } catch (error) {

            console.error(error);

            alert(
                "Error de comunicación con el servidor"
            );

        }

    }

);