<?php
$isLoginPage = true;
require_once __DIR__ . '/../_layouts/auth.layout.php';
$error = $_SESSION['error_login'] ?? ($_GET['error'] ?? null);
unset($_SESSION['error_login']);
?>
<div class="mvb-login-symbol" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg></div>
<div class="mvb-signup-heading"><p class="mvb-signup-overline">BIENVENIDO A MVB</p><h1>Iniciá sesión.</h1><p>Entrá con tu correo y contraseña.</p></div>
<?php if ($error): ?><div class="mvb-signup-error" role="alert"><strong>No pudimos iniciar sesión.</strong><span>Correo o contraseña incorrectos. Revisá tus datos y probá de nuevo.</span></div><?php endif; ?>
<form action="<?= BASE_URL ?>/src/controllers/auth/login.php" method="POST" class="mvb-signup-form mvb-login-form" id="loginForm">
<div class="mvb-signup-field"><label for="email">Correo electrónico</label><input type="email" id="email" name="email" placeholder="vos@ejemplo.com" required autocomplete="username" inputmode="email"></div>
<div class="mvb-signup-field"><label for="password">Contraseña</label><div class="mvb-password-field"><input type="password" id="password" name="password" placeholder="Tu contraseña" required autocomplete="current-password"><button type="button" id="toggleLoginPassword" aria-label="Mostrar contraseña" aria-pressed="false">Ver</button></div></div>
<button type="submit" class="mvb-signup-submit">Ingresar a mi cuenta <span aria-hidden="true">↗</span></button>
</form>
<div class="mvb-login-create"><div><h2>¿Todavía no tenés cuenta?</h2></div><a href="<?= BASE_URL ?>/src/views/auth/register.php">Crear mi cuenta <span aria-hidden="true">↗</span></a></div>
<script>
(()=>{
const button=document.getElementById('toggleLoginPassword'),field=document.getElementById('password');
button.addEventListener('click',()=>{const visible=field.type==='password';field.type=visible?'text':'password';button.textContent=visible?'Ocultar':'Ver';button.setAttribute('aria-pressed',String(visible));button.setAttribute('aria-label',visible?'Ocultar contraseña':'Mostrar contraseña');});
})();
</script>
<?php require_once __DIR__ . '/../_layouts/auth.footer.php'; ?>
