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
                // La alerta de racha/logro se muestra ya en el panel: acá
                // solo se guarda en sessionStorage para que racha_alerta.js
                // la levante después del redirect.
                const alerta = {};

                if(data.xp_diario){

                    alerta.mensajeRacha =
                        `Llevas ${data.racha} día(s) seguidos. ` +
                        `Ganaste +${data.xp_diario} XP por ingresar hoy.`;
                }

                if(data.logro){

                    alerta.logroNombre = data.logro.nombre;

                    alerta.logroXp = data.logro.xp;
                }

                if(alerta.mensajeRacha || alerta.logroNombre){

                    try {
                        sessionStorage.setItem(
                            "alertaRacha",
                            JSON.stringify(alerta)
                        );
                    } catch(error) {
                        console.error(error);
                    }
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