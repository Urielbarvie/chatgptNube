<?php
require_once __DIR__ . '/../config/rutas.php';                     // define BASE_URL
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/auth/productos_controller.php'; // consultas a Strapi
require_once __DIR__ . '/productos_card.php';                       // renderProductCard()

// ---------------------------------------------------------------------------
// Parámetros de la URL (todo por GET: se puede compartir el link, usar
// "atrás/adelante" del navegador, y recargar sin perder el filtro)
// ---------------------------------------------------------------------------
$categoriaId = isset($_GET['categoria']) ? (int) $_GET['categoria'] : null;
// Compatibilidad con enlaces de categorías unificadas.
$categoriaId = [83 => 142, 128 => 104, 130 => 100, 138 => 103][$categoriaId] ?? $categoriaId;
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

$catalogoCompleto = obtenerTodosLosProductos();
$todasCategorias = obtenerArbolCategorias();
// No mostrar ramas vacías; reaparecen al cargar productos desde Strapi.
$categoriasConProductos = [];
foreach ($catalogoCompleto as $producto) $categoriasConProductos[(int)$producto['id_categoria']] = true;
do {
    $cantidadAnterior = count($categoriasConProductos);
    foreach ($todasCategorias as $cat) {
        if (isset($categoriasConProductos[(int)$cat['id_categoria']]) && $cat['id_categoria_padre']) {
            $categoriasConProductos[(int)$cat['id_categoria_padre']] = true;
        }
    }
} while (count($categoriasConProductos) !== $cantidadAnterior);
$todasCategorias = array_values(array_filter($todasCategorias, fn($cat) => isset($categoriasConProductos[(int)$cat['id_categoria']])));
$porPadre = [];
foreach ($todasCategorias as $cat) $porPadre[(int)($cat['id_categoria_padre'] ?? 0)][] = $cat;
$idsCategoria = [];
$categoriaActual = null;
foreach ($todasCategorias as $cat) if ((int)$cat['id_categoria'] === $categoriaId) $categoriaActual = $cat;
$pendientes = $categoriaId === null ? [] : [$categoriaId];
while ($pendientes) {
    $id = array_pop($pendientes);
    if (in_array($id, $idsCategoria, true)) continue;
    $idsCategoria[] = $id;
    foreach ($porPadre[$id] ?? [] as $hija) $pendientes[] = (int)$hija['id_categoria'];
}
$productos = array_values(array_filter($catalogoCompleto, function($p) use ($categoriaId, $idsCategoria, $busqueda) {
    if ($categoriaId !== null && !in_array((int)$p['id_categoria'], $idsCategoria, true)) return false;
    $texto = ($p['nombre'] ?? '') . ' ' . ($p['descripcion'] ?? '') . ' ' . ($p['marca'] ?? '');
    $termino = strtolower(trim($busqueda)) === 'revigal' ? 'revi' : $busqueda;
    return $termino === '' || mb_stripos($texto, $termino) !== false;
}));
$enlaceCategoria = function($id) use ($busqueda) {
    $params = [];
    if ($id !== null) $params['categoria'] = $id;
    if ($busqueda !== '') $params['buscar'] = $busqueda;
    return BASE_URL . '/src/views/catalogo.php' . ($params ? '?' . http_build_query($params) : '');
};
// Solo se abre el camino de la categoría elegida.
$caminoActivo = [];
$actual = $categoriaActual;
while ($actual && !in_array((int)$actual['id_categoria'], $caminoActivo, true)) {
    $caminoActivo[] = (int)$actual['id_categoria'];
    $padreId = (int)($actual['id_categoria_padre'] ?? 0);
    $actual = null;
    foreach ($todasCategorias as $cat) {
        if ((int)$cat['id_categoria'] === $padreId) $actual = $cat;
    }
}
$renderArbol = function($padre, $visitados = []) use (&$renderArbol, $porPadre, $categoriaId, $enlaceCategoria, $caminoActivo) {
    echo '<ul class="catalog-category-tree">';
    foreach ($porPadre[$padre] ?? [] as $cat) {
        $id = (int)$cat['id_categoria'];
        if (in_array($id, $visitados, true)) continue;
        $nombre = htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8');
        $enlace = htmlspecialchars($enlaceCategoria($id), ENT_QUOTES, 'UTF-8');
        $seleccionada = $categoriaId === $id ? ' aria-current="page"' : '';
        echo '<li>';
        if (!empty($porPadre[$id])) {
            $abierta = in_array($id, $caminoActivo, true) ? ' open' : '';
            echo '<details class="catalog-category-branch"' . $abierta . '>';
            echo '<summary>' . $nombre . '</summary>';
            echo '<a class="catalog-category-all" href="' . $enlace . '"' . $seleccionada . '>Ver todo</a>';
            $renderArbol($id, [...$visitados, $id]);
            echo '</details>';
        } else {
            echo '<a href="' . $enlace . '"' . $seleccionada . '>' . $nombre . '</a>';
        }
        echo '</li>';
    }
    echo '</ul>';
};

