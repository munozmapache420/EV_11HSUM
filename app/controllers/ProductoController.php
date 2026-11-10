<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../helpers.php';

class ProductoController
{
    private $modelo;
    private $errorConexion = '';

    public function __construct()
    {
        try {
            $this->modelo = new Producto();
        } catch (Throwable $e) {
            $this->modelo = null;
            $this->errorConexion = $e->getMessage();
            error_log('Moonshine inicializar modelo: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $productos = [];
        $error = $_GET['error'] ?? '';
        $mensaje = $_GET['mensaje'] ?? '';

        try {
            if (!$this->modelo) {
                throw new RuntimeException($this->errorConexion ?: 'No hay conexión con la base de datos.');
            }
            $productos = $this->modelo->getAll();
        } catch (Throwable $e) {
            error_log('Moonshine productos index: ' . $e->getMessage());
            $error = $e->getMessage();
        }

        require __DIR__ . '/../../views/productos/index.php';
    }

    public function crear()
    {
        $datos = [
            'nombre' => '',
            'descripcion' => '',
            'categoria_id' => '',
            'id_inventario' => '',
            'precio_compra' => '',
            'precio_venta' => '',
            'stock' => '',
            'stock_minimo' => '',
            'estado' => 'Disponible'
        ];
        $error = '';

        require __DIR__ . '/../../views/productos/crear.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('productos');
        }

        $datos = $this->recogerDatos();
        $error = $this->validar($datos);

        if ($error !== '') {
            require __DIR__ . '/../../views/productos/crear.php';
            return;
        }

        try {
            if (!$this->modelo) {
                throw new RuntimeException($this->errorConexion ?: 'No hay conexión con la base de datos.');
            }
            $this->modelo->guardar($datos);
            $this->redirigir('productos', 'Producto creado correctamente.');
        } catch (Throwable $e) {
            error_log('Moonshine guardar producto: ' . $e->getMessage());
            $error = 'No se pudo guardar el producto. Verifica que la categoría y el inventario existan y que los datos sean válidos.';
            require __DIR__ . '/../../views/productos/crear.php';
        }
    }

    public function editar()
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            $this->redirigir('productos', '', 'El identificador del producto no es válido.');
        }

        try {
            if (!$this->modelo) {
                throw new RuntimeException($this->errorConexion ?: 'No hay conexión con la base de datos.');
            }
            $producto = $this->modelo->getById($id);
        } catch (Throwable $e) {
            error_log('Moonshine buscar producto: ' . $e->getMessage());
            $this->redirigir('productos', '', 'No fue posible consultar el producto.');
        }

        if (!$producto) {
            $this->redirigir('productos', '', 'El producto solicitado no existe.');
        }

        $datos = $producto;
        $error = '';
        require __DIR__ . '/../../views/productos/editar.php';
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('productos');
        }

        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            $this->redirigir('productos', '', 'El identificador del producto no es válido.');
        }

        $datos = $this->recogerDatos();
        $error = $this->validar($datos);

        if ($error !== '') {
            $datos['id'] = $id;
            require __DIR__ . '/../../views/productos/editar.php';
            return;
        }

        try {
            if (!$this->modelo) {
                throw new RuntimeException($this->errorConexion ?: 'No hay conexión con la base de datos.');
            }
            $producto = $this->modelo->getById($id);
            if (!$producto) {
                $this->redirigir('productos', '', 'El producto que intentas actualizar ya no existe.');
            }

            $this->modelo->actualizar($id, $datos);
            $this->redirigir('productos', 'Producto actualizado correctamente.');
        } catch (Throwable $e) {
            error_log('Moonshine actualizar producto: ' . $e->getMessage());
            $datos['id'] = $id;
            $error = 'No se pudo actualizar el producto. Verifica los datos y las relaciones de categoría e inventario.';
            require __DIR__ . '/../../views/productos/editar.php';
        }
    }

    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('productos');
        }

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            $this->redirigir('productos', '', 'El identificador del producto no es válido.');
        }

        try {
            if (!$this->modelo) {
                throw new RuntimeException($this->errorConexion ?: 'No hay conexión con la base de datos.');
            }
            $producto = $this->modelo->getById($id);
            if (!$producto) {
                $this->redirigir('productos', '', 'El producto ya no existe.');
            }

            $this->modelo->eliminar($id);
            $this->redirigir('productos', 'Producto eliminado correctamente.');
        } catch (Throwable $e) {
            error_log('Moonshine eliminar producto: ' . $e->getMessage());
            $this->redirigir('productos', '', 'No se pudo eliminar. Es posible que el producto esté relacionado con ventas o movimientos de inventario.');
        }
    }

    private function recogerDatos()
    {
        return [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'categoria_id' => trim($_POST['categoria_id'] ?? ''),
            'id_inventario' => trim($_POST['id_inventario'] ?? ''),
            'precio_compra' => trim($_POST['precio_compra'] ?? ''),
            'precio_venta' => trim($_POST['precio_venta'] ?? ''),
            'stock' => trim($_POST['stock'] ?? ''),
            'stock_minimo' => trim($_POST['stock_minimo'] ?? ''),
            'estado' => trim($_POST['estado'] ?? '')
        ];
    }

    private function validar(&$datos)
    {
        if ($datos['nombre'] === '') {
            return 'El nombre del producto es obligatorio.';
        }

        foreach (['categoria_id', 'id_inventario'] as $campo) {
            if (filter_var($datos[$campo], FILTER_VALIDATE_INT) === false || (int)$datos[$campo] < 1) {
                return 'El ID de categoría y el ID de inventario deben ser enteros mayores que cero.';
            }
        }

        foreach (['stock', 'stock_minimo'] as $campo) {
            if (filter_var($datos[$campo], FILTER_VALIDATE_INT) === false || (int)$datos[$campo] < 0) {
                return 'El stock y el stock mínimo deben ser números enteros iguales o mayores que cero.';
            }
        }

        foreach (['precio_compra', 'precio_venta'] as $campo) {
            if ($datos[$campo] === '' || !is_numeric($datos[$campo]) || (float)$datos[$campo] < 0) {
                return 'Los precios deben ser números iguales o mayores que cero.';
            }
        }

        if ($datos['estado'] === '') {
            return 'El estado del producto es obligatorio.';
        }

        $datos['categoria_id'] = (int)$datos['categoria_id'];
        $datos['id_inventario'] = (int)$datos['id_inventario'];
        $datos['stock'] = (int)$datos['stock'];
        $datos['stock_minimo'] = (int)$datos['stock_minimo'];
        $datos['precio_compra'] = (float)$datos['precio_compra'];
        $datos['precio_venta'] = (float)$datos['precio_venta'];

        return '';
    }

    private function redirigir($accion, $mensaje = '', $error = '')
    {
        $parametros = ['action' => $accion];
        if ($mensaje !== '') {
            $parametros['mensaje'] = $mensaje;
        }
        if ($error !== '') {
            $parametros['error'] = $error;
        }

        header('Location: index.php?' . http_build_query($parametros));
        exit;
    }
}
