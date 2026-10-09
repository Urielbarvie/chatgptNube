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
<p class="mvb-signup-overline"><span></span> TU ESPACIO EN MVB</p>
<h2 id="signup-side-title"><?php if (!empty($isLoginPage)): ?>Lo que te gusta.<br> A un paso.<br> <em>Volvé a MVB.</em><?php else: ?>Cada auto tiene<br> una historia.<br> <em>Sigamos la tuya.</em><?php endif; ?></h2>
<p class="mvb-signup-side-description"><?= !empty($isLoginPage) ? 'Tu perfil, tu auto y todo lo que viene. Entrá y seguí encontrando tu próximo detalle.' : 'Creá tu cuenta y tené a mano todo lo que necesitás para tu próxima compra.' ?></p>
<div class="mvb-signup-photo"><img src="<?= BASE_URL ?>/assets/img/limpieza-productos/arm17744/arm17744_01.png" alt="Cuidado para los detalles de tu auto" width="300" height="300"><div><span>EL DETALLE HACE LA DIFERENCIA</span><strong>Más cuidado.<br> Más tuyo.</strong></div><span aria-hidden="true" class="mvb-signup-photo-arrow">↗</span></div>
<ul class="mvb-signup-benefits"><li><span>01</span><div><strong>Tus datos, en un lugar</strong><p>Completá tu perfil cuando quieras.</p></div></li><li><span>02</span><div><strong>Tu auto también tiene su espacio</strong><p>Guardá su marca, modelo y año.</p></div></li><li><span>03</span><div><strong>Elegí cómo estar en contacto</strong><p>Vos decidís si querés recibir novedades.</p></div></li></ul>
</div></aside><div class="auth-form-panel" id="registro" role="main">
