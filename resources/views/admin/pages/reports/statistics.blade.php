@extends('admin.layouts.app')

@section('title', 'Estadisticas graduados')



@section('content-header')
    @include('admin.partials.content-header', [
        'title' => 'Estadísticas y gráficas',
        'items' => [
            [
                'name' => 'Inicio',
                'route' => route('admin.home'),
                'isActive' => null,
            ],
            [
                'name' => 'Reportes',
                'route' => null,
                'isActive' => null,
            ],
            [
                'name' => 'Estadísticas',
                'route' => null,
                'isActive' => 'active',
            ],
        ],
    ])
@endsection

@section('css')
<style>
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
    <!-- Hidden form for PDF Export -->
    <form id="pdfForm" action="{{ route('admin.reports.statistics.pdf') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="salary_chart" id="input_salary_chart">
        <input type="hidden" name="companies_chart" id="input_companies_chart">
        <input type="hidden" name="pie_chart" id="input_pie_chart">
        <input type="hidden" name="bar_chart" id="input_bar_chart">
    </form>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">



                    <div class="card">
                        <div class="card-header  border-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><b>Estadísticas Generales</b> </h3>
                                <div class="export-buttons">
                                    <button type="button" onclick="submitPdf()" class="btn btn-sm btn-pdf">
                                        <i class="fas fa-file-pdf"></i> Exportar a PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- ... (rest of the card body remains the same but I am not replacing it here to save tokens if possible, but replace_file_content needs contigous block) ... -->
                            <!-- To ensure safety I will just replace up to card-body and let the user know I only updated the header part, wait, I need to match valid block. -->
                            <!-- Actually I will just re-write the header section and the end section with JS changes. The tool doesn't support multiple blocks nicely if they are far apart. -->
                            <!-- I will use multi_replace for safety if I need to touch multiple places, but here I can probably just replace the header part first. -->
                            <!-- Wait, I need to add the hidden form SOMEWHERE. I'll add it at the start of 'content'. -->
                            <!-- And I need to update the button. -->
                            <!-- And I need to update the JS. -->
                            
                            <!-- Let's do it in one go if I can match the context, or use multi_replace. -->
                        
                            <!-- Small Box (Stat card) -->

                            <div class="row">
                                <div class="col-lg-4 col-6">
                                    <!-- small card -->
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $posts }}</h3>

                                            <p>Contenidos Informativos</p>
                                        </div>
                                        <div class="icon">
                                            <i class="far fa-file"></i>
                                        </div>

                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-4 col-6">
                                    <!-- small card -->
                                    <a href="{{ route('admin.reports.graduates', ['filter' => 'foreign']) }}" class="small-box-footer" >
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $extraGraduates }}</h3>

                                            <p>Graduados en empresas Extranjeras</p>
                                        </div>
                                        <div class="icon">
                                            <i class='fa fa-plane'></i>
                                        </div>
                                        <div class="small-box-footer">Ver detalle <i class="fas fa-arrow-circle-right"></i></div>
                                    </div>
                                    </a>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-4 col-6">
                                    <!-- small card -->
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $graduates }}</h3>

                                            <p>Graduados registrados</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-user-plus"></i>
                                        </div>

                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-4 col-6">
                                    <!-- small card -->
                                    <a href="{{ route('admin.reports.graduates', ['filter' => 'min_salary']) }}" class="small-box-footer" >
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>${{ getFormatoNumber($salaryMin) }}</h3>

                                            <p>Salario mínimo</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-money-bill"></i>
                                        </div>
                                        <div class="small-box-footer">Ver detalle <i class="fas fa-arrow-circle-right"></i></div>
                                    </div>
                                    </a>
                                </div>

                                <!-- ./col -->
                                <div class="col-lg-4 col-6">
                                    <!-- small card -->
                                    <a href="{{ route('admin.reports.graduates', ['filter' => 'unemployed']) }}" class="small-box-footer" >
                                    <div class="small-box bg-secondary">
                                        <div class="inner">
                                            <h3>{{ $graduadoSinTrabajo }}</h3>

                                            <p>Graduados desempleados</p>
                                        </div>
                                        <div class="icon">
                                            <i class='fas fa-sad-cry'></i>
                                        </div>
                                        <div class="small-box-footer">Ver detalle <i class="fas fa-arrow-circle-right"></i></div>
                                    </div>
                                    </a>
                                </div>

                                <!-- ./col -->
                                <div class="col-lg-4 col-6">
                                    <!-- small card -->
                                    <a href="{{ route('admin.reports.graduates', ['filter' => 'max_salary']) }}" class="small-box-footer" >
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>${{ getFormatoNumber($salaryMax) }}</h3>

                                            <p>Salario máximo</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="small-box-footer">Ver detalle <i class="fas fa-arrow-circle-right"></i></div>
                                    </div>
                                    </a>
                                </div>
                                <!-- ./col -->
                            </div>
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

    <!-- Salary Distribution and Top Companies -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Distribución Salarial</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="salaryChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Top Empresas Empleadoras</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="companiesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">



                    <div class="card">
                        <div class="card-header  border-info">
                            <h3 class="card-title"><b>Gráfica de torta: Número de graduados por el pais donde trabaja</b>
                            </h3>
                        </div>

                       {{--  {{ dd($graduatesByCountry->count()) }} --}}
                            @if($graduatesByCountry->count()==0)
                            <div class="card-body" >
                                <h1 id="pieP">No hay registros</h1>
                            </div>
                            @else

                              <!-- PIE CHART -->

                        <div class="card-body">
                            <h1 id="pieP"  style="display: none;">Pie</h1>
                            <div class="chart-container" style="position: relative; height:60vh; width:80vw; margin: auto;">
                                <canvas id="pieChart" data-countries="{{ $countriesWorking }}"
                                    data-colors="{{ $arrayColors }}" data-total="{{ $graduatesByCountry }}"></canvas>
                            </div>


                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                            @endif
                      


                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">



                    <div class="card">
                        <div class="card-header  border-info">
                            <h3 class="card-title"><b>Gráfica de barras: Número de graduados por año</b> </h3>
                        </div>




                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChart" data-years="{{ $years }}"
                                    data-total="{{ $graduatesByYearTotals }}"
                                    style="min-height: 450px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
                            </div>


                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->


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
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

