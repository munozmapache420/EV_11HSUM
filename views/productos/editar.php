<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonshine | Editar producto</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .mvc-form { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
        .mvc-field { display:flex; flex-direction:column; gap:7px; }
        .mvc-field.full { grid-column:1/-1; }
        .mvc-field label { font-weight:700; color:#432365; font-size:13px; }
        .mvc-field input, .mvc-field textarea { width:100%; padding:12px 13px; border:1px solid #ddd3e8; border-radius:8px; font:inherit; box-sizing:border-box; }
        .mvc-field textarea { min-height:95px; resize:vertical; }
        .mvc-actions { display:flex; flex-wrap:wrap; gap:10px; margin-top:20px; }
        .mvc-button { display:inline-flex; align-items:center; gap:8px; padding:12px 17px; border:0; border-radius:8px; background:#6c3fa0; color:white; text-decoration:none; font-weight:700; cursor:pointer; }
        .mvc-button:hover { background:#432365; color:white; }
        .mvc-button.secondary { background:#eee5f8; color:#432365; }
        .mvc-error { padding:13px 16px; margin:16px 0; border-radius:8px; background:#fde8e8; color:#922c2c; }
        @media(max-width:700px) { .mvc-form { grid-template-columns:1fr; } .mvc-field.full { grid-column:auto; } }
    </style>
</head>
<body class="app-body">
<div class="app">
    <aside class="sidebar">
        <div class="brand"><img src="img.jpg/imgLogoMoonshine.jpeg" alt="Moonshine"><span>Moonshine</span></div>
        <nav>
            <a class="nav-link" href="dashboard.html"><i class="fa-solid fa-house"></i> Inicio</a>
            <a class="nav-link" href="index.php?action=productos"><i class="fa-solid fa-boxes-stacked"></i> Inventario</a>
            <a class="nav-link active" href="#editar"><i class="fa-solid fa-pen"></i> Editar producto</a>
        </nav>
    </aside>
    <main class="content">
        <header class="content-header">
            <div><p class="eyebrow">Inventario / Edición</p><h1>Editar producto #<?= e($datos['id'] ?? '') ?></h1><p>Modifica los datos y guarda los cambios en la base de datos.</p></div>
        </header>

        <?php if (!empty($error)): ?><div class="mvc-error"><?= e($error) ?></div><?php endif; ?>

        <section class="panel">
            <h2><i class="fa-solid fa-pen-to-square"></i> Datos del producto</h2>
            <form class="mvc-form" method="POST" action="index.php?action=actualizar&amp;id=<?= e($datos['id'] ?? '') ?>">
                <div class="mvc-field">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" maxlength="150" value="<?= e($datos['nombre'] ?? '') ?>" required>
                </div>
                <div class="mvc-field">
                    <label for="categoria_id">ID de categoría</label>
                    <input id="categoria_id" name="categoria_id" type="number" min="1" step="1" value="<?= e($datos['categoria_id'] ?? '') ?>" required>
                </div>
                <div class="mvc-field full">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion"><?= e($datos['descripcion'] ?? '') ?></textarea>
                </div>
                <div class="mvc-field">
                    <label for="id_inventario">ID de inventario</label>
                    <input id="id_inventario" name="id_inventario" type="number" min="1" step="1" value="<?= e($datos['id_inventario'] ?? '') ?>" required>
                </div>
                <div class="mvc-field">
                    <label for="estado">Estado</label>
                    <input id="estado" name="estado" type="text" maxlength="50" value="<?= e($datos['estado'] ?? '') ?>" required>
                </div>
                <div class="mvc-field">
                    <label for="precio_compra">Precio de compra</label>
                    <input id="precio_compra" name="precio_compra" type="number" min="0" step="0.01" value="<?= e($datos['precio_compra'] ?? '') ?>" required>
                </div>
                <div class="mvc-field">
                    <label for="precio_venta">Precio de venta</label>
                    <input id="precio_venta" name="precio_venta" type="number" min="0" step="0.01" value="<?= e($datos['precio_venta'] ?? '') ?>" required>
                </div>
                <div class="mvc-field">
                    <label for="stock">Stock actual</label>
                    <input id="stock" name="stock" type="number" min="0" step="1" value="<?= e($datos['stock'] ?? '') ?>" required>
                </div>
                <div class="mvc-field">
                    <label for="stock_minimo">Stock mínimo</label>
                    <input id="stock_minimo" name="stock_minimo" type="number" min="0" step="1" value="<?= e($datos['stock_minimo'] ?? '') ?>" required>
                </div>
                <div class="mvc-field full">
                    <div class="mvc-actions">
                        <button class="mvc-button" type="submit"><i class="fa-solid fa-floppy-disk"></i> Actualizar producto</button>
                        <a class="mvc-button secondary" href="index.php?action=productos">Cancelar / Volver</a>
                    </div>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>
