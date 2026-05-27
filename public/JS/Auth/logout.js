document
    .getElementById(
        "btnLogout"
    )

    .addEventListener(

        "click",

        async () => {

            try{

                const response =
                    await fetch(

                        "../controllers/AuthController.php?accion=logout",

                        {
                            method:"POST"
                        }
                    );

                const data =
                    await response.json();

                if(data.success){

                    window.location.href =
                        "../views/login.html";

                }else{

                    alert(
                        data.mensaje
                    );
                }

            }catch(error){

                console.error(error);

                alert(
                    "Error cerrando sesión"
                );
            }
        }
    );