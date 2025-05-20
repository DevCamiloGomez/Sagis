<div class="container ">
    <div class="row g-5">
        @forelse ($items as $item)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card " style="width: 21rem; height: 580px;" id="card">
                    <div class="card-img-container" style="height: 200px; overflow: hidden;">
                        <img src="{{ asset($item->imageHeader()->fullAsset()) }}" class="card-img-top" style="width: 100%; height: 100%; object-fit: cover;" alt="...">
                    </div>
                    <div class="card-body">
                        <p class="fecha">{{ $item->date }}</p>
                        <h5 class="card-title"  id="card-title"> <a href="" class="vinculoTitulo">{{ $item->title }}</a></h5>
                        <div class="card-text" id="card-text">{!! $item->description !!}</div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('events.show', $item->id) }}" class="botonGC btn btn-danger">Leer
                            más...</a>
                    </div>
                </div>
            </div>
        @empty
          <h4 class="mb-4">No hay eventos registrados.</h4>
          <img src="https://img.icons8.com/ios/500/no-image.png" alt="No hay">
        @endforelse
    </div>


</div>
