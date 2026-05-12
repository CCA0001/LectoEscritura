<?php
session_start();
include("../config/conexion.php");

/*
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit();
}
*/

$textos = [];
$res = $conexion->query("
    SELECT t.ID, t.titulo, d.nombre AS dificultad, tt.nombre AS tipo_texto
    FROM textolectura t
    LEFT JOIN niveldificultad d  ON t.ID_dificultad = d.ID
    LEFT JOIN tipotexto tt       ON t.ID_tipoTexto  = tt.ID
    ORDER BY t.ID ASC
");
if ($res) {
    while ($row = $res->fetch_assoc()) $textos[] = $row;
}

// Listas para los selects del formulario
$dificultades = [];
$res_d = $conexion->query("SELECT ID, nombre FROM niveldificultad ORDER BY ID ASC");
if ($res_d) while ($row = $res_d->fetch_assoc()) $dificultades[] = $row;

$tipos = [];
$res_t = $conexion->query("SELECT ID, nombre FROM tipotexto ORDER BY ID ASC");
if ($res_t) while ($row = $res_t->fetch_assoc()) $tipos[] = $row;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Textos — EVAL</title>
    <link rel="stylesheet" href="css/pantalla_principal_Usuario.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/gestionar.css?v=<?php echo time(); ?>">
    <style>
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #dce8dc;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: Arial, sans-serif;
            color: #3a3a3a;
            background: #fafcfa;
            outline: none;
            box-sizing: border-box;
            resize: vertical;
            min-height: 120px;
        }
        .form-group textarea:focus {
            border-color: #4a8d6e;
            background: white;
        }
        .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #dce8dc;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: Arial, sans-serif;
            color: #3a3a3a;
            background: #fafcfa;
            outline: none;
            box-sizing: border-box;
        }
        .form-group select:focus {
            border-color: #4a8d6e;
            background: white;
        }
        .badge-dificultad {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .badge-facil    { background: #e8f5e9; color: #2e7d32; }
        .badge-medio    { background: #fff8e1; color: #f57f17; }
        .badge-dificil  { background: #fce4ec; color: #ad1457; }
    </style>
</head>
<body>

<header class="navbar">
    <span class="logo">EVAL</span>
    <a href="Acciones/cerrar_sesion.php" class="btn-logout">Cerrar sesión</a>
</header>

<nav class="breadcrumb">
    📍 Inicio / <a href="pantalla_principal_Admin.php">Panel Admin</a> / Gestionar Textos
</nav>

<div class="workspace">

    <!-- Panel izquierdo: tabla -->
    <section class="panel-tabla">

        <div class="panel-header">
            <h2>📄 Textos de lectura</h2>
        </div>

        <div class="tabla-scroll">
            <?php if (empty($textos)): ?>
                <div class="tabla-vacia"><p>No hay textos registrados aún.</p></div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Dificultad</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($textos as $t): ?>
                    <tr>
                        <td class="td-id"><?php echo htmlspecialchars($t['ID']); ?></td>
                        <td class="td-nombre"><?php echo htmlspecialchars($t['titulo']); ?></td>
                        <td>
                            <?php
                                $d = strtolower($t['dificultad'] ?? '');
                                $clase = str_contains($d, 'f') ? 'facil' : (str_contains($d, 'v') ? 'dificil' : 'medio');
                            ?>
                            <span class="badge-dificultad badge-<?php echo $clase; ?>">
                                <?php echo htmlspecialchars($t['dificultad'] ?? '—'); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($t['tipo_texto'] ?? '—'); ?></td>
                        <td>
                            <form method="POST" action="Acciones/eliminar_texto.php"
                                  onsubmit="return confirm('¿Eliminar el texto &quot;<?php echo htmlspecialchars($t['titulo']); ?>&quot;?')">
                                <input type="hidden" name="id" value="<?php echo (int)$t['ID']; ?>">
                                <button type="submit" class="btn-eliminar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <div class="panel-footer">
            Total: <strong><?php echo count($textos); ?></strong> texto<?php echo count($textos) !== 1 ? 's' : ''; ?>
        </div>

    </section>

    <!-- Panel derecho: formulario -->
    <section class="panel-form">

        <div class="panel-header">
            <h2>➕ Agregar texto</h2>
        </div>

        <div class="form-body">
            <p class="subtitulo">Completa los campos para agregar un nuevo texto de lectura al sistema.</p>

            <form method="POST" action="Acciones/agregar_texto.php" id="formTexto">

                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo"
                           placeholder="Ej: La metamorfosis" required>
                </div>

                <div class="form-group">
                    <label for="contenido">Contenido</label>
                    <textarea id="contenido" name="contenido"
                              placeholder="Escribe o pega el texto aquí..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="dificultad">Nivel de dificultad</label>
                    <select id="dificultad" name="ID_dificultad" required>
                        <option value="" disabled selected>Selecciona un nivel</option>
                        <?php foreach ($dificultades as $d): ?>
                        <option value="<?php echo (int)$d['ID']; ?>">
                            <?php echo htmlspecialchars($d['nombre']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo de texto</label>
                    <select id="tipo" name="ID_tipoTexto" required>
                        <option value="" disabled selected>Selecciona un tipo</option>
                        <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo (int)$t['ID']; ?>">
                            <?php echo htmlspecialchars($t['nombre']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </form>
        </div>

        <div class="form-footer">
            <button type="button" class="btn-limpiar"
                    onclick="document.getElementById('formTexto').reset()">Limpiar</button>
            <button type="submit" form="formTexto" class="btn-guardar">Agregar texto</button>
        </div>

    </section>

</div>

</body>
</html>
