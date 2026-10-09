<?php
$isRegisterPage = true;
require_once __DIR__ . '/../_layouts/auth.layout.php';
$error = $_GET['error'] ?? null;
?>
<div class="mvb-signup-heading"><p class="mvb-signup-overline">HOLA, BIENVENIDO A MVB</p><h1>Empecemos por vos.</h1><p>Una cuenta para tu auto y todo lo que viene.</p></div>
<p class="mvb-signup-login">¿Ya sos parte? <a href="<?= BASE_URL ?>/src/views/auth/login.php">Iniciá sesión ↗</a></p>
<?php if ($error): ?><div class="mvb-signup-error" role="alert"><strong>No pudimos crear tu cuenta.</strong><span><?php if($error==='email'): ?>Ese correo ya está registrado. Podés iniciar sesión con él.<?php elseif($error==='password'): ?>Las contraseñas no coinciden. Revisalas e intentá de nuevo.<?php else: ?>Revisá los datos ingresados e intentá de nuevo.<?php endif; ?></span></div><?php endif; ?>
<form action="<?= BASE_URL ?>/src/controllers/auth/register.php" method="POST" class="mvb-signup-form" id="signupForm">
<fieldset class="mvb-signup-required"><legend><span>01</span> Datos de tu cuenta <small>Obligatorios</small></legend>
<div class="mvb-signup-grid">
<div class="mvb-signup-field"><label for="nombre">Nombre</label><input type="text" id="nombre" name="nombre" required autocomplete="given-name" placeholder="Tu nombre"></div>
<div class="mvb-signup-field"><label for="apellido">Apellido</label><input type="text" id="apellido" name="apellido" required autocomplete="family-name" placeholder="Tu apellido"></div>
</div>
<div class="mvb-signup-field"><label for="email">Correo electrónico</label><input type="email" id="email" name="email" required autocomplete="email" placeholder="vos@ejemplo.com" inputmode="email"></div>
<div class="mvb-signup-grid">
<div class="mvb-signup-field"><label for="clave">Contraseña</label><div class="mvb-password-field"><input type="password" id="clave" name="clave" minlength="8" required autocomplete="new-password" placeholder="Al menos 8 caracteres" aria-describedby="passwordHint"><button type="button" data-toggle-password="clave" aria-label="Mostrar contraseña" aria-pressed="false">Ver</button></div></div>
<div class="mvb-signup-field"><label for="repetir_clave">Repetí la contraseña</label><div class="mvb-password-field"><input type="password" id="repetir_clave" name="repetir_clave" minlength="8" required autocomplete="new-password" placeholder="Una vez más"><button type="button" data-toggle-password="repetir_clave" aria-label="Mostrar confirmación de contraseña" aria-pressed="false">Ver</button></div></div>
</div>
<p class="mvb-field-hint" id="passwordHint">Usá una contraseña de al menos 8 caracteres.</p>
</fieldset>
<details class="mvb-signup-extras"><summary><span class="mvb-extras-number">02</span><span><strong>Hagamos la cuenta más tuya</strong><small>Contacto, dirección y vehículo · opcional</small></span><span aria-hidden="true" class="mvb-extras-plus">+</span></summary><div class="mvb-extras-content"><p>Podés completar estos datos ahora o más adelante desde tu perfil.</p>
    <!-- Teléfono y Frecuencia de Compra juntos -->
    <div class="row g-2 mb-3">
        <div class="col-6">
            <label for="telefono" class="form-label text-secondary small text-uppercase fw-semibold">WhatsApp / Teléfono</label>
            <input type="tel" class="form-control form-control-premium" id="telefono" name="telefono" placeholder="11 1234-5678">
        </div>
        <div class="col-6">
            <label for="frecuencia_compra" class="form-label text-secondary small text-uppercase fw-semibold">Frecuencia de compra</label>
            <select class="form-select form-control-premium" id="frecuencia_compra" name="frecuencia_compra">
                <option value="" selected>Preferís no decir</option>
                <option value="ocasional">De vez en cuando</option>
                <option value="mensual">Una vez al mes</option>
                <option value="frecuente">Frecuentemente</option>
            </select>
        </div>
    </div>

    <!-- Domicilio acoplado en 3 columnas (Provincia, Ciudad, Dirección) -->
    <div class="row g-2 mb-3">
        <div class="col-4">
            <label for="provincia" class="form-label text-secondary small text-uppercase fw-semibold">Provincia</label>
            <select class="form-select form-control-premium" id="provincia" name="provincia">
                <option value="" selected>Seleccionar...</option>
                <option value="Buenos Aires">Buenos Aires</option>
                <option value="CABA">CABA</option>
                <option value="Catamarca">Catamarca</option>
                <option value="Chaco">Chaco</option>
                <option value="Chubut">Chubut</option>
                <option value="Córdoba">Córdoba</option>
                <option value="Corrientes">Corrientes</option>
                <option value="Entre Ríos">Entre Ríos</option>
                <option value="Formosa">Formosa</option>
                <option value="Jujuy">Jujuy</option>
                <option value="La Pampa">La Pampa</option>
                <option value="La Rioja">La Rioja</option>
                <option value="Mendoza">Mendoza</option>
                <option value="Misiones">Misiones</option>
                <option value="Neuquén">Neuquén</option>
                <option value="Río Negro">Río Negro</option>
                <option value="Salta">Salta</option>
                <option value="San Juan">San Juan</option>
                <option value="San Luis">San Luis</option>
                <option value="Santa Cruz">Santa Cruz</option>
                <option value="Santa Fe">Santa Fe</option>
                <option value="Santiago del Estero">Santiago del Estero</option>
                <option value="Tierra del Fuego">Tierra del Fuego</option>
                <option value="Tucumán">Tucumán</option>
            </select>
        </div>
        <div class="col-4">
            <label for="ciudad" class="form-label text-secondary small text-uppercase fw-semibold">Ciudad / Localidad</label>
            <input type="text" class="form-control form-control-premium" id="ciudad" name="ciudad" placeholder="Ej: Quilmes">
        </div>
        <div class="col-4">
            <label for="direccion" class="form-label text-secondary small text-uppercase fw-semibold">Calle y Número</label>
            <input type="text" class="form-control form-control-premium" id="direccion" name="direccion" placeholder="Ej: Av. Mitre 1234">
        </div>
    </div>

    <!-- Vehículo acoplado -->
    <div class="row g-2 mb-3">
        <div class="col-5">
            <label for="auto_marca" class="form-label text-secondary small text-uppercase fw-semibold">Marca del auto</label>
            <input type="text" class="form-control form-control-premium" id="auto_marca" name="auto_marca" placeholder="Ej: Nissan">
        </div>
        <div class="col-5">
            <label for="auto_modelo" class="form-label text-secondary small text-uppercase fw-semibold">Modelo</label>
            <input type="text" class="form-control form-control-premium" id="auto_modelo" name="auto_modelo" placeholder="Ej: Kicks">
        </div>
        <div class="col-2">
            <label for="auto_anio" class="form-label text-secondary small text-uppercase fw-semibold">Año</label>
            <input type="number" class="form-control form-control-premium" id="auto_anio" name="auto_anio" placeholder="2020" min="1980" max="2030">
        </div>
    </div>


