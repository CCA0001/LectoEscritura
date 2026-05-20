function evaluarArchivo(idArchivo){

    fetch(
        `../controllers/ModuloEscrituraController.php?accion=evaluarArchivo&id=${idArchivo}`
    )
    .then(res => res.json())
    .then(data => {

        if(data.success){

            alert(data.mensaje);

            location.reload();

        }else{

            alert(data.mensaje);
        }
    });
}