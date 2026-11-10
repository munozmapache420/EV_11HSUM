<?php

require_once __DIR__ . '/app/controllers/ProductoController.php';

$controller = new ProductoController();
$action = $_GET['action'] ?? 'productos';

switch ($action) {
    case 'crear':
        $controller->crear();
        break;

    case 'guardar':
        $controller->guardar();
        break;

    case 'editar':
        $controller->editar();
        break;

    case 'actualizar':
        $controller->actualizar();
        break;

    case 'eliminar':
        $controller->eliminar();
        break;

    case 'productos':
    default:
        $controller->index();
        break;
}
