<div class="container-fluid mt-4">
    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>Foto/Video</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Description</th>
                    <th>Fecha de Publicación</th>
                    <th>Acciones</th>
                    <th>Cantidad de <span class="text-primary">Fotos</span>/<span class="text-success">Videos</span>
                    </th>

                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                <tr>
                    <td>
                        {{-- {{ dd($item->postCategory->name != 'Vídeos');}} --}}
                        @if($item->postCategory->name != 'Vídeos')
                        {{-- {{ dd($item->imageHeader()) }} --}}
                        <img src="{{ asset($item->imageHeader()->fullasset()) }}" alt="" width="55rem">


                        @else
                        <img src="https://www.uncommunitymanager.es/wp-content/uploads/seo_google_youtube.jpg" alt=""
                            width="55rem">
                        @endif

                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->postCategory->name }}</td>
                    <td style="white-space: pre-wrap;">{{ Str::limit(strip_tags($item->description), 100) }}</td>
                    <td>{{ $item->date }}</td>
                    <td>
                        <div class="btn-group">
                            <div class="mr-3 ml-1">
                                <a href="{{ route('admin.posts.edit', $item->id) }}" class="btn btn-sm btn-warning"><i
                                        class="fas fa-edit"></i></a>
                            </div>


                            <div class="mr-3">
                                @if($item->postCategory->id != 5)
                                <a href="{{ route('admin.posts.images', $item->id) }}" class="btn btn-sm btn-info"><em
                                    class="fas fa-image"></em></a>
                                @else
                                    <button disabled><em class="fas fa-image"></em></button>
                                @endif
                                

                                
                            </div>

                           {{--  @if($item->getCountimage()>= 2) --}}
                            <div class="mr-1">
                                <form action="{{ route('admin.posts.destroy', $item->id) }}" id="{{ $item->id }}"
                                    method="POST" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><em
                                            class="fas fa-trash"></em></button>
                                </form>
                              
                            </div>
                          {{--   @else
                            <div class="mr-1">
                                <button disabled><em class="fas fa-image"></em></button>

                            </div>

                            @endif --}}


                        </div>
                    </td>

                    <td class="text-center ">
                            @if($item->postCategory->name != "Vídeos" && $item->getCountimage()>=1 &&
                            $item->postCategory->name != "Galería"&& !(is_null($item->videoHeader())))

                            <div class="row">
                            <div class="col-md-6">
                                @if($item->postCategory->name != "Vídeos" && $item->getCountimage()>=1)
                                <h3 class="text-primary">{{ $item->getCountimage() }}</h3>
                                {{-- @else
                                <h3 class="text-success">{{ $item->getCountVideo() }}</h3> --}}
                                @endif

                            </div>

                            <div class="col-md-6">
                                @if( $item->postCategory->name != "Galería"&& !(is_null($item->videoHeader())))
                                <h3 class="text-success">1</h3>
                                @endif

                            </div>

                        </div>

                            @elseif($item->postCategory->name != "Vídeos" && $item->getCountimage()>=1 )
                            @if($item->postCategory->name != "Vídeos" && $item->getCountimage()>=1)
                                <h3 class="text-primary">{{ $item->getCountimage() }}</h3>
                                {{-- @else
                                <h3 class="text-success">{{ $item->getCountVideo() }}</h3> --}}
                                @endif

                            @elseif($item->postCategory->name != "Galería"&& !(is_null($item->videoHeader())))
                            @if( $item->postCategory->name != "Galería"&& !(is_null($item->videoHeader())))
                            <h3 class="text-success">1</h3>
                            @endif


                            @endif




                        

                        <div class="mr-3 ml-1">

                        </div>

                        <div class="mr-3">

                        </div>







                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12">No hay registros..</td>
                </tr>
                @endforelse


            </tbody>
        </table>
    </div>

</div>