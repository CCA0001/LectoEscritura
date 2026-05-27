<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eva - Iniciar Sesión</title>
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>

    <div class="login-card">
        <h2>Iniciar Sesión (Administradores) </h2>
        
        <form id="formLogin">
            <div class="input-group">
                <label>Correo Electrónico:</label>
                <input id="correo" type="email" required>
            </div>
            
            <div class="input-group">
                <label>Contraseña:</label>
                <input id="contrasenia" type="password" placeholder="Tu contraseña" required>
            </div>
            
            <button type="submit" class="btn-ingresar">Ingresar</button>
        </form>

        <p class="footer-text">
            ¿Eres usuario? <a href="login_admin.php">Acceso usuarios</a>
        </p>
    </div>


    <script src="../public/JS/Auth/obtenerDatosLoginAdmin.js"></script>
</body>
</html>