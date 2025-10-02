<?php
$api_url = 'https://api.dolarvzla.com/public/exchange-rate';

$url1 = "https://s3.amazonaws.com/dolartoday/data.json";

$url2 = "https://api.exchangedyn.com/markets/quotes/usdves/bcv";

// 2. Inicializar cURL
$ch = curl_init();

// 3. Configurar opciones de cURL
curl_setopt($ch, CURLOPT_URL, $api_url);

// Establecer cabeceras (opcional, pero buena práctica)
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json', // Decimos que esperamos una respuesta JSON
]);
// Devolver la respuesta como una cadena en lugar de imprimirla directamente
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 4. Ejecutar la petición
$response = curl_exec($ch);

// 5. Manejo de errores de cURL
if (curl_errno($ch)) {
    echo "❌ Error de conexión cURL: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit;
}

// 6. Cerrar la sesión cURL
curl_close($ch);

// 7. Decodificar la respuesta JSON
$data = json_decode($response, true); // El 'true' convierte el JSON en un array asociativo

// 8. Verificar si la decodificación fue exitosa y la data es válida
if (json_last_error() !== JSON_ERROR_NONE || !$data) {
    echo "❌ Error al decodificar la respuesta JSON o respuesta vacía.\n";
    echo "Respuesta cruda: " . $response . "\n";
} else {
    echo "✅ Conexión exitosa a la API del Dólar VZLA.\n";
    
    // --- ACCEDER A LOS DATOS ---
    
    // La estructura de esta API es simple. Asumiendo que devuelve algo como:
    // {"rate": 36.5, "date": "2024-01-01", "source": "BCV"}
    
    if (isset($data['rate'])) {
        $tasa_cambio = number_format($data['rate'], 2, ',', '.');
        $fecha = $data['date'] ?? 'Fecha no disponible';
        
        echo "---------------------------------------------------\n";
        echo "💵 Tasa de Cambio (BCV/Oficial): *Bs. {$tasa_cambio}*\n";
        echo "🗓 Fecha de Actualización: *{$fecha}*\n";
        echo "---------------------------------------------------\n";
    } else {
        // Muestra la estructura de la respuesta si no encuentras la clave 'rate'
        echo "⚠ La clave 'rate' no se encontró. Estructura de la respuesta:\n";
        print_r($data);
    }
}
?>