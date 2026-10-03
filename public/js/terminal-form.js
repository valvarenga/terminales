document.addEventListener('DOMContentLoaded', function () {
    const department = document.getElementById('departamento');
    const municipality = document.getElementById('municipio');
    if (!department || !municipality) return;
    let version = 0;

    function message(text, disabled) {
        municipality.replaceChildren(new Option(text, ''));
        municipality.disabled = disabled;
    }

    async function load(selected = '') {
        const current = ++version;
        const id = department.value;
        message(id ? 'Cargando municipios...' : 'Seleccione primero un departamento', true);
        if (!id) return;
        try {
            const response = await fetch(department.dataset.municipiosUrl + '/' + encodeURIComponent(id), {
                headers: {Accept: 'application/json'},
            });
            if (!response.ok) throw new Error('Municipality request failed');
            const items = await response.json();
            if (current !== version) return;
            if (!Array.isArray(items)) throw new Error('Invalid municipality response');
            message(items.length ? 'Seleccione un municipio' : 'No hay municipios disponibles', false);
            items.forEach(item => municipality.add(new Option(item.nombre, item.id, false, String(item.id) === String(selected))));
        } catch (_) {
            if (current === version) message('No se pudieron cargar los municipios', false);
        }
    }

    department.addEventListener('change', () => load());
    if (department.value) load(department.dataset.selectedMunicipio);
});
