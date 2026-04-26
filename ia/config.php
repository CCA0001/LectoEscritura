<?php
$api_key = "AIzaSyD0j0XGTj6RNMgRcqFJC6eSNBMebyyUL-U"; 

function evaluarPDFconGemini($ruta_pdf, $api_key) {
    if (!file_exists($ruta_pdf)) {
        return ['error' => "El archivo no existe en: $ruta_pdf"];
    }
    
    $pdf_content = file_get_contents($ruta_pdf);
    if ($pdf_content === false) {
        return ['error' => "No se pudo leer el archivo PDF"];
    }
    
    $base64_pdf = base64_encode($pdf_content);
    
    $prompt = "Eres un profesor experto evaluando textos academicos.
    
    Analiza el PDF y califica CADA criterio del 1 al 10:
    1. Coherencia
    2. Cohesion
    3. Gramatica
    4. Argumentacion
    5. Estructura
    
    Responde SOLO este JSON sin texto adicional:
    {
        \"coherencia\": 8,
        \"cohesion\": 7,
        \"gramatica\": 9,
        \"argumentacion\": 6,
        \"estructura\": 8,
        \"retroalimentacion\": \"Breve resumen.\",
        \"puntaje_promedio\": 7.6
    }";
    
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt],
                    ['inline_data' => ['mime_type' => 'application/pdf', 'data' => $base64_pdf]]
                ]
            ]
        ]
    ];
    
    $ch = curl_init("https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=$api_key");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    if ($curl_error) {
        return ['error' => "Error de cURL: $curl_error"];
    }
    
    if ($http_code != 200) {
        return ['error' => "HTTP $http_code: $response"];
    }
    
    $resultado = json_decode($response, true);
    
    if (isset($resultado['candidates'][0]['content']['parts'][0]['text'])) {
        $texto = $resultado['candidates'][0]['content']['parts'][0]['text'];
        $texto = preg_replace('/```json\s*|\s*```/', '', $texto);
        $texto = trim($texto);
        return json_decode($texto, true);
    }
    
    return ['error' => "Respuesta inesperada de la API", 'response' => $response];
}
?>