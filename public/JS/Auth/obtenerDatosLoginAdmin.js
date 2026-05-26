document.getElementById(
    "formLogin"
).addEventListener(

    "submit",

    async e => {

        e.preventDefault();

        try {

            const body = {

                correo:
                    document
                        .getElementById(
                            "correo"
                        ).value,

                contrasenia:
                    document
                        .getElementById(
                            "contrasenia"
                        ).value

            };

            const response =
                await fetch(

                    "../controllers/AuthController.php?accion=loginAdmin",

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

            if(data.success){

                window.location.href =
                    "../views/pantalla_principal_Admin.php";

            }else{

                alert(
                    data.mensaje
                );

            }

        } catch(error){

            console.error(
                error
            );

        }

    }

);