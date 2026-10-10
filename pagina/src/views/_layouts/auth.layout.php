<?php
require_once __DIR__ . '/../../config/rutas.php';
require_once __DIR__ . '/../../config/bootstrap.php';
if (isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
$isAuthPage = true;
require_once __DIR__ . '/header.php';
$esRegistro = basename($_SERVER['SCRIPT_NAME']) === 'register.php';
// Una selección pequeña de marcas reales, con fotos del catálogo.
$productosMarcas = obtenerTodosLosProductos();
shuffle($productosMarcas);
$seleccionMarcas = [];
foreach ($productosMarcas as $productoMarca) {
    $logo = obtenerLogoMarca($productoMarca);
    if (!$logo || empty($productoMarca['imagenes']) || isset($seleccionMarcas[$logo])) continue;
    $seleccionMarcas[$logo] = ['producto' => $productoMarca, 'logo' => $logo];
    if (count($seleccionMarcas) === 6) break;
}
$seleccionMarcas = array_values($seleccionMarcas);
function renderMarcasCuenta(array $marcas): void {
    foreach ($marcas as $item) {
        $p = $item['producto'];
        $nombre = htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8');
        $marca = htmlspecialchars($p['marca'] ?: $p['nombre'], ENT_QUOTES, 'UTF-8');
        echo '<a class="auth-brand-tile" href="' . BASE_URL . '/src/views/detalles_producto.php?id=' . (int)$p['id_producto'] . '">';
        echo '<img class="auth-brand-logo" src="' . htmlspecialchars($item['logo'], ENT_QUOTES) . '" alt="Marca: ' . $marca . '">';
        echo '<img class="auth-brand-item" src="' . htmlspecialchars($p['imagenes'][0], ENT_QUOTES) . '" alt="' . $nombre . '" loading="lazy">';
        echo '<span>' . $nombre . '</span></a>';
    }
}
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/account.css?v=<?= filemtime(__DIR__ . '/../../../assets/css/account.css') ?>">
<div class="auth-page mvb-auth <?= $esRegistro ? 'mvb-auth-register' : 'mvb-auth-login' ?>">
<div class="auth-center"><a class="auth-back-link auth-gallery-back" href="<?= BASE_URL ?>/index.php">← Volver a la tienda</a><div class="auth-shell">
<aside class="auth-side auth-brand-side" aria-label="Marcas y productos de MVB"><?php renderMarcasCuenta(array_slice($seleccionMarcas,0,3)); ?></aside><div class="auth-form-panel">