</div></details>
<div class="mvb-signup-preferences"><span class="mvb-preference-title">Mantenete al día, si vos querés.</span>
    <!-- Checkboxes -->
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="acepta_descuentos" name="acepta_descuentos" value="1">
        <label class="form-check-label text-secondary small" for="acepta_descuentos">
            Quiero recibir descuentos y ofertas exclusivas
        </label>
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="acepta_promociones" name="acepta_promociones" value="1">
        <label class="form-check-label text-secondary small" for="acepta_promociones">
            Quiero recibir novedades del negocio por WhatsApp/correo
        </label>
    </div>


</div><button type="submit" class="mvb-signup-submit">Crear mi cuenta <span aria-hidden="true">↗</span></button>
<p class="mvb-signup-bottom">Los datos opcionales y las novedades son siempre tu elección.</p>
</form>
<script>
(()=>{
const password=document.getElementById('clave'),confirmation=document.getElementById('repetir_clave');
const validate=()=>confirmation.setCustomValidity(confirmation.value&&confirmation.value!==password.value?'Las contraseñas no coinciden.':'');
password.addEventListener('input',validate);confirmation.addEventListener('input',validate);
document.querySelectorAll('[data-toggle-password]').forEach(button=>button.addEventListener('click',()=>{
const field=document.getElementById(button.dataset.togglePassword),visible=field.type==='password';
field.type=visible?'text':'password';button.textContent=visible?'Ocultar':'Ver';button.setAttribute('aria-pressed',String(visible));
button.setAttribute('aria-label',(visible?'Ocultar ':'Mostrar ')+(field.id==='clave'?'contraseña':'confirmación de contraseña'));
}));
})();
</script>
<?php require_once __DIR__ . '/../_layouts/auth.footer.php'; ?>
