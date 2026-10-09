<?php
require_once __DIR__ . '/../random-product.php';
$productoVisual = productosVisualesAleatorios()[0] ?? null;
?>
<!DOCTYPE html>
<html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= !empty($isLoginPage) ? 'Ingresá a tu cuenta' : 'Creá tu cuenta' ?> | Multiventas Barvie</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/register-refresh.css?v=<?= filemtime(__DIR__ . '/../../../assets/css/register-refresh.css') ?>">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/account-enhancements.css?v=<?= filemtime(__DIR__ . '/../../../assets/css/account-enhancements.css') ?>">
</head><body class="mvb-register <?= !empty($isLoginPage) ? 'mvb-login' : '' ?>"><div class="auth-page">
<nav class="auth-topbar" aria-label="Navegación de registro"><a class="mvb-signup-brand" href="<?= BASE_URL ?>/index.php"><img src="<?= BASE_URL ?>/assets/img/logo.jpg" alt="Multiventas Barvie" width="42" height="42"><span>MVB<small>MULTIVENTAS BARVIE</small></span></a><a href="<?= BASE_URL ?>/index.php" class="auth-back-link">← Volver a la tienda</a></nav>
<div class="auth-center"><div class="auth-shell">
<aside class="auth-side" aria-labelledby="signup-side-title"><div class="auth-side-content">

<h2 id="signup-side-title"><?php if (!empty($isLoginPage)): ?>Todo para<br> <em>tu auto.</em><?php else: ?>Registrate.<br> <em>Seguí comprando.</em><?php endif; ?></h2>

<?php if ($productoVisual): ?><a class="mvb-signup-photo mvb-random-product" href="<?= BASE_URL ?>/src/views/detalles_producto.php?id=<?= (int)$productoVisual['id_producto'] ?>"><img src="<?= escaparProducto($productoVisual['imagen_destacada']) ?>" alt="<?= escaparProducto($productoVisual['nombre']) ?>" width="300" height="300"><div><span>DESCUBRÍ EL CATÁLOGO</span><strong><?= escaparProducto($productoVisual['nombre']) ?></strong></div><span aria-hidden="true" class="mvb-signup-photo-arrow">↗</span></a><?php endif; ?>

</div></aside><div class="auth-form-panel" id="registro" role="main">
