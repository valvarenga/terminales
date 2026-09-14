document.addEventListener('DOMContentLoaded', function () {

    function initializeMunicipioAutocomplete(inputId, hiddenId) {

        const input = document.querySelector(inputId);
        const hidden = document.querySelector(hiddenId);

        if (!input || !hidden) {
            console.error('No se encontró:', inputId, hiddenId);
            return;
        }

        console.log('Autocompletado iniciado:', inputId);

        const container = input.parentElement;

        container.classList.add('municipio-autocomplete-container');

        const results = document.createElement('div');

        results.className = 'municipio-autocomplete-results';
        results.setAttribute('role', 'listbox');

        container.appendChild(results);

        let requestNumber = 0;
        let debounceTimer = null;

        function clearResults() {
            results.innerHTML = '';
            results.hidden = true;
        }

        function choose(municipio) {

            console.log(
                'Municipio seleccionado:',
                inputId,
                municipio
            );

            input.value = municipio.value;
            hidden.value = municipio.id;

            console.log(
                'ID guardado en',
                hiddenId,
                ':',
                hidden.value
            );

            clearResults();
        }

        function search(term) {

            const currentRequest = ++requestNumber;

            const endpoint =
                input.form &&
                input.form.dataset.municipiosUrl
                    ? input.form.dataset.municipiosUrl
                    : '/search/municipios';

            const request = new XMLHttpRequest();

            request.open(
                'GET',
                endpoint + '?term=' + encodeURIComponent(term),
                true
            );

            request.setRequestHeader(
                'Accept',
                'application/json'
            );

            request.onload = function () {

                if (
                    request.status < 200 ||
                    request.status >= 300 ||
                    currentRequest !== requestNumber
                ) {
                    return;
                }

                let municipios;

                try {
                    municipios = JSON.parse(
                        request.responseText
                    );
                } catch (error) {

                    console.error(
                        'Respuesta inválida:',
                        request.responseText
                    );

                    clearResults();
                    return;
                }

                console.log(
                    'Municipios encontrados para',
                    inputId,
                    ':',
                    municipios
                );

                if (!Array.isArray(municipios)) {
                    clearResults();
                    return;
                }

                results.innerHTML = '';

                municipios.forEach(function (municipio) {

                    const option =
                        document.createElement('button');

                    option.type = 'button';

                    option.className =
                        'municipio-autocomplete-option';

                    option.setAttribute(
                        'role',
                        'option'
                    );

                    option.textContent =
                        municipio.value;

                    /*
                     * Usamos mousedown para que el blur
                     * del input no impida seleccionar.
                     */
                    option.addEventListener(
                        'mousedown',
                        function (event) {

                            event.preventDefault();

                            choose(municipio);
                        }
                    );

                    results.appendChild(option);
                });

                results.hidden =
                    municipios.length === 0;
            };

            request.onerror = function () {

                console.error(
                    'Error consultando municipios'
                );

                clearResults();
            };

            request.send();
        }

        input.addEventListener(
            'input',
            function () {

                /*
                 * Si el usuario modifica el texto,
                 * el ID anterior deja de ser válido.
                 */
                hidden.value = '';

                clearTimeout(debounceTimer);

                const term =
                    input.value.trim();

                if (term.length < 2) {

                    ++requestNumber;

                    clearResults();

                    return;
                }

                debounceTimer = setTimeout(
                    function () {
                        search(term);
                    },
                    300
                );
            }
        );

        input.addEventListener(
            'keydown',
            function (event) {

                if (event.key === 'Escape') {
                    clearResults();
                }
            }
        );

        input.addEventListener(
            'blur',
            function () {

                setTimeout(
                    clearResults,
                    300
                );
            }
        );
    }


    initializeMunicipioAutocomplete(
        '#origen',
        '#origen_id'
    );

    initializeMunicipioAutocomplete(
        '#destino',
        '#destino_id'
    );

});