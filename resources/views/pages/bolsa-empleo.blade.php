@extends('layouts.app')

@section('tittle', 'Bolsa de Empleo UFPS')

@section('content')
<style>
    .bolsa-empleo-container {
        padding: 2rem 0;
        min-height: 80vh;
    }
    
    .bolsa-empleo-header {
        background: linear-gradient(135deg, #aa1916 0%, #d32f2f 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .bolsa-empleo-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: bold;
    }
    
    .bolsa-empleo-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }
    
    .iframe-container {
        position: relative;
        width: 100%;
        min-height: 800px;
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background: #f5f5f5;
    }
    
    .iframe-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .loading-message {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #666;
    }
    
    .external-link-notice {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 5px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .external-link-notice a {
        color: #856404;
        text-decoration: none;
        font-weight: bold;
    }
    
    .external-link-notice a:hover {
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .bolsa-empleo-header h1 {
            font-size: 1.5rem;
        }
        
        .iframe-container {
            min-height: 600px;
        }
    }
</style>

<div class="container bolsa-empleo-container">
    <div class="bolsa-empleo-header">
        <h1><i class="fas fa-briefcase"></i> Bolsa de Empleo UFPS</h1>
        <p>Accede a las oportunidades laborales disponibles en la Universidad Francisco de Paula Santander</p>
    </div>
    
    <div class="iframe-container">
        <div class="loading-message" id="loadingMessage">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Cargando bolsa de empleo...</p>
        </div>
        <iframe 
            id="bolsaEmpleoIframe"
            src="https://ww2.ufps.edu.co/universidad/egresados/2228"
            allowfullscreen
            onload="document.getElementById('loadingMessage').style.display='none';"
            title="Bolsa de Empleo UFPS">
        </iframe>
    </div>
    
    <div class="external-link-notice">
        <i class="fas fa-info-circle"></i>
        <strong>Nota:</strong> Si la bolsa de empleo no se muestra correctamente, puedes acceder directamente 
        <a href="https://ww2.ufps.edu.co/universidad/egresados/2228" target="_blank" rel="noopener noreferrer">
            aquí <i class="fas fa-external-link-alt"></i>
        </a>
    </div>
</div>

<script>
    // Manejar errores de carga del iframe
    document.getElementById('bolsaEmpleoIframe').addEventListener('error', function() {
        document.getElementById('loadingMessage').innerHTML = 
            '<i class="fas fa-exclamation-triangle fa-2x text-warning"></i>' +
            '<p class="mt-2">No se pudo cargar la bolsa de empleo.</p>' +
            '<a href="https://ww2.ufps.edu.co/universidad/egresados/2228" target="_blank" class="btn btn-primary mt-2">Abrir en nueva ventana</a>';
    });
</script>
@endsection
