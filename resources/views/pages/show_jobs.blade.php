@extends('layouts.app')

@section('title', 'Datos Laborales')


@section('content')


<!-- Main content -->
<section class=" content mt-4">
    <div class="container-fluid col-11 my-4">
        <div class="row">
            <div class="col-12">



                <!-- Job Information -->
                <div class="card mb-5 mt-3">
                    <div class="card-header  border-info">
                        <h3 class="card-title"><b>Datos laborales</b> </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-striped table-bordered" class="table1" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Nombre Empresa</th>
                                        <th>Lugar Empresa</th>
                                        <th>Dirección Empresa</th>
                                        <th>Correo Empresa</th>
                                        <th>Telefono Empresa</th>
                                        <th>Actualmente laborando</th>
                                        <th>Salario</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(graduate_user()->person->has_data_company() == false)
                                    <a href="{{ route('validate_jobs') }}" class="btn btn-sm btn-warning ">No tengo más
                                        datos laborales</a>
                                    @endif
                                    @forelse ($laborales as $laboral)
                                    @php
                                    $company = $laboral->company;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->index }}</td>
                                        <td>{{ $company ? $company->name : 'Empresa no disponible' }}</td>
                                        <td>{{ $company && $company->city ? $company->city->name : 'N/A' }}</td>
                                        <td>{{ $company ? $company->address : 'N/A' }}</td>
                                        <td>{{ $company ? $company->email : 'N/A' }}</td>
                                        <td>{{ $company ? $company->phone : 'N/A' }}</td>
                                        <td>{{ transformBoolToText($laboral->in_working, 'Si', 'No') }}</td>
                                        <td>${{ getFormatoNumber($laboral->salary) }}</td>
                                        <td>
                                            <div class="icons-acciones">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <a style="text-decoration: none; color: #000000;"
                                                            href="{{ route('edit_jobs',  $laboral->id  ) }}">

                                                            <button type="button"
                                                                class="btn btn-sm btn-success fas fa-edit"
                                                                style="width: 30px; height: 30px"></button>
                                                        </a>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <form action="{{ route('destroy_jobs',  $laboral->id) }}"
                                                            id="{{ $item->id }}" method="POST"
                                                            class="formulario-eliminar">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger btnDelete"
                                                                style="width: 30px; height: 30px"><em
                                                                    class="fas fa-trash"></em></button>
                                                        </form>

                                                    </div>
                                                </div>

                                            </div>

                                        </td>

                                    </tr>
                                     
                                    @empty
                                    <tr>
                                        <td colspan="12">Vacío.</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>

                            <div class="mt-3">
                                <a href="{{ route('create_jobs') }}" class="btn btn-sm btn-danger">
                                    Añadir nuevo
                                    Dato Laboral</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->


                </div>
                <!-- ./ Job Information -->



            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>


@endsection

@section('js')
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>


@endsection


@section('custom_js')

@include('admin.partials.button_delete')


