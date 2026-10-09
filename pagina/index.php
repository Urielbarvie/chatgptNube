<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/src/config/rutas.php';
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/controllers/auth/productos_controller.php';
require_once __DIR__ . '/src/views/productos_card.php';
require_once __DIR__ . '/src/views/random-product.php';
$productosVisuales = productosVisualesAleatorios();
$seleccion = array_slice($productosVisuales, 0, 12);
$heroProduct = $productosVisuales[0] ?? null;
$isHomePage = true;
require_once __DIR__ . '/src/views/_layouts/header.php';
$homeCategoryUrls = [
    'limpieza' => BASE_URL . '/src/views/catalogo.php?buscar=SHAMPOO',
    'herramientas' => BASE_URL . '/src/views/catalogo.php?buscar=DESTORNILLADOR',
];
foreach ($categoriasNavbar as $homeCategory) {
    foreach (array_keys($homeCategoryUrls) as $homeGroup) {
        if (stripos($homeCategory['nombre'], $homeGroup) !== false) {
            $homeCategoryUrls[$homeGroup] = BASE_URL . '/src/views/catalogo.php?categoria=' . (int) $homeCategory['id_categoria'];
        }
    }
}

?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/home-refresh.css?v=<?= filemtime(__DIR__ . '/assets/css/home-refresh.css') ?>">
<main class="mvb-storefront mvb-refresh">
<section class="mvb-new-hero" aria-labelledby="home-title"><div class="mvb-wide mvb-new-hero-grid">
<div class="mvb-new-hero-copy">
<p class="mvb-overline"><span></span> MULTIVENTAS BARVIE</p>
<h1 id="home-title">Equipá tu auto.<br> <em>A tu manera.</em></h1>
<p class="mvb-hero-description">Accesorios, limpieza y herramientas.</p>
<div class="mvb-hero-actions"><a class="mvb-main-action" href="<?= BASE_URL ?>/src/views/catalogo.php">Explorar el catálogo <span aria-hidden="true">↗</span></a><a class="mvb-text-action" href="#productos">Ver productos ↓</a></div>

</div>
<div class="mvb-new-hero-art">
<?php if ($heroProduct): ?><a class="mvb-featured-product" href="<?= BASE_URL ?>/src/views/detalles_producto.php?id=<?= (int)$heroProduct['id_producto'] ?>"><div class="mvb-featured-top"><span>DESCUBRÍ MVB</span><span class="mvb-featured-tag"><?= escaparProducto($heroProduct['categoria_nombre'] ?? '') ?></span></div><img src="<?= escaparProducto($heroProduct['imagen_destacada']) ?>" alt="<?= escaparProducto($heroProduct['nombre']) ?>" fetchpriority="high" width="400" height="400"><div class="mvb-featured-bottom"><div><span><?= escaparProducto($heroProduct['marca'] ?? '') ?></span><h2><?= escaparProducto($heroProduct['nombre']) ?></h2></div><span class="mvb-round-arrow" aria-hidden="true">↗</span></div></a><?php endif; ?>
<div class="mvb-hero-mini-grid"><?php foreach (array_slice($productosVisuales, 1, 2) as $mini): ?><a class="mvb-hero-mini" href="<?= BASE_URL ?>/src/views/detalles_producto.php?id=<?= (int)$mini['id_producto'] ?>"><img src="<?= escaparProducto($mini['imagen_destacada']) ?>" alt="<?= escaparProducto($mini['nombre']) ?>" width="96" height="96"><div><span><?= escaparProducto($mini['categoria_nombre'] ?? '') ?></span><strong><?= escaparProducto($mini['nombre']) ?></strong></div><span aria-hidden="true">↗</span></a><?php endforeach; ?></div></div></div></section>

