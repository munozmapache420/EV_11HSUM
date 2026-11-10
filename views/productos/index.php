<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonshine | Administración de productos</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .mvc-actions { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
        .mvc-button { display:inline-flex; align-items:center; gap:8px; padding:11px 15px; border:0; border-radius:8px; background:#6c3fa0; color:#fff; text-decoration:none; font-weight:700; cursor:pointer; }
        .mvc-button:hover { background:#432365; color:#fff; }
        .mvc-button.secondary { background:#eee5f8; color:#432365; }
        .mvc-button.danger { background:#fde8e8; color:#a32828; }
        .mvc-message { padding:13px 16px; margin:16px 0; border-radius:8px; background:#e4f5ed; color:#17633f; }
        .mvc-error { padding:13px 16px; margin:16px 0; border-radius:8px; background:#fde8e8; color:#922c2c; }
        .mvc-top { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:18px; }
        .mvc-table { min-width:1100px; }
        .mvc-table td { vertical-align:middle; }
        .mvc-empty { text-align:center; padding:24px; }
    </style>
</head>
<body class="app-body">
<div class="app">
    <aside class="sidebar">
        <div class="brand">
            <img src="img.jpg/imgLogoMoonshine.jpeg" alt="Moonshine">
            <span>Moonshine</span>
        </div>
        <nav>
            <a class="nav-link" href="dashboard.html"><i class="fa-solid fa-house"></i> Inicio</a>
            <a class="nav-link active" href="index.php?action=productos"><i class="fa-solid fa-boxes-stacked"></i> Inventario</a>
            <a class="nav-link" href="index.php?action=crear"><i class="fa-solid fa-circle-plus"></i> Registrar producto</a>
        </nav>
    </aside>

    <main class="content">
        <header class="content-header">
            <div>
                <p class="eyebrow">Administración</p>
                <h1>Productos de Moonshine</h1>
                <p>Registros consultados directamente desde la tabla producto de moonshine_v2.</p>
            </div>
        </header>

        <?php if (!empty($mensaje)): ?>
            <div class="mvc-message"><?= e($mensaje) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="mvc-error"><?= e($error) ?></div>
        <?php endif; ?>

        <section class="panel">
            <div class="mvc-top">
                <div>
                    <h2><i class="fa-solid fa-boxes-stacked"></i> Inventario registrado</h2>
                    <p>Total de registros: <strong><?= count($productos) ?></strong></p>
                </div>
                <div class="mvc-actions">
                    <a class="mvc-button" href="index.php?action=crear">
                        <i class="fa-solid fa-plus"></i> Crear producto
                    </a>
                    <a class="mvc-button secondary" href="dashboard.html">
                        <i class="fa-solid fa-arrow-left"></i> Volver al dashboard
                    </a>
                </div>
            </div>

            <div class="tabla-container">
                <table class="mvc-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría ID</th>
                            <th>Inventario ID</th>
                            <th>Precio compra</th>
                            <th>Precio venta</th>
                            <th>Stock</th>
                            <th>Stock mínimo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($productos)): ?>
                        <tr><td class="mvc-empty" colspan="11">No hay productos registrados en la tabla producto.</td></tr>
                    <?php else: ?>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= e($producto['id']) ?></td>
                                <td><?= e($producto['nombre']) ?></td>
                                <td><?= e($producto['descripcion']) ?></td>
                                <td><?= e($producto['categoria_id']) ?></td>
                                <td><?= e($producto['id_inventario']) ?></td>
                                <td><?= e(number_format((float)$producto['precio_compra'], 0, ',', '.')) ?></td>
                                <td><?= e(number_format((float)$producto['precio_venta'], 0, ',', '.')) ?></td>
                                <td><?= e($producto['stock']) ?></td>
                                <td><?= e($producto['stock_minimo']) ?></td>
                                <td><?= e($producto['estado']) ?></td>
                                <td>
                                    <div class="mvc-actions">
                                        <a class="mvc-button secondary" href="index.php?action=editar&amp;id=<?= e($producto['id']) ?>">
                                            <i class="fa-solid fa-pen"></i> Editar
                                        </a>
                                        <form method="POST" action="index.php?action=eliminar" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                                            <input type="hidden" name="id" value="<?= e($producto['id']) ?>">
                                            <button class="mvc-button danger" type="submit">
                                                <i class="fa-solid fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
