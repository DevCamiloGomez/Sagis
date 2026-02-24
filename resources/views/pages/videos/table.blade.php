<div class="container">
    <div class="row g-4">
        @forelse ($items as $item)
            <div class="col-12 col-md-6 col-lg-4 d-flex">
                <div class="card w-100 shadow-sm" style="border-radius: 10px; overflow: hidden; display: flex; flex-direction: column;">
                    <div class="embed-responsive embed-responsive-16by9" style="flex-shrink: 0;">
                        <iframe class="embed-responsive-item"
                                src="{{ $item->videoHeader()->fullAsset() }}"
                                allowfullscreen></iframe>
                    </div>
                    <div class="card-body d-flex flex-column" style="flex: 1; padding: 1rem;">
                        <p class="fecha mb-1" style="font-size: 0.8rem; color: #999;">{{ $item->date }}</p>

                        <h5 class="mb-2" style="font-size: 1.1rem; font-weight: 700; line-height: 1.4;
                                                 display: -webkit-box; -webkit-line-clamp: 2;
                                                 -webkit-box-orient: vertical; overflow: hidden;">
                            <a href="{{ route('videos.show', $item->id) }}"
                               class="vinculoTitulo" style="text-decoration: none; color: inherit;">
                                {{ $item->title }}
                            </a>
                        </h5>

                        <div style="font-size: 0.875rem; color: #555; line-height: 1.5;
                                    display: -webkit-box; -webkit-line-clamp: 3;
                                    -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                            {!! strip_tags($item->description) !!}
                        </div>
                    </div>
                    <div class="card-footer" style="background: transparent; border-top: 1px solid #eee; padding: 0.75rem 1rem;">
                        <a href="{{ route('videos.show', $item->id) }}" class="botonGC btn btn-danger btn-sm">Leer más...</a>
                    </div>
                </div>
            </div>
        @empty
            <h4 class="mb-4">No hay videos registrados.</h4>
            <img src="https://cdn-icons-png.flaticon.com/512/85/85488.png" alt="No hay">
        @endforelse
    </div>
</div>
