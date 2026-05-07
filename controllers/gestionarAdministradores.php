estionaradministradores · PHP
Copiar

<?php
session_start();
include("../config/conexion.php");
 
/*
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit();
}
*/
 
$nombre_real = $_SESSION['nombre'] ?? 'Administrador';
 
// Traer administradores
$admins = [];
$res = $conexion->query("SELECT ID, nombre_usuario, correo_electronico, fecha_registro FROM `admin` ORDER BY id ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $admins[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Administradores — EVAL</title>
    <link rel="stylesheet" href="css/pantalla_principal_Usuario.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/gestionar.css?v=<?php echo time(); ?>">
</head>
<body>
 
<header class="navbar">
    <span class="logo">EVAL</span>
    <a href="Acciones/cerrar_sesion.php" class="btn-logout">Cerrar sesión</a>
</header>
 
<nav class="breadcrumb">
    📍 Inicio / <a href="pantalla_principal_Admin.php">Panel Admin</a> / Gestionar Administradores
</nav>
 
<div class="workspace">
 
    <!-- Panel izquierdo: tabla -->
    <section class="panel-tabla">
 
        <div class="panel-header">
            <h2>👥 Administradores registrados</h2>
        </div>
 
        <div class="tabla-scroll">
            <?php if (empty($admins)): ?>
                <div class="tabla-vacia">
                    <p>No hay administradores registrados aún.</p>
                </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Registrado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td class="td-id"><?php echo htmlspecialchars($admin['ID']); ?></td>
                        <td class="td-nombre"><?php echo htmlspecialchars($admin['nombre_usuario']); ?></td>
                        <td class="td-correo"><?php echo htmlspecialchars($admin['correo_electronico']); ?></td>
                        <td class="td-fecha">
                            <?php
                                $fecha = $admin['fecha_registro'] ?? null;
                                echo $fecha ? date('d/m/Y', strtotime($fecha)) : '—';
                            ?>
                        </td>
                        <td>
                            <form method="POST" action="Acciones/eliminar_admin.php"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar a <?php echo htmlspecialchars($admin['nombre_usuario']); ?>?')">
                                <input type="hidden" name="id" value="<?php echo (int)$admin['ID']; ?>">
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
            Total: <strong><?php echo count($admins); ?></strong> administrador<?php echo count($admins) !== 1 ? 'es' : ''; ?>
        </div>
 
    </section>
 
    <!-- Panel derecho: formulario -->
    <section class="panel-form">
 
        <div class="panel-header">
            <h2>➕ Agregar administrador</h2>
        </div>
 
        <div class="form-body">
            <p class="subtitulo">Completa los campos para registrar un nuevo administrador en el sistema.</p>
 
            <form method="POST" action="Acciones/agregar_admin.php" id="formAdmin">
 
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre"
                           placeholder="Ej: María García" required>
                </div>
  
                <div class="form-group">
                    <label for="nombre_usuario">Nombre_usuario</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario"
                           placeholder="Ej: magarcia" required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo_electronico"
                           placeholder="admin@institución.edu.co" required>
                </div>
 
                <hr class="divider">
 
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="contrasenia"
                           placeholder="Mínimo 8 caracteres" required>
                    <p class="hint">Usa al menos 8 caracteres.</p>
                </div>
 
                <div class="form-group">
                    <label for="password2">Confirmar contraseña</label>
                    <input type="password" id="password2" name="contrasenia_confirmar"
                           placeholder="Repite la contraseña" required>
                </div>
 
            </form>
        </div>
 
        <div class="form-footer">
            <button type="button" class="btn-limpiar"
                    onclick="document.getElementById('formAdmin').reset()">Limpiar</button>
            <button type="submit" form="formAdmin" class="btn-guardar">Registrar administrador</button>
        </div>
 
    </section>
 
</div>
 
</body>
</html>