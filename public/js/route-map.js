document.addEventListener('DOMContentLoaded',function(){
const data = document.getElementById('route-itineraries');
if (!data) return;
const itineraries = JSON.parse(data.textContent);
const options = [...document.querySelectorAll('.route-option')];
const buttons = [...document.querySelectorAll('.route-map-select')];
const note = document.getElementById('route-map-note');
if (!window.L) {
    note.textContent = 'El mapa no pudo cargarse. Puedes consultar todos los horarios y tramos en las tarjetas.';
    buttons.forEach(button => { button.disabled = true; button.textContent = 'Mapa no disponible'; });
    return;
}
const map = L.map('route-map', {scrollWheelZoom:false});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom:19, attribution:'&copy; OpenStreetMap'}).addTo(map);
const routes = itineraries.map(function(legs) {
    const group = L.featureGroup(), points = [];
    let missing = false;
    legs.forEach(function(stops) {
        let segment = [];
        const drawSegment = () => { if (segment.length > 1) L.polyline(segment, {color:'#0465aa', weight:5, opacity:.85}).addTo(group); segment = []; };
        stops.forEach(function(place) {
            if (!place || place.latitud == null || place.longitud == null || place.latitud === '' || place.longitud === '') { missing = true; drawSegment(); return; }
            const point = [Number(place.latitud), Number(place.longitud)];
            if (!Number.isFinite(point[0]) || !Number.isFinite(point[1]) || Math.abs(point[0]) > 90 || Math.abs(point[1]) > 180) { missing = true; drawSegment(); return; }
            segment.push(point); points.push(point);
            const label = document.createElement('strong'); label.textContent = place.nombre;
            L.circleMarker(point, {radius:6, color:'#fff', weight:2, fillColor:'#06457f', fillOpacity:1}).addTo(group).bindPopup(label);
        });
        drawSegment();
    });
    return {group, points, missing};
});
let activeGroup;
function selectRoute(index, scroll) {
    if (activeGroup) map.removeLayer(activeGroup);
    const route = routes[index]; activeGroup = route.group.addTo(map);
    options.forEach((option, i) => option.classList.toggle('is-active', i === index));
    buttons.forEach((button, i) => { button.setAttribute('aria-pressed', String(i === index)); button.innerHTML = '<i class="bi bi-map me-2" aria-hidden="true"></i>' + (i === index ? 'Ruta en el mapa' : 'Ver en el mapa'); });
    map.invalidateSize();
    if (route.points.length) {
        map.fitBounds(route.points, {padding:[28,28], maxZoom:12, animate:false});
        note.textContent = 'Ruta ' + (index + 1) + '. ' + (route.missing ? 'Vista parcial: faltan coordenadas de algunas paradas.' : 'El mapa incluye las paradas del recorrido con coordenadas registradas.');
    } else {
        map.setView([12.8654,-85.2072],7);
        note.textContent = 'Ruta ' + (index + 1) + '. Aún no hay coordenadas registradas para este recorrido.';
    }
    if (scroll && window.matchMedia('(max-width: 991.98px)').matches) document.getElementById('route-map').scrollIntoView({behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block:'center'});
}
buttons.forEach(button => button.addEventListener('click', () => selectRoute(Number(button.dataset.routeIndex), true)));
selectRoute(0, false);
});
