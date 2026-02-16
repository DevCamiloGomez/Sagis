@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card border-0 shadow-lg rounded-lg overflow-hidden">
                <div class="card-header text-white py-4 text-center" style="background-color: #aa1916;">
                    <h2 class="mb-0 font-weight-bold">Tratamiento de Datos Personales</h2>
                    <p class="mb-0 mt-2 opacity-75 text-white">Ley 1581 de 2012 - Habeas Data</p>
                </div>
                <div class="card-body p-5">
                    <div class="privacy-content" style="max-height: 400px; overflow-y: auto; padding-right: 15px; border-bottom: 1px solid #eee; margin-bottom: 2rem;">
                        <h4 class="mb-4" style="color: #aa1916;">Autorización para el Tratamiento de Datos Personales</h4>
                        
                        <p>En cumplimiento de lo dispuesto en la <strong>Ley 1581 de 2012</strong> y su Decreto Reglamentario 1377 de 2013, yo, en mi calidad de titular de la información, de manera libre, expresa, voluntaria e informada, autorizo a la <strong>Universidad Francisco de Paula Santander (UFPS)</strong> y su sistema de seguimiento de graduados <strong>SAGIS</strong>, para que recolecten, almacenen, usen, circulen y supriman mis datos personales que han sido suministrados al momento de mi registro en esta plataforma.</p>

                        <p>Entiendo que mis datos serán tratados para las siguientes finalidades:</p>
                        <ul>
                            <li>Mantener una base de datos actualizada de los graduados de la institución.</li>
                            <li>Realizar seguimiento al desempeño profesional y situación laboral de los graduados.</li>
                            <li>Enviar información sobre programas académicos, cursos, eventos, noticias y oportunidades laborales.</li>
                            <li>Elaborar reportes estadísticos solicitados por el Ministerio de Educación Nacional y otros entes gubernamentales.</li>
                            <li>Facilitar la comunicación institucional con el graduado.</li>
                        </ul>

                        <h5 style="color: #aa1916;">Derechos del Titular</h5>
                        <p>Como titular de los datos personales, tengo derecho a:</p>
                        <ul>
                            <li>Conocer, actualizar y rectificar mis datos personales frente a los responsables del tratamiento.</li>
                            <li>Solicitar prueba de la autorización otorgada.</li>
                            <li>Ser informado respecto del uso que se le ha dado a mis datos.</li>
                            <li>Presentar ante la Superintendencia de Industria y Comercio quejas por infracciones a lo dispuesto en la ley.</li>
                            <li>Revocar la autorización y/o solicitar la supresión del dato cuando no se respeten los principios, derechos y garantías constitucionales y legales.</li>
                        </ul>

                        <p class="mt-4 text-muted small italic">Esta autorización es obligatoria para el acceso y uso de los servicios prestados por la plataforma SAGIS.</p>
                    </div>

                    <form action="{{ route('privacy-policy.accept') }}" method="POST" id="privacy-form">
                        @csrf
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="acceptCheck" name="accept" required>
                            <label class="custom-control-label font-weight-bold" for="acceptCheck">
                                He leído y acepto los términos y condiciones del tratamiento de mis datos personales.
                            </label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-lg px-5 shadow-sm text-white" id="btnAccept" disabled style="background-color: #aa1916;">
                                <i class="fas fa-check-circle mr-2"></i> Confirmar y Continuar
                            </button>
                        </div>
                    </form>

                    <div class="mt-3 text-right">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_css')
<style>
    .card { border-radius: 15px !important; }
    .card-header { border-bottom: 0; }
    .privacy-content::-webkit-scrollbar { width: 6px; }
    .privacy-content::-webkit-scrollbar-thumb { background: #aa1916; border-radius: 10px; }
    .privacy-content::-webkit-scrollbar-track { background: #f7fafc; }
    .custom-control-label { cursor: pointer; padding-top: 2px; }
    .btn-lg { border-radius: 10px; font-weight: 600; }
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #aa1916;
        border-color: #aa1916;
    }
</style>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        $('#acceptCheck').on('change', function() {
            var isChecked = $(this).is(':checked');
            console.log('Checkbox changed:', isChecked);
            $('#btnAccept').prop('disabled', !isChecked);
        });
    });
</script>
@endsection
