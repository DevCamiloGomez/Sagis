@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Gestión del Carrusel</h3>
                <a href="{{ route('admin.carousel.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Agregar Imagen
                </a>
            </div>
        </div>
    </div>

    @if(session('alert'))
        <div class="alert alert-{{ session('alert')['icon'] }} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-{{ session('alert')['icon'] }}"></i> {{ session('alert')['title'] }}</h5>
            {{ session('alert')['message'] }}
        </div>
    @endif

    <div class="row">
        <!-- Carrusel Principal -->
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-home mr-1"></i> Carrusel Principal
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-primary">{{ $mainImages->count() }} imágenes</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-valign-middle">
                            <thead>
                                <tr>
                                    <th style="width: 40px"></th>
                                    <th>Imagen</th>
                                    <th>Título</th>
                                    <th>Estado</th>
                                    <th style="width: 100px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-main" class="sortable-area" data-type="main">
                                @forelse($mainImages as $image)
                                <tr data-id="{{ $image->id }}">
                                    <td class="text-center">
                                        <i class="fas fa-grip-vertical text-muted handle" style="cursor: move"></i>
                                    </td>
                                    <td>
                                        <img src="{{ $image->fullAsset() }}" alt="" class="img-thumbnail shadow-sm" style="height: 40px; width: 60px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;" title="{{ $image->title }}">
                                            <strong>{{ $image->title ?: 'Sin título' }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $image->is_active ? 'success' : 'danger' }}">
                                            {{ $image->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.carousel.edit', $image) }}" class="btn btn-default btn-sm" title="Editar">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a>
                                            <form action="{{ route('admin.carousel.destroy', $image) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-default btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No hay imágenes en el carrusel principal.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carrusel de Sección -->
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-layer-group mr-1"></i> Carrusel de Sección
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info">{{ $sectionImages->count() }} imágenes</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-valign-middle">
                            <thead>
                                <tr>
                                    <th style="width: 40px"></th>
                                    <th>Imagen</th>
                                    <th>Título</th>
                                    <th>Estado</th>
                                    <th style="width: 100px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-section" class="sortable-area" data-type="section">
                                @forelse($sectionImages as $image)
                                <tr data-id="{{ $image->id }}">
                                    <td class="text-center">
                                        <i class="fas fa-grip-vertical text-muted handle" style="cursor: move"></i>
                                    </td>
                                    <td>
                                        <img src="{{ $image->fullAsset() }}" alt="" class="img-thumbnail shadow-sm" style="height: 40px; width: 60px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;" title="{{ $image->title }}">
                                            <strong>{{ $image->title ?: 'Sin título' }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $image->is_active ? 'success' : 'danger' }}">
                                            {{ $image->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.carousel.edit', $image) }}" class="btn btn-default btn-sm" title="Editar">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a>
                                            <form action="{{ route('admin.carousel.destroy', $image) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-default btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No hay imágenes en el carrusel de sección.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    $(".sortable-area").sortable({
        handle: '.handle',
        placeholder: "sortable-placeholder",
        update: function(event, ui) {
            let orders = [];
            let $container = $(this);
            
            $container.find('tr').each(function(index) {
                let id = $(this).data('id');
                if (id) {
                    orders.push({
                        id: id,
                        order: index
                    });
                }
            });

            $.ajax({
                url: '{{ route("admin.carousel.update-order") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    orders: orders
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Orden actualizado');
                    }
                },
                error: function() {
                    toastr.error('Error al actualizar el orden');
                }
            });
        }
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    .sortable-placeholder {
        background: #f4f6f9;
        height: 60px;
        visibility: visible !important;
    }
    .handle:hover {
        color: #333 !important;
    }
    .table-valign-middle td {
        vertical-align: middle;
    }
    .card-title {
        font-weight: 600;
    }
</style>
@endpush
 