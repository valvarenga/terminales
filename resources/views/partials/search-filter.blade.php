{{-- Filtro de búsqueda reutilizable.
    Uso: @include('partials.search-filter', ['targetId' => 'id-del-contenedor', 'placeholder' => 'Buscar...'])
    Filtra en vivo las filas (tr) o elementos (li) dentro del contenedor con ese id. --}}
<div class="search-filter mb-3" data-filter-target="{{ $targetId ?? 'filterable-list' }}">
    <label for="filtro-{{ $targetId ?? 'filterable-list' }}" class="form-label visually-hidden">Buscar</label>
    <div class="input-group">
        <span class="input-group-text">🔍</span>
        <input type="search"
               id="filtro-{{ $targetId ?? 'filterable-list' }}"
               class="form-control"
               placeholder="{{ $placeholder ?? 'Buscar...' }}"
               autocomplete="off">
    </div>
    <small class="text-muted d-none mt-1 filtro-sin-resultados">No se encontraron resultados.</small>
</div>
