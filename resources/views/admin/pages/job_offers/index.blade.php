@extends('admin.layouts.app')

@section('title', 'Ofertas de Empleo')

@section('content-header')
@include('admin.partials.content-header', [
'title' => 'Gestión de Ofertas de Empleo',
'items' => [
[
'name' => 'Inicio',
'route' => route('admin.home'),
'isActive' => null,
],
[
'name' => 'Ofertas de Empleo',
'route' => null,
'isActive' => 'active',
],
],
])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Ofertas</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.job-offers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Oferta
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="jobOffersTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Empresa</th>
                            <th>Estado</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobOffers as $offer)
                        <tr>
                            <td>{{ $offer->id }}</td>
                            <td>{{ $offer->title }}</td>
                            <td>{{ $offer->company_name }}</td>
                            <td>
                                @if ($offer->is_active)
                                <span class="badge badge-success">Activo</span>
                                @else
                                <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ $offer->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.job-offers.edit', $offer) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.job-offers.toggle', $offer) }}"
                                    class="btn btn-sm {{ $offer->is_active ? 'btn-warning' : 'btn-success' }}">
                                    <i class="fas {{ $offer->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </a>
                                <form action="{{ route('admin.job-offers.destroy', $offer) }}" method="POST"
                                    class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
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
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('#jobOffersTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        }).buttons().container().appendTo('#jobOffersTable_wrapper .col-md-6:eq(0)');

        $('.form-delete').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    });
</script>
@endsection