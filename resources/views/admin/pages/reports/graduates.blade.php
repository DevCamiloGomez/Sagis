@extends('admin.layouts.app')

@section('title', 'Reportes graduados')

@section('content-header')
    @include('admin.partials.content-header', [
        'title' => 'Reportes de Información de Graduados',
        'items' => [
            [
                'name' => 'Inicio',
                'route' => route('home'),
                'isActive' => null,
            ],
            [
                'name' => 'Reportes',
                'route' => null,
                'isActive' => null,
            ],
            [
                'name' => 'Graduados',
                'route' => null,
                'isActive' => 'active',
            ],
        ],
    ])
@endsection

@section('css')
<style>
    /* Estilos generales */
    .card-header {
        background-color: #f8f9fa;
    }

    .table-container {
        margin-bottom: 2rem;
    }

    .table-responsive {
        margin-bottom: 1rem;
    }

    /* Estilos de la tabla */
    .table {
        margin-bottom: 1rem;
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    /* Botones y acciones */
    .btn-info {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .btn-info:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    /* Controles de tabla */
    .table-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    /* Selector de registros por página */
    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .per-page-selector select {
        padding: 0.25rem 0.5rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        background-color: #fff;
        font-size: 0.875rem;
    }

    /* Paginación */
    .pagination-container {
        margin-top: 1rem;
        padding: 1rem;
    }

    .pagination-info {
        margin-bottom: 1rem;
        color: #6c757d;
    }

    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        gap: 0.5rem;
    }

    .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #0d6efd;
        background-color: #fff;
        border: 1px solid #dee2e6;
        text-decoration: none;
    }

    .page-link:hover {
        color: #0a58ca;
        background-color: #e9ecef;
    }

    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
    }

    @media (max-width: 768px) {
        .table-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .pagination-container {
            justify-content: center;
        }

        .per-page-selector {
            justify-content: center;
        }
    }

    /* Ocultar SVGs de la paginación */
    .pagination svg {
        display: none;
    }

    /* Estilos para el selector de registros */
    .records-selector {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .records-selector select {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        background-color: #fff;
        cursor: pointer;
    }

    .records-selector label {
        color: #6c757d;
        margin-bottom: 0;
    }

    /* Estilos para la barra de búsqueda */
    .search-container {
        margin-bottom: 1rem;
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-input {
        flex-grow: 1;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        max-width: 300px;
    }

    .search-input:focus {
        border-color: #0d6efd;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* Botones de exportación */
    .export-buttons {
        display: flex;
        gap: 0.5rem;
    }

    /* Botón Excel */
    .btn-excel {
        background-color: #217346;
        border-color: #217346;
        color: #fff;
    }

    .btn-excel:hover {
        background-color: #1e6339;
        border-color: #1a552f;
        color: #fff;
    }

    /* Botón PDF */
    .btn-pdf {
        background-color: #ff0000;
        border-color: #ff0000;
        color: #fff;
    }

    .btn-pdf:hover {
        background-color: #cc0000;
        border-color: #cc0000;
        color: #fff;
    }
</style>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><b>Reporte de graduados</b></h3>
                                <div class="export-buttons">
                                    <button type="button" class="btn btn-sm btn-excel" id="exportExcel">
                                        <i class="fas fa-file-excel"></i> Exportar a Excel
                                    </button>
                                    <button type="button" class="btn btn-sm btn-pdf" id="exportPdf">
                                        <i class="fas fa-file-pdf"></i> Exportar a PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="search-container">
                                <input type="text" 
                                       class="search-input" 
                                       placeholder="Buscar por nombre o cédula..." 
                                       value="{{ request('search') }}"
                                       id="searchInput">
                            </div>
                            <div class="records-selector">
                                <label for="per_page">Mostrar</label>
                                <select id="per_page" class="form-select form-select-sm" onchange="changePerPage(this.value)">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <label>registros</label>
                            </div>
                            <div class="table-container">
                                <div class="table-responsive">
                                    <table id="graduatesTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Cedula</th>
                                                <th>Correo personal</th>
                                                <th>Celular</th>
                                                <th>Año grado</th>
                                                <th>Programa</th>
                                                <th>Facultad</th>
                                                <th>Universidad</th>
                                                <th>Empresa actual</th>
                                                <th>Salario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($items as $item)
                                                @php
                                                    $person = $item->person;
                                                    $personAcademic = $person->personAcademic->first();
                                                    $personCompany = $person->personCompany->first();
                                                @endphp
                                                <tr>
                                                    <td>
                                                        @if($person)
                                                            {{ $person->name }} {{ $person->lastname }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>{{ $person->document }}</td>
                                                    <td>{{ $person->email }}</td>
                                                    <td>{{ $person->phone }}</td>
                                                    <td>{{ $personAcademic ? $personAcademic->year : 'N/A' }}</td>
                                                    <td>{{ $personAcademic ? $personAcademic->program->name : 'N/A' }}</td>
                                                    <td>{{ $personAcademic ? $personAcademic->program->faculty->name : 'N/A' }}</td>
                                                    <td>{{ $personAcademic ? $personAcademic->program->faculty->university->name : 'N/A' }}</td>
                                                    <td>{{ $personCompany ? $personCompany->company->name : 'N/A' }}</td>
                                                    <td>{{ $personCompany ? '$'.number_format($personCompany->salary, 0, ',', '.') : 'N/A' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">No hay registros disponibles</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pagination-container">
                                    <div class="pagination-info">
                                        Mostrando {{ $items->firstItem() ?? 0 }} a {{ $items->lastItem() ?? 0 }} de {{ $items->total() }} registros
                                    </div>
                                    <div class="pagination">
                                        @if ($items->onFirstPage())
                                            <span class="page-item disabled">
                                                <span class="page-link">Anterior</span>
                                            </span>
                                        @else
                                            <span class="page-item">
                                                <a class="page-link" href="{{ $items->previousPageUrl() }}">Anterior</a>
                                            </span>
                                        @endif

                                        @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                                            @if ($page == $items->currentPage())
                                                <span class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </span>
                                            @else
                                                <span class="page-item">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </span>
                                            @endif
                                        @endforeach

                                        @if ($items->hasMorePages())
                                            <span class="page-item">
                                                <a class="page-link" href="{{ $items->nextPageUrl() }}">Siguiente</a>
                                            </span>
                                        @else
                                            <span class="page-item disabled">
                                                <span class="page-link">Siguiente</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
<script>
    let searchTimeout;

    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }

    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const url = new URL(window.location.href);
            const searchValue = e.target.value.trim();
            
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            
            window.location.href = url.toString();
        }, 500);
    });

    document.getElementById('exportExcel').onclick = function() {
        window.location.href = '{{ route("admin.reports.graduates") }}?export=excel';
    };

    document.getElementById('exportPdf').onclick = function() {
        window.location.href = '{{ route("admin.reports.graduates") }}?export=pdf';
    };
</script>
@endsection
