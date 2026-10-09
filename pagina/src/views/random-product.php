<?php
require_once __DIR__ . '/../controllers/auth/productos_controller.php';

/** Muestreo uniforme del catálogo completo: cada producto con foto puede salir. */
function productosVisualesAleatorios(?array $catalogo = null): array
{
    $productos = array_values(array_filter($catalogo ?? obtenerTodosLosProductos(), fn($p) => !empty($p['imagenes'])));
    for ($i = count($productos) - 1; $i > 0; $i--) {
        $j = random_int(0, $i);
        [$productos[$i], $productos[$j]] = [$productos[$j], $productos[$i]];
    }
    foreach ($productos as &$producto) {
        $producto['imagen_destacada'] = $producto['imagenes'][random_int(0, count($producto['imagenes']) - 1)];
    }
    unset($producto);
    return $productos;
}
function escaparProducto(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
