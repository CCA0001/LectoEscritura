<?php

require_once(__DIR__ . "/../config/ia.php");

class IAEscrituraService {

    /**
     * Evalúa un archivo con Gemini.
     *
     * @param string $rutaArchivo Ruta absoluta al PDF
     * @return array Evaluación con las notas, o ["error" => "..."] si falla
     */
    public function evaluarArchivo($rutaArchivo){

        if(!file_exists($rutaArchivo)){

            return [
                "error" => "Archivo no encontrado"
            ];
        }

        $archivoBase64 =
            base64_encode(
                file_get_contents($rutaArchivo)
            );

        $mime =
            mime_content_type(
                $rutaArchivo
            );

        $prompt = "

        Evalúa este texto académico.

        Devuelve ÚNICAMENTE un JSON válido con:

        {
            \"coherencia\": 0-10,
            \"cohesion\": 0-10,
            \"gramatica\": 0-10,
            \"argumentacion\": 0-10,
            \"estructura\": 0-10,
            \"puntaje_promedio\": 0-10,
            \"retroalimentacion\": \"texto\"
        }

        ";

        $body = [

            "contents" => [

                [

                    "parts" => [

                        [
                            "text" => $prompt
                        ],

                        [

                            "inline_data" => [

                                "mime_type" => $mime,

                                "data" => $archivoBase64
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $url =
            "https://generativelanguage.googleapis.com/v1beta/models/" .
            GEMINI_MODEL .
            ":generateContent?key=" .
            GEMINI_API_KEY;

        $respuesta =
            $this->enviarPeticion(
                $url,
                $body
            );

        if($respuesta === false){

            return [
                "error" =>
                    "No se pudo conectar con el servicio de IA"
            ];
        }

        file_put_contents(
            __DIR__ . "/debug_gemini.txt",
            $respuesta
        );

        $resultado =
            json_decode(
                $respuesta,
                true
            );

        if(
            !isset(
                $resultado['candidates'][0]['content']['parts'][0]['text']
            )
        ){

            // La API suele explicar el motivo (key inválida, cuota,
            // contenido bloqueado). Se devuelve tal cual para poder
            // diagnosticar sin adivinar.
            $mensajeApi =
                $resultado['error']['message'] ??
                "Respuesta inválida de Gemini";

            return [
                "error" => $mensajeApi
            ];
        }

        $textoIA =
            $resultado['candidates'][0]['content']['parts'][0]['text'];

        $textoIA =
            trim($textoIA);

        $textoIA =
            preg_replace(
                '/```json|```/',
                '',
                $textoIA
            );

        $evaluacion =
            json_decode(
                $textoIA,
                true
            );

        if(!$evaluacion){

            return [
                "error" => "No se pudo interpretar JSON IA"
            ];
        }

        return $evaluacion;
    }

    /**
     * Envía el POST a la API.
     *
     * Usa ext-curl cuando está disponible y, si no, cae a streams de
     * PHP. Esto último es importante: en algunos servidores (por
     * ejemplo el Apache de XAMPP) la extensión php_curl.dll no carga
     * por conflicto de DLLs con OpenSSL, y la app se caía entera.
     * Con streams solo hace falta ext-openssl.
     *
     * @return string|false Cuerpo de la respuesta, o false si falló
     */
    private function enviarPeticion($url, $body){

        $json =
            json_encode($body);

        if(function_exists('curl_init')){

            $respuesta =
                $this->enviarConCurl(
                    $url,
                    $json
                );

            if($respuesta !== false){
                return $respuesta;
            }
        }

        return $this->enviarConStreams(
            $url,
            $json
        );
    }

    private function enviarConCurl($url, $json){

        $curl = curl_init();

        curl_setopt_array($curl, [

            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_TIMEOUT => 60,

            CURLOPT_HTTPHEADER => [

                "Content-Type: application/json"
            ],

            CURLOPT_POSTFIELDS =>
                $json
        ]);

        $respuesta =
            curl_exec($curl);

        if(curl_errno($curl)){

            curl_close($curl);

            return false;
        }

        curl_close($curl);

        return $respuesta;
    }

    private function enviarConStreams($url, $json){

        $contexto =
            stream_context_create([

                "http" => [

                    "method" => "POST",

                    "header" => "Content-Type: application/json\r\n",

                    "content" => $json,

                    "timeout" => 60,

                    "ignore_errors" => true
                ],

                "ssl" => [

                    "verify_peer" => true,

                    "verify_peer_name" => true
                ]
            ]);

        $respuesta =
            @file_get_contents(
                $url,
                false,
                $contexto
            );

        if($respuesta === false){

            return false;
        }

        return $respuesta;
    }
}