@endsection


@section('custom_js')

    <script>
        // Function to capture charts and submit form
        function submitPdf() {
            // Helper to safe dataURL
            function getChartImage(id) {
                var canvas = document.getElementById(id);
                if (canvas) {
                    return canvas.toDataURL('image/png');
                }
                return '';
            }

            document.getElementById('input_salary_chart').value = getChartImage('salaryChart');
            document.getElementById('input_companies_chart').value = getChartImage('companiesChart');
            document.getElementById('input_pie_chart').value = getChartImage('pieChart');
            document.getElementById('input_bar_chart').value = getChartImage('barChart');

            document.getElementById('pdfForm').submit();
        }

        $(function() {
            // ... (rest of the chart initialization logic)


            // Salary Chart
            var salaryCanvas = document.getElementById('salaryChart');
            if(salaryCanvas){
                var salaryCtx = salaryCanvas.getContext('2d');
                var salaryData = {
                    labels: {!! json_encode($salaryLabels) !!},
                    datasets: [{
                        label: 'Número de Graduados',
                        backgroundColor: 'rgba(23, 162, 184, 0.9)',
                        borderColor: 'rgba(23, 162, 184, 0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: {!! json_encode($salaryValues) !!}
                    }]
                };
                new Chart(salaryCtx, {
                    type: 'bar',
                    data: salaryData,
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        legend: { display: false },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1
                                }
                            }]
                        }
                    }
                });
            }

            // Top Companies Chart
            var companiesCanvas = document.getElementById('companiesChart');
            if(companiesCanvas){
                var companiesCtx = companiesCanvas.getContext('2d');
                var companiesData = {
                    labels: {!! json_encode($topCompaniesLabels) !!},
                    datasets: [{
                        label: 'Graduados Contratados',
                        backgroundColor: [
                            '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc'
                        ],
                        data: {!! json_encode($topCompaniesValues) !!}
                    }]
                };
                new Chart(companiesCtx, {
                    type: 'horizontalBar',
                    data: companiesData,
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        legend: { display: false },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1
                                }
                            }]
                        }
                    }
                });
            }


            let pie = document.getElementById('pieP');
            if(pie){
                let valuePie = pie.innerHTML;
                
                if(valuePie != "No hay registros"){
                    var countries = $('#pieChart').data('countries');
                    var colors = $('#pieChart').data('colors');
                    var total = $('#pieChart').data('total');

                    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
                    var pieData = {
                        labels: countries,
                        datasets: [{
                            data: total,
                            backgroundColor: colors,
                        }]
                    }
                    var pieOptions = {
                        maintainAspectRatio: false,
                        responsive: true,
                    }
                    new Chart(pieChartCanvas, {
                        type: 'pie',
                        data: pieData,
                        options: pieOptions
                    })
                }
            }

            var years = $('#barChart').data('years');
            var total = $('#barChart').data('total');
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = {
                labels: years,
                datasets: [{
                    label: 'Graduados Año',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: total,
                }]
            }

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })
        })
    </script>

@endsection
