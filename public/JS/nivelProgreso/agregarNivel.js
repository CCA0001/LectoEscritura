
    document.getElementById(

        "formNivel"

    ).addEventListener(

        "submit",

        async e => {

            e.preventDefault();

            try{

                const body = {

                    nombre:
                        document.getElementById(
                            "nombre"
                        ).value,

                    xp_requerida:
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

                        "../controllers/NivelProgresoController.php?accion=agregarNivel",

                        {

                            method:"POST",

                            headers:{
                                "Content-Type":"application/json"
                            },

                            body:
                                JSON.stringify(
                                    body
                                )
                        }
                    );

                const data =
                    await response.json();

                if(!data.success){

                    alert(
                        "Nivel agregado"
                    );

                    document.getElementById(
                        "formNivel"
                    ).reset();

                }else{

                    alert(
                        data.mensaje
                    );
                }

            }catch(error){

                console.error(error);
            }
        }
    );


