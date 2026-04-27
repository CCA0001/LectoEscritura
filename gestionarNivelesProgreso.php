<?php
session_start();
include("conexion.php");

/*
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit();
}
*/

$niveles = [];
$res = $conexion->query("SELECT ID, nombre, puntos_requeridos FROM nivelprogreso ORDER BY puntos_requeridos ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) $niveles[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Niveles de Progreso — EVAL</title>
    <link rel="stylesheet" href="css/pantalla_principal_Usuario.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/gestionar.css?v=<?php echo time(); ?>">
    <style>
        .td-puntos {
            font-weight: bold;
            color: #4a8d6e;
        }
    </style>
</head>
<body>

<header class="navbar">
    <span class="logo">EVAL</span>
    <a href="Acciones/cerrar_sesion.php" class="btn-logout">Cerrar sesión</a>
</header>

<nav class="breadcrumb">
    📍 Inicio / <a href="pantalla_principal_Admin.php">Panel Admin</a> / Gestionar Niveles de Progreso
</nav>

<div class="workspace">

    <!-- Panel izquierdo: tabla -->
    <section class="panel-tabla">

        <div class="panel-header">
            <h2>⭐ Niveles de progreso</h2>
        </div>

        <div class="tabla-scroll">
            <?php if (empty($niveles)): ?>
                <div class="tabla-vacia"><p>No hay niveles registrados aún.</p></div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>XP requerido</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($niveles as $n): ?>
                    <tr>
                        <td class="td-id"><?php echo htmlspecialchars($n['ID']); ?></td>
                        <td class="td-nombre"><?php echo htmlspecialchars($n['nombre']); ?></td>
                        <td class="td-puntos"><?php echo number_format($n['puntos_requeridos']); ?> XP</td>
                        <td>
                            <form method="POST" action="Acciones/eliminar_nivel.php"
                                  onsubmit="return confirm('¿Eliminar el nivel &quot;<?php echo htmlspecialchars($n['nombre']); ?>&quot;?')">
                                <input type="hidden" name="id" value="<?php echo (int)$n['ID']; ?>">
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
            Total: <strong><?php echo count($niveles); ?></strong> nivel<?php echo count($niveles) !== 1 ? 'es' : ''; ?>
        </div>

    </section>

    <!-- Panel derecho: formulario -->
    <section class="panel-form">

        <div class="panel-header">
            <h2>➕ Agregar nivel</h2>
        </div>

        <div class="form-body">
            <p class="subtitulo">Crea un nuevo nivel de progreso definiendo su nombre y los puntos XP necesarios para alcanzarlo.</p>

            <form method="POST" action="Acciones/agregar_nivel.php" id="formNivel">

                <div class="form-group">
                    <label for="nombre">Nombre del nivel</label>
                    <input type="text" id="nombre" name="nombre"
                           placeholder="Ej: Lector Experto" required>
                </div>

                <div class="form-group">
                    <label for="puntos">Puntos XP requeridos</label>
                    <input type="number" id="puntos" name="puntos_requeridos"
                           placeholder="Ej: 500" min="0" required>
                    <p class="hint">El usuario alcanzará este nivel al acumular esta cantidad de XP.</p>
                </div>

            </form>
        </div>

        <div class="form-footer">
            <button type="button" class="btn-limpiar"
                    onclick="document.getElementById('formNivel').reset()">Limpiar</button>
            <button type="submit" form="formNivel" class="btn-guardar">Agregar nivel</button>
        </div>

    </section>

</div>

</body>
</html>
