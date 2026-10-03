{{-- Filtro de búsqueda reutilizable.
    Uso: @include('partials.search-filter', ['targetId' => 'id-del-contenedor', 'placeholder' => 'Buscar...'])
    Filtra en vivo las filas (tr) o elementos (li) dentro del contenedor con ese id. --}}
<div class="search-filter mb-3" data-filter-target="{{ $targetId ?? 'filterable-list' }}">
    <label for="filtro-{{ $targetId ?? 'filterable-list' }}" class="form-label">Filtrar esta lista</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
        <input type="search"
               id="filtro-{{ $targetId ?? 'filterable-list' }}"
               class="form-control"
               placeholder="{{ $placeholder ?? 'Buscar...' }}"
               autocomplete="off">
    </div>
    <small class="text-muted d-none mt-1 filtro-sin-resultados" role="status">No se encontraron resultados.</small>
</div>
