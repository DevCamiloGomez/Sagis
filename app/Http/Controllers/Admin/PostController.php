<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Repositories\PostRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\Posts\StoreRequest;
use App\Repositories\PostImageRepository;
use App\Repositories\PostVideoRepository;
use App\Http\Requests\Posts\UpdateRequest;
use App\Repositories\PostCategoryRepository;
use App\Http\Requests\Posts\StoreRequestImage;
use App\Http\Requests\Posts\UpdateRequestImage;
use App\Services\S3UploadService;

/**
 * Controlador PostController - Gestión de publicaciones y noticias
 * 
 * Este controlador maneja todas las operaciones relacionadas con las publicaciones
 * y noticias del sistema, incluyendo contenido multimedia.
 * 
 * Características principales:
 * - Creación y edición de publicaciones
 * - Gestión de categorías
 * - Manejo de imágenes
 * - Manejo de videos
 * - Gestión de documentos adjuntos
 * 
 * Middleware:
 * - auth:admin : Requiere autenticación de administrador
 * 
 * Repositorios utilizados:
 * - PostRepository: Gestión de publicaciones
 * - PostCategoryRepository: Categorías de publicaciones
 * - PostImageRepository: Imágenes de publicaciones
 * - PostVideoRepository: Videos de publicaciones
 * 
 * Servicios:
 * - S3UploadService: Servicio de carga de archivos multimedia
 * 
 * Documentado por: Camilo Gomez
 */
class PostController extends Controller
{
    /** @var PostRepository */
    protected $postRepository;

    /** @var PostCategoryRepository */
    protected $postCategoryRepository;

    /** @var PostImageRepository */
    protected $postImageRepository;

    /** @var PostVideoRepository */
    protected $postVideoRepository;

    /** @var S3UploadService */
    protected $s3UploadService;