$isCatalogPage = true;
require_once __DIR__ . '/_layouts/header.php';
?>
    <!-- Catálogo -->
    <main class="container my-5 mvb-catalog">

        <nav class="catalog-breadcrumb" aria-label="Ubicación"><a href="<?= BASE_URL ?>/index.php">Inicio</a> / <a href="<?= BASE_URL ?>/src/views/catalogo.php">Productos</a><?php if ($categoriaActual): ?> / <?= htmlspecialchars($categoriaActual['nombre']) ?><?php endif; ?></nav>
        <div class="catalog-layout">
        <aside class="catalog-sidebar" aria-label="Categorías">
            <h2>Categorías</h2>
            <a href="<?= htmlspecialchars($enlaceCategoria(null)) ?>" <?= $categoriaId === null ? 'aria-current="page"' : '' ?>>Todo el catálogo</a>
            <?php $renderArbol(0); ?>
            <div class="catalog-help"><strong>¿Necesitás una mano?</strong><p>Consultanos sobre el producto que buscás.</p><a href="https://wa.me/5491162982496" target="_blank" rel="noopener noreferrer">Escribinos por WhatsApp ↗</a></div>
        </aside>
        <section class="catalog-results" aria-label="Resultados del catálogo">

        <!-- Encabezado + buscador propio del catálogo (GET) -->
        <div class="catalog-results-heading">
            <div>
                <?php if ($busqueda !== ''): ?>
                    <a href="<?= BASE_URL ?>/src/views/catalogo.php" class="text-secondary text-decoration-none small d-inline-block mb-2">← Volver al catálogo</a>
                    <h1 class="fw-bold text-white text-uppercase tracking-wide m-0">Resultados para "<?= htmlspecialchars($busqueda) ?>"</h1>
                <?php elseif ($categoriaActual): ?>
                    <a href="<?= BASE_URL ?>/src/views/catalogo.php<?= $categoriaActual['id_categoria_padre'] ? '?categoria=' . (int) $categoriaActual['id_categoria_padre'] : '' ?>" class="text-secondary text-decoration-none small d-inline-block mb-2">← Volver</a>
                    <h1 class="fw-bold text-white text-uppercase tracking-wide m-0"><?= htmlspecialchars($categoriaActual['nombre']) ?></h1>
                <?php else: ?>
                    <h1 class="fw-bold text-white text-uppercase tracking-wide m-0">Catálogo</h1>
                    
                <?php endif; ?>
            </div>

            <form method="GET" action="<?= BASE_URL ?>/src/views/catalogo.php" class="catalog-search" role="search" aria-label="Filtrar productos del catálogo">
                <?php if ($categoriaId !== null): ?><input type="hidden" name="categoria" value="<?= $categoriaId ?>"><?php endif; ?>
                <label for="catalog-search">Filtrar en <strong><?= htmlspecialchars($categoriaActual['nombre'] ?? 'este catálogo') ?></strong></label><div class="catalog-search-controls">
                <input id="catalog-search" type="search" name="buscar" value="<?= htmlspecialchars($busqueda) ?>" class="form-control form-control-premium" placeholder="Nombre o marca del producto">
                <button type="submit" class="btn catalog-filter-button">Filtrar</button></div><?php if ($busqueda !== ''): ?><a class="catalog-clear-search" href="<?= htmlspecialchars(BASE_URL . '/src/views/catalogo.php' . ($categoriaId !== null ? '?categoria=' . $categoriaId : ''), ENT_QUOTES) ?>">Quitar búsqueda</a><?php endif; ?>
            </form>
        </div>

        <?php if (!empty($productos)): ?>

            <!-- ============================================================
                 VISTA: PRODUCTOS (de una categoría hoja, o de una búsqueda)
                 ============================================================ -->
            <p class="catalog-result-count"><?= count($productos) ?> producto<?= count($productos) === 1 ? '' : 's' ?></p>
            <div class="row g-4 catalog-product-grid">
                <?php foreach ($productos as $p): ?>
                    <?= renderProductCard($p) ?>
                <?php endforeach; ?>
            </div>

        <?php else: ?>

            <div class="p-5 text-center bg-dark border border-secondary border-opacity-25 rounded-3">
                <p class="text-secondary m-0">
                    <?= $busqueda !== '' ? 'No encontramos productos que coincidan con tu búsqueda.' : 'Todavía no hay productos cargados en esta categoría.' ?>
                </p>
            </div>

        <?php endif; ?>

        </section>
        </div>
    </main>

<?php
require_once __DIR__ . '/_layouts/footer.php';
?>
