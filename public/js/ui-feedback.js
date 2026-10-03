document.addEventListener('DOMContentLoaded', function () {
    const summary = document.querySelector('[data-error-summary]');
    if (summary) summary.focus();
    document.querySelectorAll('.is-invalid').forEach(field => field.setAttribute('aria-invalid', 'true'));
    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        const input = document.getElementById(button.getAttribute('aria-controls'));
        if (!input) return;
        button.hidden = false;
        button.addEventListener('click', function () {
            const visible = input.type === 'password';
            input.type = visible ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(visible));
            button.textContent = visible ? 'Ocultar' : 'Mostrar';
        });
    });
});
