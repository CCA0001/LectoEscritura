document.getElementById("formRegistro").addEventListener( "submit", async e =>{
    e.preventDefault();

    try{
        const body = {
            nombre:
                document
                    .getElementById(
                        "nombre"
                    ).value,

            correo:
                document
                    .getElementById(
                        "correo"
                    ).value + "@ucundinamarca.edu.co",

            contrasenia:
                document
                    .getElementById(
                        "contrasenia"
                    ).value,
                    
            confirmar_contrasenia:
                document
                    .getElementById(
                        "confirmarContrasenia"
                    ).value
                };  

            const response =
                await fetch(

                    "../controllers/AuthController.php?accion=registerUsuario",

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

                alert(data.mensaje);
                window.location.href =
                    "../views/login.html";

            }else{

                alert(
                    data.mensaje
                );

            }                
        
    }catch(error){
        console.log(error);
    }
})