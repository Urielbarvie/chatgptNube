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
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

$categoriaActual = null;
$subcategorias = [];
$productos = [];

if ($busqueda !== '') {
    // Si hay búsqueda activa, ignoramos la navegación por categorías
    // y mostramos directamente los resultados que matchean el texto.
    $productos = buscarProductos($busqueda);
} elseif ($categoriaId !== null) {
    $categoriaActual = obtenerCategoriaPorId($categoriaId);

    if ($categoriaActual) {
        $subcategorias = obtenerCategorias($categoriaId);
        // Si la categoría no tiene subcategorías, es una categoría "hoja" -> mostramos sus productos
        if (empty($subcategorias)) {
            $productos = obtenerProductosPorCategoria($categoriaId);
        }
    }
}

if ($busqueda === '' && $categoriaId === null) {
    $productos = obtenerTodosLosProductos();
}

$isCatalogPage = true;
require_once __DIR__ . '/_layouts/header.php';
?>
    <!-- Catálogo -->
    <main class="container my-5 mvb-catalog">

        <div class="catalog-banner" aria-label="Accesorios, limpieza y herramientas">
            <div><span>MULTIVENTAS BARVIE</span><h2>Cuidá cada detalle.</h2><p>Accesorios, limpieza y herramientas para tu vehículo.</p></div>
            <img src="<?= BASE_URL ?>/assets/img/limpieza-productos/re551/re551_01.png" alt="Producto para limpieza vehicular" width="160" height="180">
            <img src="<?= BASE_URL ?>/assets/img/herramientas-y-elevacion/ll-013/ll-013_01.png" alt="Juego de herramientas" width="200" height="180">
        </div>
        <nav class="catalog-breadcrumb" aria-label="Ubicación"><a href="<?= BASE_URL ?>/index.php">Inicio</a> / <a href="<?= BASE_URL ?>/src/views/catalogo.php">Productos</a><?php if ($categoriaActual): ?> / <?= htmlspecialchars($categoriaActual['nombre']) ?><?php endif; ?></nav>
        <div class="catalog-layout">
        <aside class="catalog-sidebar" aria-label="Categorías">
            <h2>Categorías</h2>
            <a href="<?= BASE_URL ?>/src/views/catalogo.php" <?= $categoriaId === null && $busqueda === '' ? 'aria-current="page"' : '' ?>>Todo el catálogo</a>
            <?php foreach ($categoriasNavbar as $cat): ?>
                <a href="<?= BASE_URL ?>/src/views/catalogo.php?categoria=<?= (int)$cat['id_categoria'] ?>" <?= $categoriaId === (int)$cat['id_categoria'] ? 'aria-current="page"' : '' ?>><?= htmlspecialchars($cat['nombre']) ?></a>
            <?php endforeach; ?>
            <div class="catalog-help"><strong>¿Necesitás una mano?</strong><p>Consultanos sobre el producto que buscás.</p><a href="https://wa.me/5491162982496" target="_blank" rel="noopener noreferrer">Escribinos por WhatsApp ↗</a></div>
        </aside>
        <section class="catalog-results" aria-label="Resultados del catálogo">

        <!-- Encabezado + buscador propio del catálogo (GET) -->
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <?php if ($busqueda !== ''): ?>
                    <a href="<?= BASE_URL ?>/src/views/catalogo.php" class="text-secondary text-decoration-none small d-inline-block mb-2">← Volver al catálogo</a>
                    <h1 class="fw-bold text-white text-uppercase tracking-wide m-0">Resultados para "<?= htmlspecialchars($busqueda) ?>"</h1>
                <?php elseif ($categoriaActual): ?>
                    <a href="<?= BASE_URL ?>/src/views/catalogo.php<?= $categoriaActual['id_categoria_padre'] ? '?categoria=' . (int) $categoriaActual['id_categoria_padre'] : '' ?>" class="text-secondary text-decoration-none small d-inline-block mb-2">← Volver</a>
                    <h1 class="fw-bold text-white text-uppercase tracking-wide m-0"><?= htmlspecialchars($categoriaActual['nombre']) ?></h1>
                <?php else: ?>
                    <h1 class="fw-bold text-white text-uppercase tracking-wide m-0">Catálogo</h1>
                    <p class="text-secondary mb-0">Explorá todos los productos o filtrá por categoría y búsqueda.</p>
                <?php endif; ?>
            </div>

            <form method="GET" action="<?= BASE_URL ?>/src/views/catalogo.php" class="d-flex gap-2 catalog-search">
                <label for="catalog-search" class="visually-hidden">Buscar en el catálogo</label>
                <input id="catalog-search" type="search" name="buscar" value="<?= htmlspecialchars($busqueda) ?>" class="form-control form-control-premium" placeholder="Buscar en el catálogo...">
                <button type="submit" class="btn btn-premium-red px-4">Buscar</button>
            </form>
        </div>

        <?php if (!empty($subcategorias)): ?>

            <!-- ============================================================
                 VISTA: GRILLA DE (SUB)CATEGORÍAS
                 ============================================================ -->
            <div class="row g-4 catalog-categories">
                <?php foreach ($subcategorias as $cat): ?>
                    <?php $cantidad = contarProductosEnCategoria($cat['id_categoria']); ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <a href="<?= BASE_URL ?>/src/views/catalogo.php?categoria=<?= (int) $cat['id_categoria'] ?>" class="text-decoration-none">
                            <div class="card card-premium h-100 p-4 catalog-category">
                                <?php
                                $fotoCategoria = null;
                                $nombreCategoria = strtolower($cat['nombre']);
                                foreach (['detailing' => 'limpieza-productos/re551/re551_01.png', 'herramientas' => 'herramientas-y-elevacion/ll-013/ll-013_01.png', 'accesorios' => 'celulares-soportes-y-carga/va-119/va-119_01.png', 'seguridad' => 'sujecion-y-seguridad/te-001/te-001_01.png'] as $clave => $foto) {
                                    if (str_contains($nombreCategoria, $clave)) { $fotoCategoria = $foto; break; }
                                }
                                ?>
                                <?php if ($fotoCategoria): ?><img class="catalog-category-photo" src="<?= BASE_URL ?>/assets/img/<?= $fotoCategoria ?>" alt="" loading="lazy" width="220" height="180"><?php endif; ?>
                                <h4 class="h5 fw-bold text-white mb-2"><?= htmlspecialchars($cat['nombre']) ?></h4>
                                <?php if (!empty($cat['descripcion'])): ?>
                                    <p class="text-secondary small mb-3"><?= htmlspecialchars($cat['descripcion']) ?></p>
                                <?php endif; ?>
                                <?php if ($cantidad > 0): ?>
                                    <span class="badge badge-premium-red align-self-center">
                                        <?= $cantidad ?> producto<?= $cantidad === 1 ? '' : 's' ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif (!empty($productos)): ?>

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
