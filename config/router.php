<?php

/**
 * Resolución y validación de la acción solicitada.
 *
 * Antes, el dispatcher usaba `method_exists($controller, $accion)`, lo
 * que dejaba expuestos TODOS los métodos públicos del controlador
 * (incluidos los administrativos). Ahora cada controlador declara
 * explícitamente qué acciones acepta y con qué rol.
 *
 * Uso típico en un controlador:
 *
 *     $accion = Router::resolver(
 *         "listarTextos",              // acción por defecto
 *         [                             // lista blanca: acción => rol
 *             "listarTextos"     => "admin",
 *             "agregarTexto"     => "admin",
 *             "mostrarGestionar" => "adminPagina"
 *         ]
 *     );
 *
 * Roles disponibles:
 *   "publico"     → cualquiera
 *   "admin"       → sesión de administrador, respuesta JSON 401
 *   "adminPagina" → sesión de administrador, redirección al login
 *   "usuario"     → sesión de estudiante, respuesta JSON 401
 *   "usuarioPagina" → sesión de estudiante, redirección al login
 */

require_once(__DIR__ . "/guardias.php");

class Router {

    /**
     * @param string $accionPorDefecto   Acción usada si no se pasa ?accion=
     * @param array  $accionesPermitidas  Lista blanca: acción => rol
     * @return string La acción validada
     */
    public static function resolver(
        $accionPorDefecto,
        $accionesPermitidas
    ) {

        $accion =
            $_GET['accion'] ?? $accionPorDefecto;

        if(!isset($accionesPermitidas[$accion])) {

            http_response_code(400);

            header("Content-Type: application/json");

            echo json_encode([
                "success" => false,
                "mensaje" => "Acción no válida"
            ]);

            exit;
        }

        // La autorización se resuelve ANTES de abrir la conexión con la
        // base de datos: así una petición no autorizada se rechaza
        // aunque la BD esté caída, y no se filtra información del sistema.
        switch($accionesPermitidas[$accion]) {

            case "admin":

                Guardias::exigirAdmin();

                break;

            case "adminPagina":

                Guardias::exigirAdmin(false);

                break;

            case "usuario":

                Guardias::exigirUsuario();

                break;

            case "usuarioPagina":

                Guardias::exigirUsuario(false);

                break;
        }

        return $accion;
    }
}
