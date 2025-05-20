@extends('admin.layouts.app')

@section('title', 'Datos del graduado')

@section('content-header')
    @include('admin.partials.content-header', [
        'title' => 'Datos Generales',
        'items' => [
            [
                'name' => 'Inicio',
                'route' => route('admin.home'),
                'isActive' => null,
            ],
            [
                'name' => 'Datos Generales',
                'route' => null,
                'isActive' => 'active',
            ],
        ],
    ])
@endsection

@section('content')

    <!-- Main content -->
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><b>Datos personales</b> </h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>Código</th>
                                            <th>Celular</th>
                                            <th>Correo institucional</th>
                                            <th>Correo personal</th>
                                            <th>Telefono fijo</th>
                                            <th>Fecha nacimiento</th>
                                            <th>Lugar nacimiento</th>
                                            <th >Dirección residencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $item->document }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->lastname }}</td>
                                            <td>{{ $item->user->code }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->user->email }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->telephone }}</td>
                                            <td>{{ $item->birthdate }}</td>
                                            <td>{{ $item->birthdatePlace->name }}</td>
                                            <td>{{ $item->address }}</td>

                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- Academic Information -->
                    <div class="card">
                        <div class="card-header  border-info">
                            <h3 class="card-title"><b>Datos académicos</b> </h3>
                            
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Nivel Académico</th>
                                            <th>Nombre Programa</th>
                                            <th>Facultad</th>
                                            <th>Universidad</th>
                                            <th>Año Graduado</th>
                                             <th>Acciones </th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($academics as $academic)
                                            <tr>
                                                <td>{{ $loop->index }}</td>
                                                <td>{{ $academic->program->academicLevel->name }}</td>
                                                <td>{{ $academic->program->name }}</td>
                                                <td>{{ $academic->program->faculty->name }}</td>
                                                <td>{{ $academic->program->faculty->university->name }}</td>
                                                <td>{{ $academic->year }}</td>
                                               <td> 

                                                <div class="icons-acciones">
                                                    
                                                    <div class="mr-3">
                                                        <a style="text-decoration: none; color: #000000;"
                                                        href="{{ route('admin.graduates.edit_academic', [$item->id, $academic->id ] ) }}">

                                                            <button type="button" class="btn btn-sm btn-success fas fa-edit"
                                                                style="width: 30px; height: 30px"></button>
                                                        </a>
                                                    </div>

                                                    <div class="mr-3">
                                                        <form action="{{ route('admin.graduates.destroy_academic', [$item->id, $academic->id ]) }}"
                                                            id="{{ $item->id }}" method="POST" class="formulario-eliminar">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger btnDelete" style="width: 30px; height: 30px"><em class="fas fa-trash"></em></button>
                                                        </form>             
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

                                <div class="mt-2">
                                    <a href="{{ route('admin.graduates.create_academic', $item->id) }}" class="btn btn-sm btn-danger">
                                        Añadir nuevo
                                        Dato Académico</a>
                                </div>
                            </div>

                           
                        </div>
                        <!-- /.card-body -->


                    </div>
                    <!-- ./Academic Information -->

                    <!-- Job Information -->
                    <div class="card">
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
                                        @forelse ($laborales as $laboral)
                                            @php
                                                $company = $laboral->company;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->index }}</td>
                                                <td>{{ $company->name }}</td>
                                                <td>{{ $company->country ? $company->country->name . ' - ' : '' }}{{ $company->city ? $company->city->name : 'No especificada' }}</td>
                                                <td>{{ $company->address }}</td>
                                                <td>{{ $company->email }}</td>
                                                <td>{{ $company->phone }}</td>
                                                <td>{{ transformBoolToText($laboral->in_working, 'Si', 'No') }}</td>
                                                <td>${{ getFormatoNumber($laboral->salary) }}</td>
                                                <td>
                                                    <div class="icons-acciones">
                                                    
                                                        <div class="mr-3">
                                                            <a style="text-decoration: none; color: #000000;"
                                                            href="{{ route('admin.graduates.edit_jobs', [$item->id, $laboral->id ] ) }}">
    
                                                                <button type="button" class="btn btn-sm btn-success fas fa-edit"
                                                                    style="width: 30px; height: 30px"></button>
                                                            </a>
                                                        </div>
    
                                                        <div class="mr-3">
                                                            <form action="{{ route('admin.graduates.destroy_jobs', [$item->id, $laboral->id ]) }}"
                                                                id="{{ $item->id }}" method="POST" class="formulario-eliminar">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger btnDelete" style="width: 30px; height: 30px"><em class="fas fa-trash"></em></button>
                                                            </form>             
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

                                <div class="mt-2">
                                    <a href="{{ route('admin.graduates.create_jobs', $item->id) }}" class="btn btn-sm btn-danger">
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

{{-- <script>
            
            var eventFiredBtnDeleteSweetAlert = function(jE) {
                
                // Use sweetalert AFTER DataTables
                $(jE).on('click', '.btnDelete', function(e) {
                    alert("Funciona o nO")
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

</script> --}}


    <script>
        $(function() {
            $('table.display').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"],
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

@endsection
