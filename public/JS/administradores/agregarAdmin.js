document.addEventListener(
    "DOMContentLoaded",

    () => {

document
    .getElementById(
        "formAdmin"
    )

    .addEventListener(

        "submit",

        async e => {

            e.preventDefault();

            const nombres =
                document.getElementById(
                    "nombres"
                ).value;

            const apellidos =
                document.getElementById(
                    "apellidos"
                ).value;

            const nombre_usuario =
                document.getElementById(
                    "nombre_usuario"
                ).value;

            const contrasenia = 
                document.getElementById(
                    "contrasenia"
                ).value;

            const confirmar_contrasenia =
                document.getElementById(
                    "confirmar_contrasenia"
                ).value;

            const correo =
                document.getElementById(
                    "correo"
                ).value;

            const estado =
                document.getElementById(
                    "estado"
                ).value;

            try{

                const response =
                    await fetch(

                        "../controllers/AdminController.php?accion=agregarAdministrador",

                        {
                            method:"POST",

                            headers:{
                                "Content-Type":"application/json"
                            },

                            body: JSON.stringify({

                                nombres,

                                apellidos,

                                nombre_usuario,

                                contrasenia,
                                
                                confirmar_contrasenia,

                                correo,

                                estado
                            })
                        }
                    );

                const data =
                    await response.json();

                if(data.success){

                    alert(
                        "Admin agregado"
                    );

                    document
                        .getElementById(
                            "formAdmin"
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
                    "Error agregando admin"
                );
            }
        }
    );
})