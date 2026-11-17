<?php

/**
 * Guardas de acceso por rol.
 *
 * Los controladores NO deben responder datos sensibles sin pasar por acá.
 * Antes, el dispatcher `method_exists($controller, $accion)` dejaba-exposed
 * cualquier método público: por eso el panel de administración quedaba
 * abierto a cualquiera que hiciera un POST.
 *
 * Uso en controladores (respuestas JSON):
 *     Guardias::exigirAdmin();
 *
 * Uso en vistas que devuelven HTML:
 *     Guardias::exigirAdmin(false, "../views/login_admin.php");
 */

require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/sesion.php");

class Guardias {

    public static function asegurarSesion() {

        Sesion::iniciar();
    }

    public static function esUsuario() {

        self::asegurarSesion();

        return isset($_SESSION['id_usuario']);
    }

    public static function esAdmin() {

        self::asegurarSesion();

        return isset($_SESSION['id_admin']);
    }

    public static function exigirUsuario(
        $json = true,
        $login = "../views/login.html"
    ) {

        if(self::esUsuario()) {
            return true;
        }

        self::denegar(
            $json,
            "Usuario no autenticado",
            $login
        );

        return false;
    }

    public static function exigirAdmin(
        $json = true,
        $login = "../views/login_admin.php"
    ) {

        if(self::esAdmin()) {
            return true;
        }

        self::denegar(
            $json,
            "Administrador no autenticado",
            $login
        );

        return false;
    }

    private static function denegar(
        $json,
        $mensaje,
        $login
    ) {

        if($json) {

            http_response_code(401);

            header("Content-Type: application/json");

            echo json_encode([
                "success" => false,
                "mensaje" => $mensaje
            ]);

        } else {

            header("Location: " . $login);

        }

        exit;
    }
}
