(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const list = document.getElementById('bus-stops');
        const template = document.getElementById('bus-stop-template');
        const addButton = document.getElementById('add-stop');
        const warning = document.getElementById('bus-route-warning');
        if (!list || !template || !addButton) return;

        const map = window.L ? L.map('bus-route-map', {scrollWheelZoom: false}).setView([12.8654, -85.2072], 7) : null;
        if (map) L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; OpenStreetMap'}).addTo(map);
        let routeLayer;

        function distance(a, b) {
            const radians = value => value * Math.PI / 180;
            const dLat = radians(b[0] - a[0]), dLng = radians(b[1] - a[1]);
            const value = Math.sin(dLat / 2) ** 2 + Math.cos(radians(a[0])) * Math.cos(radians(b[0])) * Math.sin(dLng / 2) ** 2;
            return 6371 * 2 * Math.atan2(Math.sqrt(value), Math.sqrt(1 - value));
        }

        function refreshMap() {
            if (!map) return;
            if (routeLayer) routeLayer.remove();
            const points = [], missing = [];
            list.querySelectorAll('[data-stop-row]').forEach(function (row) {
                const option = row.querySelector('.stop-municipality').selectedOptions[0];
                if (!option || !option.value) return;
                const lat = Number(option.dataset.lat), lng = Number(option.dataset.lng);
                if (Number.isFinite(lat) && Number.isFinite(lng) && option.dataset.lat !== '' && option.dataset.lng !== '') points.push([lat, lng, option.textContent]);
                else missing.push(option.textContent);
            });
            const group = L.featureGroup();
            points.forEach((point, index) => L.marker([point[0], point[1]]).bindTooltip((index + 1) + '. ' + point[2]).addTo(group));
            if (points.length > 1) L.polyline(points.map(point => [point[0], point[1]]), {color: '#0c705c', weight: 4}).addTo(group);
            group.addTo(map); routeLayer = group;
            if (points.length) map.fitBounds(group.getBounds(), {padding: [25, 25], maxZoom: 12});
            const longJump = points.some((point, index) => index && distance(points[index - 1], point) > 250);
            warning.textContent = missing.length ? 'Sin coordenadas: ' + missing.join(', ') + '.' : (longJump ? 'Revisa el orden: hay paradas consecutivas separadas por más de 250 km.' : 'El mapa respeta el orden seleccionado; no modifica las paradas automáticamente.');
        }

        function reindex() {
            const rows = Array.from(list.querySelectorAll('[data-stop-row]'));
            rows.forEach(function (row, index) {
                row.querySelector('.bus-stop-number').textContent = index + 1;
                row.querySelector('.stop-municipality').name = `paradas[${index}][municipio_id]`;
                row.querySelector('.stop-municipality').setAttribute('aria-label', `Municipio de parada ${index + 1}`);
                row.querySelector('.stop-time').name = `paradas[${index}][hora_paso]`;
                row.querySelector('.stop-time').setAttribute('aria-label', `Hora de paso en parada ${index + 1}`);
                row.querySelector('.stop-fare').name = `paradas[${index}][tarifa_acumulada]`;
                row.querySelector('.stop-fare').setAttribute('aria-label', `Tarifa acumulada en parada ${index + 1}`);
                row.querySelector('.move-up').disabled = index === 0;
                row.querySelector('.move-down').disabled = index === rows.length - 1;
                row.querySelector('.remove-stop').disabled = rows.length <= Number(list.dataset.minStops);
            });
            filterOriginMunicipalities();
            refreshMap();
        }

        function filterOriginMunicipalities() {
            const terminal = document.getElementById('terminal');
            if (!terminal) return;
            const departmentId = terminal.selectedOptions[0]?.dataset.departamento || '';
            const selects = Array.from(list.querySelectorAll('.stop-municipality'));
            selects.forEach(function (select, index) {
                Array.from(select.options).forEach(function (option) {
                    if (!option.value) return;
                    const unavailable = index === 0 && (!departmentId || option.dataset.departamento !== departmentId);
                    option.hidden = unavailable;
                    option.disabled = unavailable;
                });
            });
            const origin = selects[0];
            if (!origin) return;
            origin.options[0].textContent = departmentId ? 'Seleccione municipio de origen' : 'Seleccione primero una terminal';
            if (origin.selectedOptions[0]?.disabled) {
                origin.value = '';
                syncLegacyFields();
            }
        }

        function syncLegacyFields() {
            const rows = Array.from(list.querySelectorAll('[data-stop-row]'));
            const first = rows[0], last = rows[rows.length - 1];
            if (!first || !last) return;
            document.getElementById('legacy-origin').value = first.querySelector('.stop-municipality').value;
            document.getElementById('legacy-destination').value = last.querySelector('.stop-municipality').value;
            document.getElementById('legacy-departure').value = first.querySelector('.stop-time').value;
            document.getElementById('legacy-arrival').value = last.querySelector('.stop-time').value;
            document.getElementById('legacy-fare').value = last.querySelector('.stop-fare').value;
        }

        addButton.addEventListener('click', function () { list.appendChild(template.content.cloneNode(true)); reindex(); });
        list.addEventListener('click', function (event) {
            const row = event.target.closest('[data-stop-row]'); if (!row) return;
            if (event.target.closest('.move-up') && row.previousElementSibling) list.insertBefore(row, row.previousElementSibling);
            if (event.target.closest('.move-down') && row.nextElementSibling) list.insertBefore(row.nextElementSibling, row);
            if (event.target.closest('.remove-stop') && list.children.length > Number(list.dataset.minStops)) row.remove();
            reindex();
        });
        list.addEventListener('change', function () { syncLegacyFields(); filterOriginMunicipalities(); refreshMap(); });
        document.getElementById('terminal')?.addEventListener('change', function () { filterOriginMunicipalities(); refreshMap(); });
        list.closest('form').addEventListener('submit', syncLegacyFields);
        reindex();
        syncLegacyFields();
    });
}());
