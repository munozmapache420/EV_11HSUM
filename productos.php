<?php

require_once __DIR__ . '/app/models/Producto.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $modelo = new Producto();
    $productos = $modelo->getAll();

    echo json_encode($productos, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $e) {
    error_log('Moonshine API productos: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(
        ['error' => 'No fue posible consultar los productos de moonshine_v2. Verifica la conexión con MySQL.'],
        JSON_UNESCAPED_UNICODE
    );
}
