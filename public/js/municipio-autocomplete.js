document.addEventListener('DOMContentLoaded', function () {
    function initialize(inputId, hiddenId) {
        const input = document.querySelector(inputId), hidden = document.querySelector(hiddenId);
        if (!input || !hidden) return;
        const container = input.parentElement;
        container.classList.add('municipio-autocomplete-container');
        const results = document.createElement('div');
        results.id = input.id + '-suggestions';
        results.className = 'municipio-autocomplete-results';
        results.setAttribute('role', 'listbox');
        results.setAttribute('aria-label', 'Municipios sugeridos para ' + input.id);
        results.hidden = true;
        container.appendChild(results);
        input.setAttribute('role', 'combobox');
        input.setAttribute('aria-autocomplete', 'list');
        input.setAttribute('aria-controls', results.id);
        input.setAttribute('aria-expanded', 'false');
        let requestNumber = 0, debounceTimer, active = -1, municipios = [];
        function clearResults() {
            results.replaceChildren(); results.hidden = true; active = -1; municipios = [];
            input.setAttribute('aria-expanded', 'false');
            input.removeAttribute('aria-activedescendant');
        }
        function choose(municipio) {
            ++requestNumber; clearTimeout(debounceTimer);
            input.value = municipio.value; hidden.value = municipio.id;
            clearResults(); input.focus();
        }
        function setActive(index) {
            const options = [...results.children];
            if (!options.length) return;
            active = (index + options.length) % options.length;
            options.forEach((option, i) => {
                option.setAttribute('aria-selected', String(i === active));
                option.classList.toggle('is-active', i === active);
            });
            input.setAttribute('aria-activedescendant', options[active].id);
            options[active].scrollIntoView({block:'nearest'});
        }
        function search(term, version) {
            const endpoint = input.form?.dataset.municipiosUrl || '/search/municipios';
            const request = new XMLHttpRequest();
            request.open('GET', endpoint + '?term=' + encodeURIComponent(term), true);
            request.setRequestHeader('Accept', 'application/json');
            request.onload = function () {
                if (version !== requestNumber) return;
                clearResults();
                if (request.status < 200 || request.status >= 300) return;
                try { municipios = JSON.parse(request.responseText); } catch (_) { return; }
                if (!Array.isArray(municipios)) { municipios = []; return; }
                municipios.forEach(function (municipio, index) {
                    const option = document.createElement('button');
                    option.type = 'button'; option.tabIndex = -1;
                    option.id = results.id + '-' + index;
                    option.className = 'municipio-autocomplete-option';
                    option.setAttribute('role', 'option');
                    option.setAttribute('aria-selected', 'false');
                    option.textContent = municipio.value;
                    option.addEventListener('mousedown', event => event.preventDefault());
                    option.addEventListener('click', () => choose(municipio));
                    results.appendChild(option);
                });
                results.hidden = !municipios.length;
                input.setAttribute('aria-expanded', String(!!municipios.length));
            };
            request.onerror = function () { if (version === requestNumber) clearResults(); };
            request.send();
        }
        input.addEventListener('input', function () {
            hidden.value = ''; clearTimeout(debounceTimer); clearResults();
            const version = ++requestNumber, term = input.value.trim();
            if (term.length >= 2) debounceTimer = setTimeout(() => search(term, version), 300);
        });
        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') { ++requestNumber; clearTimeout(debounceTimer); clearResults(); }
            if (results.hidden) return;
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault(); setActive(active < 0 ? (event.key === 'ArrowDown' ? 0 : municipios.length - 1) : active + (event.key === 'ArrowDown' ? 1 : -1));
            } else if (event.key === 'Enter' && active >= 0) {
                event.preventDefault(); choose(municipios[active]);
            }
        });
        input.addEventListener('blur', function () {
            ++requestNumber; clearTimeout(debounceTimer); clearResults();
        });
    }
    initialize('#origen', '#origen_id');
    initialize('#destino', '#destino_id');
});
