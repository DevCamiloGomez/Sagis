<div class="container ">
    <div class="row g-5">
        @forelse ($items as $item)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card " style="width: 21rem; height: 580px;" id="card">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{ $item->videoHeader()->fullAsset() }}"
                            allowfullscreen></iframe>
                    </div>
                    <div class="card-body">
                        <p class="fecha">{{ $item->date }}</p>
                        <h5 class="card-title"  id="card-title"> <a href="" class="vinculoTitulo">{{ $item->title }}</a></h5>
                        <div class="card-text" id="card-text">{!! $item->description !!}</div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('videos.show', $item->id) }}" class="botonGC btn btn-danger">Leer
                            más...</a>
                        {{-- <a href="#" class="botonGC btn btn-danger">Leer más...</a> --}}
                    </div>
                </div>
            </div>
        @empty
            <h4 class="mb-4">No hay videos registrados.</h4>
            <img src="https://cdn-icons-png.flaticon.com/512/85/85488.png" alt="No hay">
        @endforelse
    </div>


</div>
