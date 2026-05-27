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

                    "../controllers/AuthController.php?accion=loginUsuario",

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
                if(data.xp_diario){

                    await Swal.fire({

                        title: '¡Bienvenido!',

                        html: `
                            Ganaste
                            <strong>
                                +${data.xp_diario} XP
                            </strong>
                            por ingresar hoy.
                        `,

                        icon: 'success'
                    });
                }                
                window.location.href =
                    "../views/pantalla_principal_Usuario.php";

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