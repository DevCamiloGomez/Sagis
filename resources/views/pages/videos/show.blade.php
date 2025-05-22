@extends('layouts.app')

@section('tittle', 'Videos')

@section('css')
<style>
    .content-wrapper {
        min-height: calc(100vh - 60px); /* Ajusta el 60px según la altura de tu header */
        display: flex;
        flex-direction: column;
    }

    .main-content {
        flex: 1;
    }

    .responsive-iframe {
        width: 100%;
        height: 550px;
    }

    @media (max-width: 768px) {
        .responsive-iframe {
            height: 300px;
        }
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="main-content">
        <div class="container content profile mt-4">
            <div class="row margin-bottom-30">
                <div id="informacionContent" class="mb-margin-bottom-30 shadow-wrapper">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom:20px; border-bottom: 3px solid #aa1916; padding: 0;">
                        <h1 class="pull-left" style="font-size:36px;">Vídeos</h1>
                    </div>
                    <h1 class="tituloinformacion">{{ $item->title }}</h1>
                    <p class="fecha">{{ $item->date }}</p>
                    @if($item->getCountVideo()>0 && !is_null($item->videoHeader()))
                    <div class="text-center d-flex justify-content-center mt-4 mb-4">
                        <div class="card" style="width: 35rem;" id="card">
                            <div class="card-body">
                                <div class="embed-responsive embed-responsive-16by9 responsive-iframe">
                                    <iframe class="embed-responsive-item" src="{{ $videoHeader->fullAsset() }}" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <p style="white-space: pre-wrap;">{!! $item->description !!}</p>
                    <div style="clear:both"></div>
                    <div style="clear:both; min-height:30px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
