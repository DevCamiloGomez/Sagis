<!-- Filters -->
<!-- Main content -->
<form method="GET" id="filters">
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="search" name="title" class="form-control form-control-lg"
                            placeholder="Buscar en Noticias" value="{{ getParamValue($params, 'title') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <label class="input-group-text" for="inputGroupSelect02">Ordenar por:</label>
                    </div>
                    <select class="custom-select" name="order_by">
                        <option value="3" {{ isSelectedOption($params, 'order_by', '3') }}>Más recientes primero</option>
                        <option value="4" {{ isSelectedOption($params, 'order_by', '4') }}>Más antiguos primero</option>
                        <option value="1" {{ isSelectedOption($params, 'order_by', '1') }}>A-Z</option>
                        <option value="2" {{ isSelectedOption($params, 'order_by', '2') }}>Z-A</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <!--fecha ini -->
                    <div class="col-md-5">
                        <div class="row">
                            <label class="col-sm-3 col-form-label">Desde:</label>
                            <div class="col-sm-8">
                                <input name="created_at_from" onclick="desabilitar()" type="date"
                                    class="form-control" value="{{ getParamValue($params, 'created_at_from') }}">
                            </div>
                        </div>
                    </div>

                    <!--fecha fin -->
                    <div class="col-md-5">
                        <div class="row mr-3">
                            <label class="col-sm-3 col-form-label">Hasta:</label>
                            <div class="col-sm-8">
                                <input onclick="limitarFecha()" name="created_at_to" type="date" class="form-control"
                                    value="{{ getParamValue($params, 'created_at_to') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-2">
            <button class="btn btn-sm btn-outline-danger" type="submit">Filtrar</button>
        </div>
    </div>

</form>
<!-- ./Filters -->
