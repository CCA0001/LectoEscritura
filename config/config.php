<?php

/**
 * Cargador de configuración basado en archivo .env, sin dependencias externas.
 *
 * Prioridad de resolución de cada clave:
 *   1) Variable de entorno real del proceso (getenv)
 *   2) Archivo .env en la raíz del proyecto
 *   3) Valor por defecto pasado en la llamada
 *
 * El archivo .env NO se versiona (ver .gitignore). Por eso los secretos
 * no deben escribirse nunca dentro de config/ ni de services/.
 */
class Config {

    private static $valores = [];
    private static $cargado = false;

    public static function cargar($rutaRaiz = null) {

        if(self::$cargado) {
            return;
        }

        self::$cargado = true;

        if($rutaRaiz === null) {
            $rutaRaiz = dirname(__DIR__);
        }

        $archivoEnv = $rutaRaiz . "/.env";

        if(!is_readable($archivoEnv)) {
            return;
        }

        $lineas = file(
            $archivoEnv,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        foreach($lineas as $linea) {

            $linea = trim($linea);

            if($linea === "" || $linea[0] === "#") {
                continue;
            }

            if(strpos($linea, "=") === false) {
                continue;
            }

            list($clave, $valor) = explode("=", $linea, 2);

            $clave = trim($clave);
            $valor = trim($valor);

            if(
                strlen($valor) >= 2 &&
                ($valor[0] === '"' || $valor[0] === "'") &&
                substr($valor, -1) === $valor[0]
            ) {
                $valor = substr($valor, 1, -1);
            }

            self::$valores[$clave] = $valor;
        }
    }

    public static function get($clave, $defecto = null) {

        self::cargar();

        $delEntorno = getenv($clave);

        if($delEntorno !== false && $delEntorno !== "") {
            return $delEntorno;
        }

        if(array_key_exists($clave, self::$valores)) {
            return self::$valores[$clave];
        }

        return $defecto;
    }

    /**
     * Obtiene un valor obligatorio. Si falta, detiene el request con un
     * mensaje claro en vez de dejar que la app reviente más adelante.
     */
    public static function obtener($clave) {

        $valor = self::get($clave);

        if($valor === null || $valor === "") {
            http_response_code(500);

            echo json_encode([
                "success" => false,
                "mensaje" =>
                    "Falta la variable de configuración " . $clave .
                    ". Revisá el archivo .env"
            ]);

            exit;
        }

        return $valor;
    }

    public static function tiene($clave) {

        $valor = self::get($clave);

        return $valor !== null && $valor !== "";
    }
}
