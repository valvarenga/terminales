// Static lists are indexed once; each keystroke only compares text and visibility.
(function () {
    const indexes = new WeakMap();
    const selector = 'article, li, .row > [class*="col-"]';

    function index(container) {
        if (indexes.has(container)) return indexes.get(container);
        const tableRows = [...container.querySelectorAll('tbody tr')];
        const items = tableRows.length ? tableRows : [...container.querySelectorAll(selector)].filter(item => {
            const ancestor = item.parentElement.closest(selector);
            return !ancestor || ancestor === container || !container.contains(ancestor);
        });
        const entries = items.map(element => ({element, text: element.textContent.toLocaleLowerCase()}));
        indexes.set(container, entries);
        return entries;
    }

    document.addEventListener('input', function (event) {
        if (!event.target.matches('.search-filter input[type="search"]')) return;
        const filter = event.target.closest('.search-filter');
        const container = document.getElementById(filter.dataset.filterTarget);
        if (!container) return;
        const term = event.target.value.trim().toLocaleLowerCase();
        let visible = 0;
        index(container).forEach(({element, text}) => {
            const matches = text.includes(term);
            element.hidden = !matches;
            element.classList.toggle('d-none', !matches);
            if (matches) visible++;
        });
        filter.querySelector('.filtro-sin-resultados')?.classList.toggle('d-none', visible > 0);
    });
})();
