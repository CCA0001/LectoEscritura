document
    .getElementById(
        "formLogro"
    )

    .addEventListener(

        "submit",

        async e => {

            e.preventDefault();

            const nombre =
                document.getElementById(
                    "nombre"
                ).value;

            const descripcion =
                document.getElementById(
                    "descripcion"
                ).value;

            const recompensa_xp =
                document.getElementById(
                    "xp"
                ).value;

            const estado =
                document.getElementById(
                    "estado"
                ).value;

            try{

                const response =
                    await fetch(

                        "../controllers/LogroController.php?accion=agregarLogros",

                        {
                            method:"POST",

                            headers:{
                                "Content-Type":"application/json"
                            },

                            body: JSON.stringify({

                                nombre,

                                descripcion,

                                recompensa_xp,

                                estado
                            })
                        }
                    );

                const data =
                    await response.json();

                if(data.success){

                    alert(
                        "Logro agregado"
                    );

                    document
                        .getElementById(
                            "formLogro"
                        )
                        .reset();

                }else{

                    alert(
                        data.mensaje
                    );
                }

            }catch(error){

                console.error(error);

                alert(
                    "Error agregando logro"
                );
            }
        }
    );