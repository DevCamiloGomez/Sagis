@extends('layouts.app')


@section('tittle', 'Noticia')

@section('css')
<style>
/* #img-fluid{
    height: 300px;
} */


</style>
@endsection


@section('content')

    <div class="container content profile mt-4">
        <div class="row margin-bottom-30">
            <div id="informacionContent" class=" mb-margin-bottom-30 shadow-wrapper">

                <div class="col-md-12 col-sm-12 col-xs-12"
                    style="margin-bottom:20px; border-bottom: 3px solid #aa1916; padding: 0;">
                    <h1 class="pull-left" style="font-size:36px;">Galería</h1>
                </div>
                <h1 class="tituloinformacion"> {{ $item->title }} </h1>
                <p class="fecha">{{ $item->date }}</p>
                <div class="text-center  d-flex justify-content-center  mt-4 mb-4">
                    <div class="card" style="width: 32rem;" >
                        <div class="card-body">
                            <img src="{{ asset($imageHeader->fullAsset()) }}" class="imgInformacion img-fluid " alt="" />
                        </div>
                        </div>
                   
                </div>
                <div class="description-content">
                    {!! $item->description !!}
                </div>
                @if (count($images) > 0)
                    <h4 class="font-weight-bold">Imágenes</h4>
                    <div class="row justify-content-center">
                        @forelse ($images as $image)
                            <div class="col-3">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{ asset($image->fullAsset()) }}" alt="" class="img-fluid" id="img-fluid">
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                @endif
        

                <div style="clear:both"></div>
                <div style="clear:both; min-height:30px;"></div>

            </div>
            <!--informacionContent-->

        </div><!-- row margin-bottom-30-->
    </div>
    <!--container content profile-->

@endsection
