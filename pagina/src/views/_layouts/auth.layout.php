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
$fotos = array_values(array_filter(obtenerTodosLosProductos(), fn($p) => !empty($p['imagenes'])));
$destacado = $fotos ? $fotos[random_int(0, count($fotos) - 1)] : null;
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/account.css?v=<?= filemtime(__DIR__ . '/../../../assets/css/account.css') ?>">
<div class="auth-page mvb-auth <?= $esRegistro ? 'mvb-auth-register' : 'mvb-auth-login' ?>">
<div class="auth-center"><div class="auth-shell">
<aside class="auth-side"><div class="auth-side-content">
<a class="auth-back-link" href="<?= BASE_URL ?>/index.php">← Volver a la tienda</a>
<p class="auth-kicker">TU CUENTA MVB</p>
<h2><?= $esRegistro ? 'Tu próxima compra<br> empieza <em>acá.</em>' : 'Volvé a lo<br> que te <em>gusta.</em>' ?></h2>
<?php if ($destacado): ?>
<a class="auth-product" href="<?= BASE_URL ?>/src/views/detalles_producto.php?id=<?= (int)$destacado['id_producto'] ?>">
<img src="<?= htmlspecialchars($destacado['imagenes'][random_int(0,count($destacado['imagenes'])-1)], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($destacado['nombre'], ENT_QUOTES) ?>" width="360" height="300">
<div><span>DESCUBRÍ MVB</span><strong><?= htmlspecialchars($destacado['nombre']) ?></strong><span aria-hidden="true">↗</span></div>
</a>
<?php endif; ?>
</div></aside><div class="auth-form-panel">
