<?php

/**
 * Arranque y cierre únicos de la sesión.
 *
 * Reemplaza los `session_start()` sueltos para que TODAS las sesiones salgan
 * con los mismos atributos de cookie:
 *
 *   - HttpOnly : el JavaScript de la página no puede leer el PHPSESSID, así
 *                que un XSS no alcanza para robar la sesión.
 *   - SameSite=Lax : la cookie no viaja en peticiones de otros sitios
 *                (mitiga CSRF sin romper la navegación normal).
 *   - use_strict_mode : PHP rechaza identificadores de sesión que él mismo no
 *                generó, evitando que alguien "plante" uno fijo.
 *
 * Uso en controladores (antes de router.php):
 *     require_once(__DIR__ . "/../config/sesion.php");
 *     Sesion::iniciar();
 */

class Sesion {

    public static function iniciar() {

        if(session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        ini_set("session.use_strict_mode", "1");

        session_set_cookie_params([
            "lifetime" => 0,
            "path" => "/",
            "domain" => "",
            "secure" => self::esHttps(),
            "httponly" => true,
            "samesite" => "Lax"
        ]);

        session_start();
    }

    /**
     * Deja una sola cabecera Set-Cookie por sesión.
     *
     * PHP manda un Set-Cookie al abrir la sesión y otro al regenerar el id
     * (login). Salen dos PHPSESSID con el mismo nombre: el primero es la
     * sesión vieja, que `session_regenerate_id(true)` ya destruyó. Los
     * navegadores guardan el último, pero un proxy o un túnel (cloudflared,
     * ngrok) puede quedarse con el primero y dejar al usuario sin sesión.
     * Se repite solo la cookie de la sesión válida.
     */
    public static function fijarCookieDeSesion() {

        if(session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        header_remove("Set-Cookie");

        $parametros = session_get_cookie_params();

        setcookie(session_name(), session_id(), [
            "expires" => $parametros["lifetime"] > 0
                ? time() + $parametros["lifetime"]
                : 0,
            "path" => $parametros["path"],
            "domain" => $parametros["domain"],
            "secure" => $parametros["secure"],
            "httponly" => $parametros["httponly"],
            "samesite" => $parametros["samesite"] ?: "Lax"
        ]);
    }

    /**
     * Cierra la sesión y borra la cookie del navegador.
     *
     * `session_destroy()` elimina los datos en el servidor pero deja el
     * PHPSESSID en el navegador apuntando a una sesión que ya no existe.
     */
    public static function cerrar() {

        if(session_status() === PHP_SESSION_ACTIVE) {

            session_unset();

            session_destroy();
        }

        $parametros = session_get_cookie_params();

        setcookie(session_name(), "", [
            "expires" => time() - 3600,
            "path" => $parametros["path"],
            "domain" => $parametros["domain"],
            "secure" => $parametros["secure"],
            "httponly" => $parametros["httponly"],
            "samesite" => $parametros["samesite"] ?: "Lax"
        ]);
    }

    private static function esHttps() {

        if(!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
            return true;
        }

        // Apache detrás de un túnel (cloudflared/ngrok) cuenta como HTTPS
        // aunque el tramo local sea HTTP.
        return isset($_SERVER["HTTP_X_FORWARDED_PROTO"])
            && strtolower($_SERVER["HTTP_X_FORWARDED_PROTO"]) === "https";
    }
}
