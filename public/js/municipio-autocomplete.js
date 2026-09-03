(function () {
    function initializeMunicipioAutocomplete(inputId, hiddenId) {
        const input = document.querySelector(inputId);
        const hidden = document.querySelector(hiddenId);

        if (!input || !hidden) return;

        input.parentElement.classList.add('municipio-autocomplete-container');
        const results = document.createElement('div');
        results.className = 'municipio-autocomplete-results';
        results.setAttribute('role', 'listbox');
        results.hidden = true;
        input.parentElement.appendChild(results);

        let requestNumber = 0;
        let debounceTimer;
        const clearResults = function () {
            results.replaceChildren();
            results.hidden = true;
        };

        const choose = function (municipio) {
            hidden.value = municipio.id;
            input.value = municipio.value;
            clearResults();
        };

        const search = function (term) {
            const currentRequest = ++requestNumber;
            const request = new XMLHttpRequest();
            request.open('GET', '/search/municipios?term=' + encodeURIComponent(term), true);
            request.setRequestHeader('Accept', 'application/json');
            request.onload = function () {
                if (request.status < 200 || request.status >= 300 || currentRequest !== requestNumber) return;

                let municipios;
                try {
                    municipios = JSON.parse(request.responseText);
                } catch (_) {
                    clearResults();
                    return;
                }
                if (currentRequest !== requestNumber || !Array.isArray(municipios)) return;
                clearResults();
                municipios.forEach(function (municipio) {
                    const option = document.createElement('button');
                    option.type = 'button';
                    option.className = 'municipio-autocomplete-option';
                    option.setAttribute('role', 'option');
                    option.textContent = municipio.value;
                    option.addEventListener('mousedown', function (event) {
                        event.preventDefault();
                        choose(municipio);
                    });
                    results.appendChild(option);
                });
                results.hidden = municipios.length === 0;
            };
            request.onerror = function () {
                clearResults();
            };
            request.send();
        };

        input.addEventListener('input', function () {
            hidden.value = '';
            clearTimeout(debounceTimer);
            const term = input.value.trim();
            if (term.length < 2) {
                ++requestNumber;
                clearResults();
                return;
            }
            debounceTimer = setTimeout(function () { search(term); }, 200);
        });
        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') clearResults();
        });
        input.addEventListener('blur', function () { setTimeout(clearResults, 150); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initializeMunicipioAutocomplete('#origen', '#origen_id');
        initializeMunicipioAutocomplete('#destino', '#destino_id');
    });
}());
