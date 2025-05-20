@if ($editMode)
    <form action="{{ route('admin.posts.update', [$item->id]) }}" method="post"  enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- PostCategory -->
        <div class="form-group">
            <label>Categoría de la Publicación:</label>
            <select name="post_category_id" id="categories" onchange="seleccionarCategoria()"
                class="form-control select2bs4 @error('post_category_id') is-invalid @enderror" >
                <option value="-1">Seleccione una categoría de publicación..</option>
                @foreach ($postCategories as $postCategory)
                    <option value="{{ $postCategory->id }}"
                        {{ isSelectedOld($item->post_category_id, $postCategory->id) }}>{{ $postCategory->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('post_category_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- PostCategory -->

        <!-- Title -->
        <div class="form-group">
            <label>Título:</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                value="{{ $item->title }}">
        </div>
        @error('title')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Title -->

        <!-- Descripción -->
        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="description" id="summernote" rows="5" class="form-control @error('description') is-invalid @enderror">{{$item->description}}</textarea>
        </div>
        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Descripción -->

        <!-- Date -->
        <div class="form-group">
            <label>Fecha de Publicación:</label>
            <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" id="datePickerId"
                value="{{ $item->date }}">
        </div>
        @error('date')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Date -->

       {{--  {{dd($item->postCategory->name == "Vídeos" &&  $item->getCountimage()==0);  }} --}}
    
        @if($item->postCategory->name != "Vídeos" &&  $item->getCountimage()>=1)
                     <!-- Imagen Principal -->
         <div class="form-group" id="imageP">
            <label>Imagen principal:</label>
            <div class="text-center">
                <img style="width: 340px; height: 340px" src="{{ asset($imageHeader->fullAsset() ) }}" alt="">

            </div>
            <div class="mt-2">
                <input type="file" class="form-control-file"  name="imagen"  accept="image/*" >
            </div>   
        </div>
        @error('imagen')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Imagen principal -->

        <div class="form-group"  id="videoP">
            <label>URL video de Youtube:</label>
            <div class="mt-2">
                @if($item->getCountVideo()>0 && !(is_null($item->videoHeader())))

                @if($item->postCategory->name != "Galería" )
                <small>Si desea eliminar el video, solamente coloque: <strong>NO</strong>; No aplica en categoría Vídeos.</small>
                @endif
               
                    <input type="text"  value="{{ $videoHeader->fullAsset() }}" id="inputVideo" class="form-control-file" name="video" 
                    pattern="((?:https?:\/\/)?(?:www\.)?youtu(?:\.be\/|be.com\/\S*(?:watch|embed)(?:(?:(?=\/[-a-zA-Z0-9_]{11,}(?!\S))\/)|(?:\S*v=|v\/)))([-a-zA-Z0-9_]{11,})|NO)" 
                    title="Debe comenzar por https://youtu.be/ o https://www.youtube.com/watch?v=, o si quiere eliminar el video NO, excepto en categoría Vídeos" >
                @else
                <input type="text" class="form-control-file"  id="inputVideo" name="video" 
                pattern="(?:https?:\/\/)?(?:www\.)?youtu(?:\.be\/|be.com\/\S*(?:watch|embed)(?:(?:(?=\/[-a-zA-Z0-9_]{11,}(?!\S))\/)|(?:\S*v=|v\/)))([-a-zA-Z0-9_]{11,})"
                title="Debe comenzar por https://youtu.be/ o https://www.youtube.com/watch?v=" >
                @endif
                
            </div>   
        </div>
        @error('video')
            <small class="text-danger">{{ $message }}</small>
        @enderror

        @elseif ($item->postCategory->name == "Vídeos" &&  $item->getCountimage()==0)
        <div class="form-group" style="display: none;" id="imageP">
            
            <div class="mt-2" >
                <input type="file" class="form-control-file" id="inputImage"   name="imagen" accept="image/*"  required  >
            </div>   
        </div>
        @error('imagen')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <div class="form-group"  id="videoP">
            <label>URL video de Youtube:</label>
            <div class="mt-2">
                @if($item->postCategory->name != "Galería" )
                <small>Si desea eliminar el video, solamente coloque: <strong>NO</strong>; No aplica en categoría Vídeos.</small>
                @endif
               
                <input type="text"  value="{{ $videoHeader->fullAsset() }}"  id="inputVideo" class="form-control-file" name="video" required 
                pattern="((?:https?:\/\/)?(?:www\.)?youtu(?:\.be\/|be.com\/\S*(?:watch|embed)(?:(?:(?=\/[-a-zA-Z0-9_]{11,}(?!\S))\/)|(?:\S*v=|v\/)))([-a-zA-Z0-9_]{11,})|NO)" 
                title="Debe comenzar por https://youtu.be/ o https://www.youtube.com/watch?v=, o si quiere eliminar el video NO, excepto en categoría Vídeos" >
            </div>   
        </div>
        @error('video')
            <small class="text-danger">{{ $message }}</small>
        @enderror

            
        @endif

        



        <!-- Submit -->
        <div class="form-group">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-sm btn-danger">Guardar</button>
                <div class="ml-5">
                    <a class="btn btn-sm btn-warning " style="color:black;
                    text-decoration: none;" href="{{ route('admin.posts.index') }}">Regresar</a>
                </div>
            </div>
        </div>
        <!-- ./Submit -->
    </form>
@else
    <form action="{{ route('admin.posts.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <!-- PostCategory -->
        <div class="form-group">
            <label>Categoría de la Publicación:</label>
            <select name="post_category_id" id="categories" onchange="seleccionarCategoria2()"
                class="form-control select2bs4 @error('post_category_id') is-invalid @enderror">
                <option value="-1">Seleccione una categoría de publicación..</option>
                @foreach ($postCategories as $postCategory)
                    <option value="{{ $postCategory->id }}"
                        {{ isSelectedOld(old('post_category_id'), $postCategory->id) }}>
                        {{ $postCategory->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('post_category_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- PostCategory -->

        <!-- Title -->
        <div class="form-group">
            <label>Título:</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                value="{{ old('title') }}">
        </div>
        @error('title')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Title -->

        <!-- Descripción -->
        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="description" id="summernote" rows="5" class="form-control @error('description') is-invalid @enderror">{{old('description')}}</textarea>
        </div>
        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Descripción -->

        <!-- Date -->
        <div class="form-group">
            <label>Fecha de Publicación:</label>
            <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" id="datePickerId"
                value="{{ old('date') }}">
        </div>
        @error('date')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- ./Date -->


        <!-- File -->
        <div class="form-group" id ="imageP" >
            <label for="exampleFormControlFile1">Fotos de noticias: <small class="text-muted">(Obligatorio) - La última foto seleccionada será la principal.</small></label>
            <input type="file" class="form-control-file" name="image[]" id="inputImage" multiple accept="image/*" required>
        </div>
        @error('image.*')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        @error('image')
        <small class="text-danger">{{ $message }}</small>
    @enderror
        <!-- ./File -->

        <div class="form-group"  id="videoP">
            <label>URL video de Youtube:</label>
            <div class="mt-2">        
                <input type="text" class="form-control-file" id="videoInput" name="video" pattern="(?:https?:\/\/)?(?:www\.)?youtu(?:\.be\/|be.com\/\S*(?:watch|embed)(?:(?:(?=\/[-a-zA-Z0-9_]{11,}(?!\S))\/)|(?:\S*v=|v\/)))([-a-zA-Z0-9_]{11,})" title="Debe comenzar por https://youtu.be/ o https://www.youtube.com/watch?v=">
               {{-- ((?:https?:\/\/)?(?:www\.)?youtu(?:\.be\/|be.com\/\S*(?:watch|embed)(?:(?:(?=\/[-a-zA-Z0-9_]{11,}(?!\S))\/)|(?:\S*v=|v\/)))([-a-zA-Z0-9_]{11,})|NO)--}}
                
            </div>   
        </div>

        <!-- Submit -->
        <div class="form-group">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-sm btn-danger">Guardar</button>
                <div class="ml-5">
                    <a class="btn btn-sm btn-warning " style="color:black;
                    text-decoration: none;" href="{{ route('admin.posts.index') }}">Regresar</a>
                </div>
            </div>
        </div>
        <!-- ./Submit -->
    </form>
@endif
