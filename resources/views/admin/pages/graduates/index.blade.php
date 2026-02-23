@extends('admin.layouts.app')


@section('title', 'Graduados')

@section('content-header')
@include('admin.partials.content-header', [
'title' => 'Graduados',
'items' => [
[
'name' => 'Inicio',
'route' => route('admin.home'),
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

@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header  border-info">


                        <div class="d-flex justify-content-start">
                            <h3 class="card-title"><b>Graduados registrados</b> </h3>
                        </div>


                        @if($cantidadGraduates!=0)



                        <div class="d-flex justify-content-end">



                            <form action="{{ route('admin.graduates.destroy_all') }}" method="POST"
                                class="formulario-eliminar" class="form">
                                @csrf

                                <button type="submit" class="btn btn-sm btn-danger btn-eliminar" id="delete">Eliminar
                                    Todo</button>
                            </form>

                        </div>

                        @endif

                        <div class="d-flex justify-content-between mt-2 ">

                            <a href="{{ route('admin.graduates.create') }}" class="btn btn-sm btn-primary">
                                Añadir nuevo
                                graduado</a>



                            <div class="mt-2">
                                <form action="{{ route('admin.graduates.import_excel') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <input type="file" name="file"
                                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                        required>

                                    <button class="btn btn-success">Importar graduados</button>




                                </form>
                            </div>

                        </div>


                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3">Total de Graduados: <a class="btn btn-sm btn-danger">{{
                                $cantidadGraduates }}</a></h6>


                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>Cedula</th>
                                    <th>Código</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                    <th>Ver datos completos</th>
                                    <th>Estado verificado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                <tr>
                                    <td>
                                        {{-- <img src="{{ asset($item->person->image_url . $item->person->image) }}"
                                            alt="" width="55rem"> --}}
                                        <img src="{{ asset($item->person->fullAsset()) }}" alt="" width="55rem">
                                        {{-- {{ asset($item->person->fullAsset()) }} --}}
                                    </td>
                                    <td>{{ $item->person->fullName() }}</td>
                                    <td>{{ $item->person->document }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->person->phone }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td style="text-align: center">
                                        <a href="{{ route('admin.graduates.show', $item->person->id) }}">
                                            <button style=" border: none ;background-color: transparent" type="submit">
                                                <img src="{{ asset('img/lupa.png') }}"
                                                    style="display: block; width: 30px; height: 30px; margin:auto;" />
                                            </button>

                                        </a>
                                    </td>
                                    <td>
                                        @if(($item->person->has_data_person() == true) &&
                                        ($item->person->has_data_academic() == true) &&
                                        ($item->person->has_data_company() == true))
                                        <img src="{{ asset('img/aprobado.png') }}" alt=""
                                            style="display: block; width: 30px; height: 30px; margin:auto;">
                                        @else
                                        <img src="{{ asset('img/rechazo.png') }}" alt=""
                                            style="display: block; width: 30px; height: 30px; margin:auto;">
                                        @endif

                                    </td>
                                    <td>
                                        <div class="icons-acciones">
                                            <div class="mr-3">
                                                <a style="text-decoration: none; color: #000000;"
                                                    href="{{ route('admin.graduates.edit', $item->person->id) }}">

                                                    <button type="button" class="btn btn-sm btn-success fas fa-edit"
                                                        style="width: 30px; height: 30px"></button>
                                                </a>
                                            </div>
                                            <div class="mr-3">
                                                <a style="text-decoration: none; color: #000000;"
                                                    href="{{ route('admin.graduates.edit_password', $item->person->id) }}">

                                                    <button type="button" class="btn btn-sm btn-warning fas fa-key"
                                                        style="width: 30px; height: 30px"></button>
                                                </a>
                                            </div>

                                            <div class="mr-3">

                                                <form action="{{ route('admin.graduates.destroy', $item->id) }}"
                                                    id="{{ $item->id }}" method="POST" class="formulario-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger btnDelete"
                                                        style="width: 30px; height: 30px"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">No hay registros..</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>

                        {{-- <form action="" class="mt-5"> --}}


                            {{-- </form> --}}

                    </div>
                    <!-- /.card-body -->


                </div>
                <!-- /.card -->
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


{{-- @include('admin.partials.button_delete') --}}



<script>
    $(function () {
        $("#example1")
            .on('draw.dt', function () {
                console.log('draw.dt');
                eventFiredBtnDeleteSweetAlert(this);
            })

            .DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    {
                        "extend": 'excelHtml5',
                        "text": '<i class="fas fa-file-excel"></i> ',
                        "titleAttr": 'Exportar a Excel',
                        "className": 'btn btn-success',
                        "exportOptions": {
                            "columns": [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        "extend": 'pdfHtml5',
                        "text": '<i class="fas fa-file-pdf"></i> ',
                        "titleAttr": 'Exportar a PDF',
                        "className": 'btn btn-danger',
                        "exportOptions": {
                            "columns": [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        "extend": 'print',
                        "text": '<i class="fa fa-print"></i> ',
                        "titleAttr": 'Imprimir',
                        "className": 'btn btn-info',
                        "exportOptions": {
                            "columns": [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        "extend": 'copy',
                        "text": '<i class="fa fa-copy"></i> ',
                        "titleAttr": 'Copiar',
                        "className": 'btn btn-warning',
                        "exportOptions": {
                            "columns": [1, 2, 3, 4, 5]
                        }
                    },

                ],
                "language": {

                    "processing": "Procesando...",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron resultados",
                    "emptyTable": "Ningún dato disponible en esta tabla",
                    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "search": "Buscar:",
                    "infoThousands": ",",
                    "loadingRecords": "Cargando...",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sortDescending": ": Activar para ordenar la columna de manera descendente"
                    },
                    "buttons": {
                        "copy": "Copiar",
                        "colvis": "Visibilidad",
                        "collection": "Colección",
                        "colvisRestore": "Restaurar visibilidad",
                        "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
                        "copySuccess": {
                            "1": "Copiada 1 fila al portapapeles",
                            "_": "Copiadas %ds fila al portapapeles"
                        },
                        "copyTitle": "Copiar al portapapeles",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Mostrar todas las filas",
                            "_": "Mostrar %d filas"
                        },
                        "pdf": "PDF",
                        "print": "Imprimir",
                        "renameState": "Cambiar nombre",
                        "updateState": "Actualizar",
                        "createState": "Crear Estado",
                        "removeAllStates": "Remover Estados",
                        "removeState": "Remover",
                        "savedStates": "Estados Guardados",
                        "stateRestore": "Estado %d"
                    },
                    "autoFill": {
                        "cancel": "Cancelar",
                        "fill": "Rellene todas las celdas con <i>%d<\/i>",
                        "fillHorizontal": "Rellenar celdas horizontalmente",
                        "fillVertical": "Rellenar celdas verticalmentemente"
                    },
                    "decimal": ",",
                    "searchBuilder": {
                        "add": "Añadir condición",
                        "button": {
                            "0": "Constructor de búsqueda",
                            "_": "Constructor de búsqueda (%d)"
                        },
                        "clearAll": "Borrar todo",
                        "condition": "Condición",
                        "conditions": {
                            "date": {
                                "after": "Despues",
                                "before": "Antes",
                                "between": "Entre",
                                "empty": "Vacío",
                                "equals": "Igual a",
                                "notBetween": "No entre",
                                "notEmpty": "No Vacio",
                                "not": "Diferente de"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vacio",
                                "equals": "Igual a",
                                "gt": "Mayor a",
                                "gte": "Mayor o igual a",
                                "lt": "Menor que",
                                "lte": "Menor o igual que",
                                "notBetween": "No entre",
                                "notEmpty": "No vacío",
                                "not": "Diferente de"
                            },
                            "string": {
                                "contains": "Contiene",
                                "empty": "Vacío",
                                "endsWith": "Termina en",
                                "equals": "Igual a",
                                "notEmpty": "No Vacio",
                                "startsWith": "Empieza con",
                                "not": "Diferente de",
                                "notContains": "No Contiene",
                                "notStarts": "No empieza con",
                                "notEnds": "No termina con"
                            },
                            "array": {
                                "not": "Diferente de",
                                "equals": "Igual",
                                "empty": "Vacío",
                                "contains": "Contiene",
                                "notEmpty": "No Vacío",
                                "without": "Sin"
                            }
                        },
                        "data": "Data",
                        "deleteTitle": "Eliminar regla de filtrado",
                        "leftTitle": "Criterios anulados",
                        "logicAnd": "Y",
                        "logicOr": "O",
                        "rightTitle": "Criterios de sangría",
                        "title": {
                            "0": "Constructor de búsqueda",
                            "_": "Constructor de búsqueda (%d)"
                        },
                        "value": "Valor"
                    },
                    "searchPanes": {
                        "clearMessage": "Borrar todo",
                        "collapse": {
                            "0": "Paneles de búsqueda",
                            "_": "Paneles de búsqueda (%d)"
                        },
                        "count": "{total}",
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Sin paneles de búsqueda",
                        "loadMessage": "Cargando paneles de búsqueda",
                        "title": "Filtros Activos - %d",
                        "showMessage": "Mostrar Todo",
                        "collapseMessage": "Colapsar Todo"
                    },
                    "select": {
                        "cells": {
                            "1": "1 celda seleccionada",
                            "_": "%d celdas seleccionadas"
                        },
                        "columns": {
                            "1": "1 columna seleccionada",
                            "_": "%d columnas seleccionadas"
                        },
                        "rows": {
                            "1": "1 fila seleccionada",
                            "_": "%d filas seleccionadas"
                        }
                    },
                    "thousands": ".",
                    "datetime": {
                        "previous": "Anterior",
                        "next": "Proximo",
                        "hours": "Horas",
                        "minutes": "Minutos",
                        "seconds": "Segundos",
                        "unknown": "-",
                        "amPm": [
                            "AM",
                            "PM"
                        ],
                        "months": {
                            "0": "Enero",
                            "1": "Febrero",
                            "10": "Noviembre",
                            "11": "Diciembre",
                            "2": "Marzo",
                            "3": "Abril",
                            "4": "Mayo",
                            "5": "Junio",
                            "6": "Julio",
                            "7": "Agosto",
                            "8": "Septiembre",
                            "9": "Octubre"
                        },
                        "weekdays": [
                            "Dom",
                            "Lun",
                            "Mar",
                            "Mie",
                            "Jue",
                            "Vie",
                            "Sab"
                        ]
                    },
                    "editor": {
                        "close": "Cerrar",
                        "create": {
                            "button": "Nuevo",
                            "title": "Crear Nuevo Registro",
                            "submit": "Crear"
                        },
                        "edit": {
                            "button": "Editar",
                            "title": "Editar Registro",
                            "submit": "Actualizar"
                        },
                        "remove": {
                            "button": "Eliminar",
                            "title": "Eliminar Registro",
                            "submit": "Eliminar",
                            "confirm": {
                                "_": "¿Está seguro que desea eliminar %d filas?",
                                "1": "¿Está seguro que desea eliminar 1 fila?"
                            }
                        },
                        "error": {
                            "system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
                        },
                        "multi": {
                            "title": "Múltiples Valores",
                            "info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, hacer click o tap aquí, de lo contrario conservarán sus valores individuales.",
                            "restore": "Deshacer Cambios",
                            "noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo."
                        }
                    },
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "stateRestore": {
                        "creationModal": {
                            "button": "Crear",
                            "name": "Nombre:",
                            "order": "Clasificación",
                            "paging": "Paginación",
                            "search": "Busqueda",
                            "select": "Seleccionar",
                            "columns": {
                                "search": "Búsqueda de Columna",
                                "visible": "Visibilidad de Columna"
                            },
                            "title": "Crear Nuevo Estado",
                            "toggleLabel": "Incluir:"
                        },
                        "emptyError": "El nombre no puede estar vacio",
                        "removeConfirm": "¿Seguro que quiere eliminar este %s?",
                        "removeError": "Error al eliminar el registro",
                        "removeJoiner": "y",
                        "removeSubmit": "Eliminar",
                        "renameButton": "Cambiar Nombre",
                        "renameLabel": "Nuevo nombre para %s",
                        "duplicateError": "Ya existe un Estado con este nombre.",
                        "emptyStates": "No hay Estados guardados",
                        "removeTitle": "Remover Estado",
                        "renameTitle": "Cambiar Nombre Estado"
                    }
                }
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });


    });
</script>

<script>

    var eventFiredBtnDeleteSweetAlert = function (jE) {

        // Use sweetalert AFTER DataTables
        $(jE).on('click', '.btnDelete', function (e) {
            // alert("Funciona o nO")
            e.preventDefault();

            var btnDelete = $(this);
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, ¡Estoy seguro!',
                cancelButtonText: 'Cancelar',
                closeOnConfirm: true
            }).then((result) => {
                if (result.isConfirmed) {
                    btnDelete.closest('form').submit();
                    // this.submit();
                } else {
                    return false;
                }

            })
        });

    };


    // Use sweetalert AFTER DataTables
    $(".btn-eliminar").on('click', function (e) {
        /*  alert("Funciona o nO") */
        e.preventDefault();

        var btnDelete2 = $(this);
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, ¡Estoy seguro!',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true
        }).then((result) => {
            if (result.isConfirmed) {
                btnDelete2.closest('form').submit();
                // this.submit();
            } else {
                return false;
            }

        })
    });

</script>

@endsection