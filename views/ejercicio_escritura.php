<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio de Escritura - LectoEscritura</title>
    <link rel="stylesheet" href="../public/css/escritura.css?v=<?php echo time(); ?>">
    <script src="../public/JS/retroalimentacion.js"></script>
</head>
<body>

<header class="navbar">
    <span class="logo"> EVAL - Escritura</span>
    <div class="user-rank">
        🏅 <?php echo $_SESSION['rango_actual'] ?? 'Principiante'; ?>
    </div>
    <a href="../views/pantalla_principal_Usuario.php" class="btn-volver">Volver</a>
</header>

<nav class="breadcrumb">
   < 📍 Inicio / Panel Usuario / Ejercicio de Escritura
</nav>

<main class="container">
    <h1>Ejercicio de Escritura</h1>
    <p>Sube tu archivo PDF para ser evaluado. Recibirás retroalimentación y puntos de experiencia.</p>

    <div class="upload-card">
        <h2>Subir nuevo archivo</h2>
        <form id="formSubirArchivo" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nombre del ejercicio:</label>
                <input type="text" name="nombre_archivo" placeholder="Ej: Ensayo sobre la lectura" required>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Nivel de dificultad:</label>
                    <select name="ID_dificultad" id="selectDificultad" required>
                        <option value="">Selecciona...</option>
                    </select>
                </div>

                <div class="form-group half">
                    <label>Tipo de texto:</label>
                    <select name="ID_tipoTexto" id="selectTipoTexto" required>
                        <option value="">Selecciona...</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>📎 Archivo PDF:</label>
                <input type="file" name="archivo_pdf" accept=".pdf,.docx,.txt" required>
                <small>Máximo 5MB. Solo archivos PDF.</small>
            </div>

            <button type="submit" class="btn-subir">Enviar</button>
        </form>
    </div>

    <div class="historial-card">
        <h2>Mis archivos subidos</h2>

        <div id="contenedorArchivos">
            <p class="cargando"> Cargando archivos... </p>
        </div>
    </div>
</main>

<div id="modalRetro" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="cerrarModal()">&times;</span>
        <h3>🤖 Retroalimentación de la IA</h3>
        <div id="retroTexto"></div>
    </div>
</div>

</body>

    <script src="../public/JS/escritura/cargarArchivosUsuario.js"></script>
    <script src="../public/JS/escritura/cargarDificultades.js"></script>
    <script src="../public/JS/escritura/cargarTipoTexto.js"></script>
    <script src="../public/JS/escritura/evaluarArchivo.js"></script>
    <script src="../public/JS/escritura/mostrarRetroalimentacion.js"></script>
    <script src="../public/JS/escritura/subirArchivo.js"></script>
</html>