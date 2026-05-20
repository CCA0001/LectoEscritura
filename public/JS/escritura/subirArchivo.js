const form =
    document.getElementById(
        "formSubirArchivo"
    );

form.addEventListener("submit", async e => {

    e.preventDefault();

    const formData =
        new FormData(form);

    try{

        const response = await fetch(

            "../controllers/ModuloEscrituraController.php?accion=subirArchivo",

            {
                method: "POST",
                body: formData
            }
        );

        const data =
            await response.json();

        alert(data.mensaje);

        if(data.success){

            location.reload();
        }

    }catch(error){

        console.error(error);

        alert(
            "Error al subir archivo"
        );
    }
});