<section class="mvb-wide mvb-new-section" id="productos" aria-labelledby="selection-title">
<div class="mvb-new-section-heading"><div><p class="mvb-overline">EXPLORÁ MVB</p><h2 id="selection-title">Encontrá tu próximo producto.</h2></div><div class="mvb-rail-controls"><a href="<?= BASE_URL ?>/src/views/catalogo.php">Ver todo el catálogo ↗</a><button type="button" data-product-scroll="-1" aria-label="Ver productos anteriores">←</button><button type="button" data-product-scroll="1" aria-label="Ver más productos">→</button></div></div>
<?php if ($seleccion): ?><div class="mvb-product-rail" id="homeProductRail" tabindex="0" role="region" aria-label="Selección de productos, desplazable horizontalmente"><?php foreach ($seleccion as $p): ?><?= renderProductCard($p) ?><?php endforeach; ?></div>
<?php else: ?><div class="mvb-empty-selection"><p>Tu próximo detalle te espera en el catálogo.</p><a href="<?= BASE_URL ?>/src/views/catalogo.php">Explorar productos ↗</a></div><?php endif; ?>
</section>
<section class="mvb-wide mvb-new-section" aria-labelledby="category-title"><div class="mvb-new-section-heading"><div><p class="mvb-overline">¿POR DÓNDE EMPEZAMOS?</p><h2 id="category-title">Algo para cada plan.</h2></div><p class="mvb-section-aside">Para cuidarlo, equiparlo<br> o poner manos a la obra.</p></div>
<div class="mvb-discover-grid">
<a class="mvb-discover-card" href="<?= htmlspecialchars($homeCategoryUrls['limpieza'], ENT_QUOTES, 'UTF-8') ?>"><div><span>01 / LIMPIEZA</span><h3>Que brille<br> como te gusta.</h3><span class="mvb-discover-link">Cuidado &amp; detailing ↗</span></div><img src="<?= BASE_URL ?>/assets/img/limpieza-productos/arm17744/arm17744_01.png" alt="Producto para el cuidado del auto" loading="lazy" width="240" height="260"></a>
<a class="mvb-discover-card" href="<?= htmlspecialchars($homeCategoryUrls['herramientas'], ENT_QUOTES, 'UTF-8') ?>"><div><span>02 / HERRAMIENTAS</span><h3>Listo para<br> lo que venga.</h3><span class="mvb-discover-link">Manos a la obra ↗</span></div><img src="<?= BASE_URL ?>/assets/img/herramientas-y-elevacion/ll-013/ll-013_01.png" alt="Herramientas para equiparte" loading="lazy" width="240" height="260"></a>
<a class="mvb-discover-card" href="<?= BASE_URL ?>/src/views/catalogo.php?buscar=REVIGAL"><div><span>03 / MANTENIMIENTO</span><h3>Cuidalo<br> por dentro.</h3><span class="mvb-discover-link">Motor a punto ↗</span></div><img src="<?= BASE_URL ?>/assets/img/limpieza-productos/re551/re551_01.png" alt="Limpiador de motor Revigal" loading="lazy" width="240" height="260"></a>
</div></section>
<section class="mvb-wide mvb-brand-section" aria-labelledby="brands-title"><h2 id="brands-title">Marcas que acompañan tu camino.</h2><div class="mvb-brands-strip">
<?php foreach (['revigal'=>'Revigal','iael'=>'IAEL','goodyear'=>'Goodyear','barbie'=>'Barbie','armorall'=>'Armor All','california-scents'=>'California Scents'] as $archivo=>$marca): ?>
<a href="<?= BASE_URL ?>/src/views/catalogo.php?buscar=<?= rawurlencode($marca) ?>" aria-label="Ver productos <?= htmlspecialchars($marca) ?>"><img src="<?= BASE_URL ?>/assets/img/marca-<?= $archivo ?>.png" alt="<?= htmlspecialchars($marca) ?>" loading="lazy" width="180" height="75"></a>
<?php endforeach; ?></div></section>
<section id="como-empezar" class="mvb-wide mvb-account-invite" aria-labelledby="account-title"><div><p class="mvb-overline">TU ESPACIO EN MVB</p><h2 id="account-title">Un auto único.<br> Una cuenta <em>bien tuya.</em></h2><p>Guardá tus datos, tus direcciones y la información de tu vehículo en un solo lugar.</p></div><div><a class="mvb-main-action" href="<?= BASE_URL ?>/src/views/<?= !empty($_SESSION['usuario']) ? 'user/profile.php' : 'auth/register.php' ?>"><?= !empty($_SESSION['usuario']) ? 'Ir a mi cuenta' : 'Crear mi cuenta' ?> <span aria-hidden="true">↗</span></a><a class="mvb-text-action" href="https://wa.me/5491162982496" target="_blank" rel="noopener noreferrer">¿Necesitás ayuda para elegir? Hablemos ↗</a></div></section>
</main>
<script>
document.addEventListener('DOMContentLoaded',()=>{
const rail=document.getElementById('homeProductRail');
document.querySelectorAll('[data-product-scroll]').forEach(button=>button.addEventListener('click',()=>{
if(rail) rail.scrollBy({left:Number(button.dataset.productScroll)*rail.clientWidth*.85,behavior:window.matchMedia('(prefers-reduced-motion: reduce)').matches?'auto':'smooth'});
}));
});
</script>
<?php require_once __DIR__ . '/src/views/_layouts/footer.php'; ?>
