@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestión del Carrusel</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.carousel.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Agregar Imagen
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('alert'))
                        <div class="alert alert-{{ session('alert')['icon'] }} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-{{ session('alert')['icon'] }}"></i> {{ session('alert')['title'] }}</h5>
                            {{ session('alert')['message'] }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px">Orden</th>
                                    <th style="width: 100px">Imagen</th>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th style="width: 100px">Estado</th>
                                    <th style="width: 150px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="sortable">
                                @foreach($images as $image)
                                <tr data-id="{{ $image->id }}">
                                    <td class="text-center">
                                        <i class="fas fa-grip-vertical handle" style="cursor: move"></i>
                                        <span class="order-number">{{ $image->order }}</span>
                                    </td>
                                    <td>
                                        <img src="{{ $image->fullAsset() }}" 
                                             alt="{{ $image->title }}" 
                                             class="img-thumbnail" 
                                             style="max-height: 50px;">
                                    </td>
                                    <td>{{ $image->title }}</td>
                                    <td>{{ $image->type == 'main' ? 'Carrusel Principal' : 'Carrusel de Sección' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $image->is_active ? 'success' : 'danger' }}">
                                            {{ $image->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.carousel.edit', $image) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.carousel.destroy', $image) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Está seguro de eliminar esta imagen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
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
    $("#sortable").sortable({
        handle: '.handle',
        update: function(event, ui) {
            let orders = [];
            $('.order-number').each(function(index) {
                orders.push({
                    id: $(this).closest('tr').data('id'),
                    order: index
                });
                $(this).text(index);
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
                        toastr.success('Orden actualizado correctamente');
                    } else {
                        toastr.error('Error al actualizar el orden');
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
.handle {
    cursor: move;
    color: #999;
}
.handle:hover {
    color: #666;
}
</style>
@endpush 