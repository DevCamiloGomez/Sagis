<div class="container">
    <div class="row g-4">
        @forelse ($items as $item)
            <div class="col-12 col-md-6 col-lg-4 d-flex">
                <div class="card w-100 shadow-sm" style="border-radius: 10px; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="height: 200px; overflow: hidden; flex-shrink: 0;">
                        <img src="{{ asset($item->imageHeader()->fullAsset()) }}"
                             class="w-100 h-100"
                             style="object-fit: cover;"
                             alt="{{ $item->title }}">
                    </div>
                    <div class="card-body d-flex flex-column" style="flex: 1; padding: 1rem;">
                        <p class="fecha mb-1" style="font-size: 0.8rem; color: #999;">{{ $item->date }}</p>

                        {{-- Título: siempre más grande y prominente --}}
                        <h5 class="mb-2" style="font-size: 1.1rem; font-weight: 700; line-height: 1.4;
                                                 display: -webkit-box; -webkit-line-clamp: 2;
                                                 -webkit-box-orient: vertical; overflow: hidden;">
                            <a href="{{ route('notices.show', $item->id) }}"
                               class="vinculoTitulo" style="text-decoration: none; color: inherit;">
                                {{ $item->title }}
                            </a>
                        </h5>

                        {{-- Descripción: siempre más pequeña y truncada --}}
                        <div style="font-size: 0.875rem; color: #555; line-height: 1.5;
                                    display: -webkit-box; -webkit-line-clamp: 3;
                                    -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                            {!! strip_tags($item->description) !!}
                        </div>
                    </div>
                    <div class="card-footer" style="background: transparent; border-top: 1px solid #eee; padding: 0.75rem 1rem;">
                        <a href="{{ route('notices.show', $item->id) }}" class="botonGC btn btn-danger btn-sm">Leer más...</a>
                    </div>
                </div>
            </div>
        @empty
            <h4 class="mb-4">No hay noticias registradas.</h4>
            <img src="https://img.icons8.com/ios/500/no-image.png" alt="No hay">
        @endforelse
    </div>
</div>
