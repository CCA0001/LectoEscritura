<?php

require_once("../config/ia.php");

class IAEscrituraService {

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
            "https://generativelanguage.googleapis.com/v1beta/models/"
            . GEMINI_MODEL .
            ":generateContent?key="
            . GEMINI_API_KEY;

        $curl = curl_init();

        curl_setopt_array($curl, [

            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_HTTPHEADER => [

                "Content-Type: application/json"
            ],

            CURLOPT_POSTFIELDS =>
                json_encode($body)
        ]);

        $response =
            curl_exec($curl);

        if(curl_errno($curl)){

            return [
                "error" =>
                    curl_error($curl)
            ];
        }

        curl_close($curl);

        file_put_contents(
            __DIR__ . "/debug_gemini.txt",
            $response
        );

        $resultado =
            json_decode(
                $response,
                true
            );

        if(
            !isset(
                $resultado['candidates'][0]['content']['parts'][0]['text']
            )
        ){

            return [
                "error" =>
                    "Respuesta inválida de Gemini"
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
                "error" =>
                    "No se pudo interpretar JSON IA"
            ];
        }

        return $evaluacion;
    }
}