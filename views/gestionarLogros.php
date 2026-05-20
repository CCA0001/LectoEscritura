<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Logros — EVAL</title>
    <link rel="stylesheet" href="../public/css/pantalla_principal_Usuario.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../public/css/gestionar.css?v=<?php echo time(); ?>">
    <style>
        .td-xp {
            font-weight: bold;
            color: #b8860b;
        }
        .td-desc {
            color: #666;
            font-size: 0.82rem;
            max-width: 200px;
        }
    </style>
</head>
<body>

<header class="navbar">
    <span class="logo">EVAL</span>
    <a href="../controllers/cerrar_sesion.php" class="btn-logout">Cerrar sesión</a>
</header>

<nav class="breadcrumb">
    📍 Inicio / <a href="pantalla_principal_Admin.php">Panel Admin</a> / Gestionar Logros
</nav>

<div class="workspace">

    <!-- Panel izquierdo: tabla -->
    <section class="panel-tabla">

        <div class="panel-header">
            <h2>🏆 Logros registrados</h2>
        </div>

        <div class="tabla-scroll">
            <?php if (empty($logros)): ?>
                <div class="tabla-vacia"><p>No hay logros registrados aún.</p></div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>XP</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logros as $l): ?>
                    <tr>
                        <td class="td-id"><?php echo htmlspecialchars($l['ID']); ?></td>
                        <td class="td-nombre"><?php echo htmlspecialchars($l['nombre']); ?></td>
                        <td class="td-desc"><?php echo htmlspecialchars($l['descripcion']); ?></td>
                        <td class="td-xp">+<?php echo htmlspecialchars($l['recompensa_xp']); ?> XP</td>
                        <td class="td-estado">+<?php echo htmlspecialchars($l['estado']); ?> </td>
                        <td>
                            <form method="POST" action="../controllers/LogroController.php?accion=invertirEstado"
                                  onsubmit="return confirm('Invertir el estado del logro &quot;<?php echo htmlspecialchars($l['nombre']); ?>&quot;?')">
                                <input type="hidden" name="ID" value="<?php echo (int)$l['ID']; ?>">
                                <input type="hidden" name="estado" value="<?php echo htmlspecialchars($l['estado']); ?>">
                                <button type="submit" class="btn-estado">Cambiar estado</button>
                            </form>
                            <a
                                href="../controllers/LogroController.php?accion=mostrarVistaActualizar&id=<?php echo $l['ID']; ?>"
                                class="btn-estado"
                            >
                                Actualizar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <div class="panel-footer">
            Total: <strong><?php echo count($logros); ?></strong> logro<?php echo count($logros) !== 1 ? 's' : ''; ?>
        </div>

    </section>

    <!-- Panel derecho: formulario -->
    <section class="panel-form">

        <div class="panel-header">
            <h2>➕ Agregar logro</h2>
        </div>

        <div class="form-body">
            <p class="subtitulo">Completa los campos para crear un nuevo logro que los usuarios podrán desbloquear.</p>

            <form method="POST" action="../controllers/LogroController.php?accion=agregarLogros" id="formLogro">

                <div class="form-group">
                    <label for="nombre">Nombre del logro</label>
                    <input type="text" id="nombre" name="nombre"
                           placeholder="Ej: Racha de 1 semana" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion"
                           placeholder="Ej: Inicia sesión 7 días seguidos" required>
                </div>

                <div class="form-group">
                    <label for="xp">Recompensa en XP</label>
                    <input type="number" id="xp" name="recompensa_xp"
                           placeholder="Ej: 100" min="0" required>
                    <p class="hint">Puntos de experiencia que recibirá el usuario al desbloquearlo.</p>
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" name="estado"
                           placeholder="Ej: Activo" min="0" required>
                    <p class="hint">Puntos de experiencia que recibirá el usuario al desbloquearlo.</p>
                </div>
            </form>
        </div>

        <div class="form-footer">
            <button type="button" class="btn-limpiar"
                    onclick="document.getElementById('formLogro').reset()">Limpiar</button>
            <button type="submit" form="formLogro" class="btn-guardar">Agregar logro</button>
        </div>

    </section>

</div>

</body>
</html>
