document.querySelectorAll('.mvb-auth input[type="password"]').forEach(field => {
    const wrapper = document.createElement('div');
    wrapper.className = 'auth-password';
    field.parentNode.insertBefore(wrapper, field);
    wrapper.appendChild(field);
    const button = document.createElement('button');
    button.type = 'button';
    button.textContent = 'Ver';
    button.setAttribute('aria-label', 'Mostrar contraseña');
    button.setAttribute('aria-pressed', 'false');
    button.addEventListener('click', () => {
        const visible = field.type === 'password';
        field.type = visible ? 'text' : 'password';
        button.textContent = visible ? 'Ocultar' : 'Ver';
        button.setAttribute('aria-label', visible ? 'Ocultar contraseña' : 'Mostrar contraseña');
        button.setAttribute('aria-pressed', String(visible));
    });
    wrapper.appendChild(button);
});
const confirmation = document.getElementById('repetir_clave');
const password = document.getElementById('clave');
if (confirmation && password) {
    const validate = () => confirmation.setCustomValidity(confirmation.value && confirmation.value !== password.value ? 'Las contraseñas no coinciden.' : '');
    confirmation.addEventListener('input', validate);
    password.addEventListener('input', validate);
}