    public function __construct(
        PostRepository $postRepository,
        PostCategoryRepository $postCategoryRepository,
        PostImageRepository $postImageRepository,
        PostVideoRepository $postVideoRepository,
        S3UploadService $s3UploadService
    ) {
        $this->middleware('auth:admin');

        $this->postRepository = $postRepository;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->postImageRepository =  $postImageRepository;
        $this->postVideoRepository = $postVideoRepository;
        $this->s3UploadService = $s3UploadService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $params = $this->postRepository->transformParameters($request->all());
            $query = $this->postRepository->search($params, null, 'postCategory');

            $total = $query->count();
            $items = $this->postRepository->customPagination($query, $params, $request->get('page'), $total);


            $postCategories = $this->postCategoryRepository->all();

            return view('admin.pages.posts.index', compact('items'))
                ->nest('filters', 'admin.pages.posts.filters', compact('params', 'total'))
                ->nest('table', 'admin.pages.posts.table', compact('items', 'postCategories'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $postCategories = $this->postCategoryRepository->all();

            return view('admin.pages.posts.create', compact('postCategories'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $paramsRequest = $request->all();

             //dd($paramsRequest);

            /** Creating Post */
            $postParams = $request->except(['image', '_token', 'video', 'image']);

           // dd(is_null($paramsRequest['video']));


            $this->postRepository->create($postParams);

            /** Searching created Post */
            $post = $this->postRepository->getByAttribute('title', $request->title);

            /* dd($post); */

            $files = $request->file('image');
            $videoParams = $request->only(['video']);


            if ($paramsRequest['post_category_id'] == "5") {

                if(!is_null($videoParams['video'])){

                    $video = substr($videoParams['video'], -11);
                    $videoParams['post_id'] = $post->id;
                    $videoParams['asset_url'] = $video;
                    $videoParams['is_header'] = 1;
                    unset($videoParams['video']);

                    $this->postVideoRepository->create($videoParams);
                }


            }else if($paramsRequest['post_category_id'] == "4"){

                $fileParams = array();
            if (!($request->file('image') == null)) {

                foreach ($files as $key => $file) {
                    //dd($key);
                    /** Saving Photo */
                    $fileParams[$key]  = $this->saveImageMultiple($file, $key);
                }
            }

            // dd($fileParams);

            if (!($request->file('image') == null)) {

                foreach ($fileParams as $key => $fileParam) {
                    $postImgParams['post_id'] = $post->id;
                    ($key == 0) ?  $postImgParams['is_header'] = 1 :   $postImgParams['is_header'] = 0;

                    $postImg = array_merge($postImgParams,  $fileParam);

                    $this->postImageRepository->create($postImg);
                }
            }

            }else{

                if(!is_null($paramsRequest['video'])){
                    $video = substr($videoParams['video'], -11);
                    $videoParams['post_id'] = $post->id;
                    $videoParams['asset_url'] = $video;
                    $videoParams['is_header'] = 1;
                    unset($videoParams['video']);

                    $this->postVideoRepository->create($videoParams);
                }


                $fileParams = array();
                if (!($request->file('image') == null)) {
    
                    foreach ($files as $key => $file) {
                        //dd($key);
                        /** Saving Photo */
                        $fileParams[$key]  = $this->saveImageMultiple($file, $key);
                    }
                }
    
                // dd($fileParams);
    
                if (!($request->file('image') == null)) {
    
                    foreach ($fileParams as $key => $fileParam) {
                        $postImgParams['post_id'] = $post->id;
                        ($key == 0) ?  $postImgParams['is_header'] = 1 :   $postImgParams['is_header'] = 0;
    
                        $postImg = array_merge($postImgParams,  $fileParam);
    
                        $this->postImageRepository->create($postImg);
                    }
                }



            }

            //  dd($files);

            
            /*  else{
                $postImgParams['post_id'] = $post->id;
                $postImgParams['asset_url'] = 'https://media.tenor.com/3Yat5iUF8fgAAAAM/huh-quoi.gif';
                $postImgParams['asset'] = null;
                $postImgParams['is_header'] = 1;
                $this->postImageRepository->create($postImgParams);
            } */



            DB::commit();

            return redirect()->route('admin.posts.index')->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha registrado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            dd($th);
            DB::rollBack();

            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido registrar correctamente.', 'icon' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $item = $this->postRepository->getById($id);

            $postCategories = $this->postCategoryRepository->all();

            //dd($item->getCountimage());

            if ($item->postCategory->name != "Vídeos" && $item->getCountimage() >= 1) {
                $imageHeader = $item->imageHeader();
                $images = $item->images()->whereNotIn('id', [$imageHeader->id])->get();
                  //  dd();
                if ($item->getCountVideo() >= 1 && !(is_null($item->videoHeader()))) {
                    $videoHeader = $item->videoHeader();
                    $videos = $item->videos()->whereNotIn('id', [$videoHeader->id])->get();
                }
            } else if($item->postCategory->name == "Vídeos" &&  $item->getCountimage() == 0 ) {
                $videoHeader = $item->videoHeader();
                $videos = $item->videos()->whereNotIn('id', [$videoHeader->id])->get();
            }


            if ($item->postCategory->name != "Vídeos" && $item->getCountimage() >= 1) {


                if ($item->getCountVideo() >= 1 && !(is_null($item->videoHeader()))) {
                    return view('admin.pages.posts.edit', compact('item', 'postCategories', 'imageHeader', 'images', 'videoHeader', 'videos'));
                } else {
                    return view('admin.pages.posts.edit', compact('item', 'postCategories', 'imageHeader', 'images'));
                }
            } else {

                return view('admin.pages.posts.edit', compact('item', 'postCategories', 'videoHeader', 'videos'));
            }


            //return redirect()->route('admin.posts,edit', compact('item', 'postCategories', 'imageHeader', 'images'))->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha actualizado correctamente.']);
        } catch (\Exception $th) {
            dd($th);
            return redirect()->route('admin.posts.index')->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'No hemos podido encontrar esta publicación.']);
        }
    }


    public function images($id){
        try {
            $item = $this->postRepository->getById($id);

            $postCategories = $this->postCategoryRepository->all();

            //dd($item->getCountimage());

            
            if ($item->postCategory->name != "Vídeos" && $item->getCountimage() >= 1) {


                if ($item->getCountimage() >= 1 && !(is_null($item->imageHeader()))) {
                     $imageHeader = $item->imageHeader();
                $images = $item->images()->whereNotIn('id', [$imageHeader->id])->get();
                    return view('admin.pages.posts.images', compact('item', 'postCategories', 'imageHeader', 'images'));
                } else {
                    return view('admin.pages.posts.images', compact('item', 'postCategories'));
                }
            } 

            //return redirect()->route('admin.posts,edit', compact('item', 'postCategories', 'imageHeader', 'images'))->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha actualizado correctamente.']);
        } catch (\Exception $th) {
            dd($th);
            return redirect()->route('admin.posts.index')->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'No hemos podido encontrar esta publicación.']);
        }


    }


    public function edit_image($post_id, $image_id){
       

        try {
            $item = $this->postRepository->getById($post_id);
            $img = $this->postImageRepository->getById($image_id);

  /*           $postCategories = $this->postCategoryRepository->all(); */

            //dd($item->getCountimage());

        
              return view('admin.pages.posts.edit_image', compact('item', 'img'));
                

            //return redirect()->route('admin.posts,edit', compact('item', 'postCategories', 'imageHeader', 'images'))->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha actualizado correctamente.']);
        } catch (\Exception $th) {
            dd($th);
            return redirect()->route('admin.posts.images',$item->id)->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'No hemos podido encontrar esta publicación.']);
        }



    }


    public function update_image(UpdateRequestImage $request, $post_id, $image_id){
        try {
            $post = $this->postRepository->getById($post_id);
            $img = $this->postImageRepository->getById($image_id);

            $paramsRequest =  $request->all();
        

            if (!($request->file('image') == null)) {
                /** Saving Photo */
                $fileParams = $this->saveImage($paramsRequest['image']);
            }

           
            $postImgParams['post_id'] = $post->id;
            $postImgParams['is_header'] = 0;

            $postImg = array_merge($postImgParams,  $fileParams);
        


            $this->postImageRepository->update($img, $postImg);
                

            return redirect()->route('admin.posts.images', $post->id)->with('alert', ['title' => '¡Éxito!', 'icon' => 'success', 'message' => 'Se ha actualizado correctamente.']);
        } catch (\Exception $th) {
            dd($th);
            return redirect()->route('admin.posts.images',$post->id)->with('alert', ['title' => '¡Error!', 'icon' => 'error', 'message' => 'No hemos podido encontrar esta publicación.']);
        }

    }


    public function create_image($post_id){
        try {
            $item = $this->postRepository->getById($post_id);

            return view('admin.pages.posts.create_image', compact('item'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function store_image(StoreRequestImage $request, $post_id){
        try {
            DB::beginTransaction();
            $post = $this->postRepository->getById($post_id);

            $paramsRequest = $request->all();

             //dd($paramsRequest);

            // $paramsRequest =  $request->all();

             if (!($request->file('image') == null)) {
                 /** Saving Photo */
                 $fileParams = $this->saveImage($paramsRequest['image']);
             }
 
            
             $postImgParams['post_id'] = $post->id;
             $postImgParams['is_header'] = 0;
 
             $postImg = array_merge($postImgParams,  $fileParams);
         
 
 
             $this->postImageRepository->create($postImg);



            DB::commit();

            return redirect()->route('admin.posts.images', $post->id)->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha registrado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            dd($th);
            DB::rollBack();

            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido registrar correctamente.', 'icon' => 'error']);
        }


    }




    public function destroy_image($post_id, $img_id)
    {
        try {
            $post = $this->postRepository->getById($post_id);
            $img = $this->postImageRepository->getById($img_id);
            //dd($img);


            DB::beginTransaction();

            $this->postImageRepository->delete($img);

            DB::commit();

            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha eliminado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();

            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido eliminar correctamente.', 'icon' => 'error']);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            // DB::beginTransaction();

            $params = $request->all();

           // $videoParams = $request->only(['video']);

            //dd($videoParams);

             /* dd($params); */
            if (!($request->file('imagen') == null)) {
                $paramsImagen = $request->only(['imagen']);
            }


            $post = $this->postRepository->getById($id);


            $this->postRepository->update($post, $request->all());


            if ($params['post_category_id'] == "5") {

                $imageHeader = $post->imageHeader();
                if (isset($imageHeader)) {
                    $images = $post->images()->whereNotIn('id', [$imageHeader->id])->get();

                    $this->postImageRepository->delete($imageHeader);
                    foreach ($images as $image) {

                        $this->postImageRepository->delete($image);
                    }
                }


                $videoParams2 = $request->only(['video']);
                $video = substr($videoParams2['video'], -11);
              //  dd(!is_null($videoParams2['video']));
                if($post->getCountVideo()>0 && !(is_null($post->videoHeader())) ) {
                    if(!is_null($videoParams2['video'])){

                        if(strtoupper($videoParams2['video'])!="NO"  ){
                            //dd("Probando NO");
                            $postVideo = $this->postVideoRepository->getByAttribute("post_id", $post->id);

                            //  dd($postVideo);
              
                              $videoParams2['post_id'] = $post->id;
                              $videoParams2['asset_url'] = $video;
                              $videoParams2['is_header'] = 1;
                              unset($videoParams2['video']);
                              //dd($postVideo);
                              $this->postVideoRepository->update($postVideo, $videoParams2);

                        }
                       
                    }
                 }else{
                    if(!is_null($videoParams2['video'])){

                        if(strtoupper($videoParams2['video'])!="NO"){
                           // dd("Probando NO");
                            $videoParams2['post_id'] = $post->id;
                        $videoParams2['asset_url'] = $video;
                        $videoParams2['is_header'] = 1;
                        unset($videoParams2['video']);
    
                        $this->postVideoRepository->create($videoParams2);
                        }
                        
                        

                    }
                             

                    }


           
            } else if ($params['post_category_id'] == "4") {

                if (!($request->file('imagen') == null)) {
                    /** Saving Photo */
                    $fileParams = $this->saveImage($paramsImagen['imagen']);
                }
                //dd($fileParams);

                $postImage = $this->postImageRepository->getByAttribute("post_id", $post->id);

               // dd(is_null($postImage));

                if(is_null($postImage)){
                    if (!($request->file('imagen') == null)) {
                        $postImgParams['post_id'] = $post->id;
                        $postImgParams['is_header'] = 1;
    
                        $postImg = array_merge($postImgParams,  $fileParams);
                        // dd($postImg);
    
    
                        $this->postImageRepository->create($postImg);
                    }

                }else{
                    if (!($request->file('imagen') == null)) {
                        $postImgParams['post_id'] = $post->id;
                        $postImgParams['is_header'] = 1;
    
                        $postImg = array_merge($postImgParams,  $fileParams);
                        // dd($postImg);
    
    
                        $this->postImageRepository->update($postImage, $postImg);
                    }
    

                }

                //dd( $postImage);

                
                if($post->getCountVideo()>0 && !(is_null($post->videoHeader()))) {
                    $videoHeader = $post->videoHeader();
                    $this->postVideoRepository->delete($videoHeader);
                }


            } else {


                if (!($request->file('imagen') == null)) {
                    /** Saving Photo */
                    $fileParams = $this->saveImage($paramsImagen['imagen']);
                }
                //dd($fileParams);

                $postImage = $this->postImageRepository->getByAttribute("post_id", $post->id);

                //dd( $postImage);

                if($post->getCountimage()>0&& !(is_null($post->imageHeader())) ) {
                    if (!($request->file('imagen') == null)) {
                        $postImgParams['post_id'] = $post->id;
                        $postImgParams['is_header'] = 1;
    
                        $postImg = array_merge($postImgParams,  $fileParams);
                        // dd($postImg);
    
    
                        $this->postImageRepository->update($postImage, $postImg);
                    }

                }else{

                    if (!($request->file('imagen') == null)) {
                        $postImgParams['post_id'] = $post->id;
                        $postImgParams['is_header'] = 1;
    
                        $postImg = array_merge($postImgParams,  $fileParams);
                        // dd($postImg);
    
    
                        $this->postImageRepository->create($postImg);
                    }

                }
               

                $videoParams = $request->only(['video']);
                $video = substr($videoParams['video'], -11);
                
                //dd(isset($videoParams));
                  //      dd($post->getCountVideo());

                 // dd($post->getCountVideo()>0&& !(is_null($post->videoHeader())));
                
                if($post->getCountVideo()>0&& !(is_null($post->videoHeader())) ) {

                    if(!is_null($videoParams['video'])){


                        if(strtoupper($videoParams['video'])=="NO"){
  
                                $videoHeader = $post->videoHeader();
                                  //dd($postVideo);
                                  $this->postVideoRepository->delete($videoHeader);
                            

                        }else{
                            $postVideo = $this->postVideoRepository->getByAttribute("post_id", $post->id);

                            //  dd($postVideo);
              
                              $videoParams['post_id'] = $post->id;
                              $videoParams['asset_url'] = $video;
                              $videoParams['is_header'] = 1;
                              unset($videoParams['video']);
                              //dd($postVideo);
                              $this->postVideoRepository->update($postVideo, $videoParams);

                        }
                      
                    }
                 }else{
                    if(!is_null($videoParams['video'])){
                        if(strtoupper($videoParams['video'])!="NO"){
                        $videoParams['post_id'] = $post->id;
                        $videoParams['asset_url'] = $video;
                        $videoParams['is_header'] = 1;
                        unset($videoParams['video']);
    
                        $this->postVideoRepository->create($videoParams);
                        }
                    }

                             

                    }

                
                }

                
            


            //  DB::commit();

            return redirect()->route('admin.posts.index')->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha actualizado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            dd($th);
            DB::rollBack();

            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido registrar correctamente.', 'icon' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $post = $this->postRepository->getById($id);

            DB::beginTransaction();

            $this->postRepository->delete($post);

            DB::commit();

            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se ha eliminado correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();

            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido eliminar correctamente.', 'icon' => 'error']);
        }
    }


    public function destroy_all(){
        try {

            $posts = $this->postRepository->all();


             DB::beginTransaction();

            foreach($posts as $post){
                 $this->postRepository->delete($post);
            }


           DB::commit();
            
           
            return back()->with('alert', ['title' => '¡Éxito!', 'message' => 'Se han eliminado todos los contenidos correctamente.', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
            return back()->with('alert', ['title' => '¡Error!', 'message' => 'No se ha podido eliminar correctamente todos los contenidos.', 'icon' => 'error']);
        }

    }


    /**
     * @param StoreRequest $request
     * @param array $params
     */
    private function saveImage($file)
    {
        $result = $this->s3UploadService->uploadFile($file, 'posts');
        
        return [
            'asset_url' => $result['url'],
            'asset' => $result['filename']
        ];
    }

    /**
     * @param StoreRequest $request
     * @param array $params
     */
    private function saveImageMultiple($file, $iter)
    {
        $result = $this->s3UploadService->uploadFile($file, 'posts');
        
        return [
            'asset_url' => $result['url'],
            'asset' => $result['filename']
        ];
    }
}
