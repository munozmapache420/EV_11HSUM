# MOONSHINE — Sistema de inventario

## Requisitos

- XAMPP con Apache y MySQL activos.
- PHP con las extensiones `PDO` y `pdo_mysql` habilitadas.
- Base de datos `moonshine_v2` y tabla `producto`.

## Configuración de MySQL

El proyecto lee las credenciales desde `config/.env`. Para XAMPP con usuario `root` y contraseña vacía, el archivo queda así:

```ini
DB_HOST=localhost
DB_PORT=3306
DB_NAME=moonshine_v2
DB_USER=root
DB_PASSWORD=
```

Si tu instalación de XAMPP usa otra contraseña o puerto, modifica esos valores. La conexión utiliza PDO y muestra un mensaje en pantalla cuando no puede conectar.

## Estructura MVC de productos

```text
EV_HU/
├── app/
│   ├── controllers/ProductoController.php
│   ├── helpers.php
│   └── models/Producto.php
├── config/
│   ├── database.php
│   ├── .env
│   └── .env.example
├── views/productos/
│   ├── index.php
│   ├── crear.php
│   └── editar.php
├── public/js/
├── css/styles.css
├── dashboard.html
├── index.html
├── index.php
└── productos.php
```

## Campos utilizados

El modelo consulta y modifica los campos reales suministrados para `producto`: `id`, `nombre`, `descripcion`, `categoria_id`, `id_inventario`, `stock_minimo`, `estado`, `precio_compra`, `precio_venta` y `stock`. No se agregaron columnas de imagen, marca o fecha.

Los IDs de categoría e inventario que se ingresen en el formulario deben existir en sus respectivas tablas relacionadas. El campo `estado` debe contener un valor permitido por la definición de tu base de datos.

## Cómo abrir el sistema

1. Copia la carpeta `EV_HU` dentro de `C:\xampp\htdocs\`.
2. Inicia Apache y MySQL en XAMPP.
3. Para iniciar sesión, abre `http://localhost/EV_HU/index.html`.
4. Después de iniciar sesión, entra a **Inventario** en `dashboard.html`. La tabla del dashboard consulta `productos.php`, que devuelve los registros de MySQL en JSON.
5. Usa **Administrar productos (crear, editar y eliminar)** para abrir el módulo MVC.

También puedes abrir directamente `http://localhost/EV_HU/index.php?action=productos`.

## Acciones MVC

- `index.php?action=productos`: lista los registros de la tabla `producto`.
- `index.php?action=crear`: muestra únicamente el formulario de creación.
- `index.php?action=guardar`: recibe el formulario de creación mediante POST.
- `index.php?action=editar&id=ID`: muestra únicamente el formulario de edición.
- `index.php?action=actualizar&id=ID`: actualiza mediante POST.
- `index.php?action=eliminar`: elimina mediante POST.

`productos.php` es el endpoint JSON que consume el `public/js/script.js` existente. No se modificó ningún archivo `.js` del ZIP original.

## Si no conecta

En XAMPP abre `php.ini`, confirma que `extension=pdo_mysql` esté habilitada y reinicia Apache. Comprueba también que MySQL esté iniciado, que `moonshine_v2` exista y que los nombres de las columnas de `producto` coincidan exactamente con los indicados arriba.
