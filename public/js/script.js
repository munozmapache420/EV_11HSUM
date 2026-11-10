
// =====================================
// MOONSHINE - SISTEMA DE INVENTARIO
// =====================================

let graficaStock = null;
let graficaValor = null;

// =====================================
// INICIAR SISTEMA
// =====================================

document.addEventListener("DOMContentLoaded", function () {

    mostrarSeccion("inicio");

    actualizarEstadisticas();
    actualizarGraficas();

});

// =====================================
// CAMBIAR SECCIONES
// =====================================

function mostrarSeccion(nombre) {

    let secciones = document.querySelectorAll(".section");
    let enlaces = document.querySelectorAll(".nav-link");

    secciones.forEach(function (seccion) {
        seccion.classList.remove("active");
    });

    enlaces.forEach(function (enlace) {
        enlace.classList.remove("active");
    });

    let seleccionada = document.getElementById(nombre);

    if (seleccionada) {
        seleccionada.classList.add("active");
    }

    let enlace = document.querySelector(
        '.nav-link[href="#' + nombre + '"]'
    );

    if (enlace) {
        enlace.classList.add("active");
    }

    if (nombre === "estadisticas") {
        actualizarEstadisticas();
        actualizarGraficas();
    }
}

// =====================================
// OBTENER PRODUCTOS
// =====================================

function obtenerProductos() {

    let datos = localStorage.getItem("productosMoonshine");

    if (datos) {
        return JSON.parse(datos);
    }

    return [];
}

// =====================================
// ACTUALIZAR ESTADISTICAS
// =====================================

function actualizarEstadisticas() {

    let productos = obtenerProductos();

    let totalProductos = productos.length;
    let stockTotal = 0;
    let valorTotal = 0;

    productos.forEach(function (producto) {

        let cantidad = Number(
            producto.cantidad ?? producto.stock ?? 0
        );

        let precio = Number(
            producto.precio ?? producto.precio_base ?? 0
        );

        stockTotal += cantidad;
        valorTotal += cantidad * precio;

    });

    let total = document.getElementById("totalProductos");
    let stock = document.getElementById("stockTotal");
    let valor = document.getElementById("valorInventario");

    if (total) {
        total.textContent = totalProductos;
    }

    if (stock) {
        stock.textContent = stockTotal;
    }

    if (valor) {
        valor.textContent = formatearPrecio(valorTotal);
    }

}

// =====================================
// GRAFICAS
// =====================================

function actualizarGraficas() {

    if (typeof Chart === "undefined") {
        return;
    }

    let productos = obtenerProductos();

    let nombres = productos.map(function (producto) {
        return producto.nombre || producto.nombre_producto || "Producto";
    });

    let cantidades = productos.map(function (producto) {
        return Number(producto.cantidad ?? producto.stock ?? 0);
    });

    let valores = productos.map(function (producto) {

        let cantidad = Number(
            producto.cantidad ?? producto.stock ?? 0
        );

        let precio = Number(
            producto.precio ?? producto.precio_base ?? 0
        );

        return cantidad * precio;

    });

    // GRAFICA DE STOCK

    let canvasStock = document.getElementById("graficaStock");

    if (canvasStock) {

        if (graficaStock) {
            graficaStock.destroy();
        }

        graficaStock = new Chart(canvasStock, {

            type: "bar",

            data: {
                labels: nombres,

                datasets: [{
                    label: "Cantidad disponible",
                    data: cantidades
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: true
            }

        });

    }

    // GRAFICA DE VALOR

    let canvasValor = document.getElementById("graficaValor");

    if (canvasValor) {

        if (graficaValor) {
            graficaValor.destroy();
        }

        graficaValor = new Chart(canvasValor, {

            type: "bar",

            data: {
                labels: nombres,

                datasets: [{
                    label: "Valor de existencias",
                    data: valores
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: true
            }

        });

    }

}

// =====================================
// FORMATO DE PRECIO
// =====================================

function formatearPrecio(valor) {

    return new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP",
        maximumFractionDigits: 0
    }).format(valor);

}
/////////////// barrrrrrr /////////

// =====================================
// MOONSHINE - INVENTARIO
// =====================================

let productos = [];

// =====================================
// CONSULTAR PRODUCTOS
// =====================================

function cargarProductos() {

    fetch("productos.php")
        .then(respuesta => respuesta.json())
        .then(datos => {

            productos = datos;

            mostrarProductos();
            calcularEstadisticas();

        })
        .catch(error => {

            console.error("Error al cargar productos:", error);

            document.getElementById("tablaProductos").innerHTML =
                '<tr><td colspan="10">Error al consultar los productos.</td></tr>';

        });

}

// =====================================
// MOSTRAR PRODUCTOS
// =====================================

function mostrarProductos() {

    const tabla = document.getElementById("tablaProductos");

    tabla.innerHTML = "";

    if (productos.length === 0) {

        tabla.innerHTML =
            '<tr><td colspan="10">No hay productos registrados.</td></tr>';

        return;
    }

    productos.forEach(producto => {

        const bajo = Number(producto.stock) <= Number(producto.stock_minimo);

        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${producto.id}</td>
            <td>${producto.nombre}</td>
            <td>${producto.descripcion ?? ""}</td>
            <td>${producto.categoria_id}</td>
            <td>${producto.id_inventario}</td>
            <td>${formatearDinero(producto.precio_compra)}</td>
            <td>${formatearDinero(producto.precio_venta)}</td>
            <td>${producto.stock}</td>
            <td>${producto.stock_minimo}</td>
            <td>
                <span class="estado ${bajo ? 'salida' : 'entrada'}">
                    ${bajo ? 'Stock bajo' : producto.estado}
                </span>
            </td>
        `;

        tabla.appendChild(fila);

    });

}

// =====================================
// CALCULAR ESTADÍSTICAS
// =====================================

function calcularEstadisticas() {

    let unidades = 0;
    let valor = 0;
    let bajo = 0;

    productos.forEach(producto => {

        const stock = Number(producto.stock);
        const minimo = Number(producto.stock_minimo);
        const compra = Number(producto.precio_compra);

        unidades += stock;

        valor += compra * stock;

        if (stock <= minimo) {
            bajo++;
        }

    });

    document.getElementById("totalProductos").textContent =
        productos.length;

    document.getElementById("stockTotal").textContent =
        unidades;

    document.getElementById("valorInventario").textContent =
        formatearDinero(valor);

    document.getElementById("stockBajo").textContent =
        bajo;

}

// =====================================
// FORMATO DE DINERO
// =====================================

function formatearDinero(valor) {

    return "$" + Number(valor).toLocaleString("es-CO");

}

// =====================================
// INICIAR
// =====================================

document.addEventListener("DOMContentLoaded", function () {

    cargarProductos();

});