<script>
    $(function() {
        $('table.display').DataTable({
            "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print"]
            , "language": {

                "processing": "Procesando..."
                , "lengthMenu": "Mostrar _MENU_ registros"
                , "zeroRecords": "No se encontraron resultados"
                , "emptyTable": "Ningún dato disponible en esta tabla"
                , "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros"
                , "infoFiltered": "(filtrado de un total de _MAX_ registros)"
                , "search": "Buscar:"
                , "infoThousands": ","
                , "loadingRecords": "Cargando..."
                , "paginate": {
                    "first": "Primero"
                    , "last": "Último"
                    , "next": "Siguiente"
                    , "previous": "Anterior"
                }
                , "aria": {
                    "sortAscending": ": Activar para ordenar la columna de manera ascendente"
                    , "sortDescending": ": Activar para ordenar la columna de manera descendente"
                }
                , "buttons": {
                    "copy": "Copiar"
                    , "colvis": "Visibilidad"
                    , "collection": "Colección"
                    , "colvisRestore": "Restaurar visibilidad"
                    , "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape."
                    , "copySuccess": {
                        "1": "Copiada 1 fila al portapapeles"
                        , "_": "Copiadas %ds fila al portapapeles"
                    }
                    , "copyTitle": "Copiar al portapapeles"
                    , "csv": "CSV"
                    , "excel": "Excel"
                    , "pageLength": {
                        "-1": "Mostrar todas las filas"
                        , "_": "Mostrar %d filas"
                    }
                    , "pdf": "PDF"
                    , "print": "Imprimir"
                    , "renameState": "Cambiar nombre"
                    , "updateState": "Actualizar"
                    , "createState": "Crear Estado"
                    , "removeAllStates": "Remover Estados"
                    , "removeState": "Remover"
                    , "savedStates": "Estados Guardados"
                    , "stateRestore": "Estado %d"
                }
                , "autoFill": {
                    "cancel": "Cancelar"
                    , "fill": "Rellene todas las celdas con <i>%d<\/i>"
                    , "fillHorizontal": "Rellenar celdas horizontalmente"
                    , "fillVertical": "Rellenar celdas verticalmentemente"
                }
                , "decimal": ","
                , "searchBuilder": {
                    "add": "Añadir condición"
                    , "button": {
                        "0": "Constructor de búsqueda"
                        , "_": "Constructor de búsqueda (%d)"
                    }
                    , "clearAll": "Borrar todo"
                    , "condition": "Condición"
                    , "conditions": {
                        "date": {
                            "after": "Despues"
                            , "before": "Antes"
                            , "between": "Entre"
                            , "empty": "Vacío"
                            , "equals": "Igual a"
                            , "notBetween": "No entre"
                            , "notEmpty": "No Vacio"
                            , "not": "Diferente de"
                        }
                        , "number": {
                            "between": "Entre"
                            , "empty": "Vacio"
                            , "equals": "Igual a"
                            , "gt": "Mayor a"
                            , "gte": "Mayor o igual a"
                            , "lt": "Menor que"
                            , "lte": "Menor o igual que"
                            , "notBetween": "No entre"
                            , "notEmpty": "No vacío"
                            , "not": "Diferente de"
                        }
                        , "string": {
                            "contains": "Contiene"
                            , "empty": "Vacío"
                            , "endsWith": "Termina en"
                            , "equals": "Igual a"
                            , "notEmpty": "No Vacio"
                            , "not": "Diferente de"
                            , "startsWith": "Empieza con"
                        }
                    }
                    , "data": "Datos"
                    , "leftTitle": "Título izquierdo"
                    , "logicAnd": "Y"
                    , "logicOr": "O"
                    , "rightTitle": "Título derecho"
                    , "title": {
                        "_": "Constructor de búsqueda (%d)"
                        , "0": "Constructor de búsqueda"
                    }
                    , "value": "Valor"
                }
                , "searchPanes": {
                    "clearMessage": "Borrar todo"
                    , "collapse": {
                        "_": "Paneles de búsqueda (%d)"
                        , "0": "Paneles de búsqueda"
                    }
                    , "count": "{total}"
                    , "countFiltered": "{shown} ({total})"
                    , "emptyPanes": "Sin paneles de búsqueda"
                    , "loadMessage": "Cargando paneles de búsqueda"
                    , "title": "Filtros Activos - %d"
                    , "showMessage": "Mostrar Todo"
                    , "collapseMessage": "Colapsar Todo"
                }
                , "select": {
                    "cells": {
                        "1": "1 celda seleccionada"
                        , "_": "%d celdas seleccionadas"
                    }
                    , "columns": {
                        "1": "1 columna seleccionada"
                        , "_": "%d columnas seleccionadas"
                    }
                    , "rows": {
                        "1": "1 fila seleccionada"
                        , "_": "%d filas seleccionadas"
                    }
                }
                , "thousands": "."
                , "datetime": {
                    "previous": "Anterior"
                    , "next": "Siguiente"
                    , "hours": "Horas"
                    , "minutes": "Minutos"
                    , "seconds": "Segundos"
                    , "unknown": "-"
                    , "amPm": [
                        "am"
                        , "pm"
                    ]
                    , "weekdays": [
                        "Dom"
                        , "Lun"
                        , "Mar"
                        , "Mié"
                        , "Jue"
                        , "Vie"
                        , "Sáb"
                    ]
                    , "months": [
                        "Enero"
                        , "Febrero"
                        , "Marzo"
                        , "Abril"
                        , "Mayo"
                        , "Junio"
                        , "Julio"
                        , "Agosto"
                        , "Septiembre"
                        , "Octubre"
                        , "Noviembre"
                        , "Diciembre"
                    ]
                }
                , "editor": {
                    "close": "Cerrar"
                    , "create": {
                        "button": "Nuevo"
                        , "title": "Crear Nuevo Registro"
                        , "submit": "Crear"
                    }
                    , "edit": {
                        "button": "Editar"
                        , "title": "Editar Registro"
                        , "submit": "Actualizar"
                    }
                    , "remove": {
                        "button": "Eliminar"
                        , "title": "Eliminar Registro"
                        , "submit": "Eliminar"
                        , "confirm": {
                            "_": "¿Está seguro que desea eliminar %d filas?"
                            , "1": "¿Está seguro que desea eliminar 1 fila?"
                        }
                    }
                    , "error": {
                        "system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;)."
                    }
                    , "multi": {
                        "title": "Múltiples Valores"
                        , "info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, hacer clic o tocar aquí, de lo contrario conservarán sus valores individuales."
                        , "restore": "Deshacer Cambios"
                        , "noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo."
                    }
                }
                , "info": "Mostrando _START_ a _END_ de _TOTAL_ registros"
                , "stateRestore": "Estado %d"
                , "processing": "Procesando..."
                , "lengthMenu": "Mostrar _MENU_ registros"
                , "zeroRecords": "No se encontraron resultados"
                , "emptyTable": "Ningún dato disponible en esta tabla"
                , "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros"
                , "infoFiltered": "(filtrado de un total de _MAX_ registros)"
                , "search": "Buscar:"
                , "infoThousands": ","
                , "loadingRecords": "Cargando..."
                , "paginate": {
                    "first": "Primero"
                    , "last": "Último"
                    , "next": "Siguiente"
                    , "previous": "Anterior"
                }
                , "aria": {
                    "sortAscending": ": Activar para ordenar la columna de manera ascendente"
                    , "sortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    });
</script>
@endsection