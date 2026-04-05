
pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

document.getElementById("btnSubir").addEventListener("click", async() => {
    const archivo = document.getElementById("subirArchivo").files[0];
    
    if(!archivo){
        alert("Selecciona un archivo primero");
        return;
    }

    const contenido = await leerArchivo(archivo);


    document.getElementById("previsualizacion").innerText = contenido.slice(0,500) + "...";

    fetch("subirTextoEscritura.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            nombre: archivo.name,
            contenido: contenido
        })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("mensajeResultado").innerText = data.mensaje;
    });
})

async function leerArchivo(archivo){
    const extensionArchivo = archivo.name.split(".").pop().toLowerCase();

    if(extensionArchivo == "txt"){
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => resolve(e.target.result);
            reader.onerror = () => reject(new Error("Error leyendo .txt"));
            reader.readAsText(archivo, "UTF-8");
        })
    }

    if(extensionArchivo == "docx"){
        const buffer = await archivo.arrayBuffer();
        const resultado = await mammoth.extractRawValue({ arrayBuffer: buffer});
        return resultado.value;
    }

    if(extensionArchivo == "pdf"){
        const buffer = await archivo.arrayBuffer();
        const pdf = await pdfjsLib.getDocument({ data: buffer }).promise;
        let texto = "";
        for (let i = 1; i <= pdf.numPages; i++){
            const pagina = await pdf.getPage(i);
            const content = await pagina.getTextContent();
            texto += content.items.map(x => x.str).join(" ") + "\n";
        }
        return texto;
    }

    throw new Error("Formato no soportado");
}
