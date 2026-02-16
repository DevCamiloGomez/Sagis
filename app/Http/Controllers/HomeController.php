<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Repositories\PostRepository;
use App\Repositories\PostImageRepository;
use App\Repositories\PostCategoryRepository;
use App\Http\Requests\Filters\EventFilterRequest;
use App\Http\Requests\Filters\VideoFilterRequest;
use App\Http\Requests\Filters\CourseFilterRequest;
use App\Http\Requests\Filters\NoticeFilterRequest;
use App\Http\Requests\Filters\GalleryFilterRequest;
use App\Models\CarouselImage;

class HomeController extends Controller
{
    /** @var PostCategoryRepository */
    protected $postCategoryRepository;

    /** @var PostRepository */
    protected $postRepository;

    protected $postImageRepository;

    /** @var string */
    protected $viewLocation = 'pages.';

    public function __construct(
        PostCategoryRepository $postCategoryRepository,
        PostRepository $postRepository,
        PostImageRepository $postImageRepository
    ) {
        $this->middleware('auth')->except(['home', 'notices', 'showNotice', 'courses', 'showCourse', 'events', 'showEvent', 'gallerys', 'showGallery', 'videos', 'showVideo', 'bolsaEmpleo']);
        
        $this->postCategoryRepository = $postCategoryRepository;
        $this->postRepository = $postRepository;
        $this->postImageRepository =  $postImageRepository;
    }

    public function home()
    {
        try {
            // Reconectar si la conexión se perdió
            DB::reconnect();
            
            $mainCarousel = CarouselImage::getMainCarousel();
            $sectionCarousel = CarouselImage::getSectionCarousel();
        } catch (\Exception $e) {
            // Si falla, usar colecciones vacías
            Log::error('Error cargando carousel: ' . $e->getMessage());
            $mainCarousel = collect();
            $sectionCarousel = collect();
        }
        
        // Obtener las categorías
        $postNotice = $this->postCategoryRepository->getByAttribute('name', 'Noticias');
        $postCourse = $this->postCategoryRepository->getByAttribute('name', 'Cursos');
        $postVideo = $this->postCategoryRepository->getByAttribute('name', 'Vídeos');
        $postEvent = $this->postCategoryRepository->getByAttribute('name', 'Eventos');

        // Obtener las últimas publicaciones de cada categoría
        $lastNotice = $postNotice ? $this->postRepository->getLatestPostByCategory($postNotice->id) : null;
        $lastCourse = $postCourse ? $this->postRepository->getLatestPostByCategory($postCourse->id) : null;
        $lastVideo = $postVideo ? $this->postRepository->getLatestPostByCategory($postVideo->id) : null;
        $lastEvent = $postEvent ? $this->postRepository->getLatestPostByCategory($postEvent->id) : null;
        
        return view('pages.home', compact(
            'mainCarousel',
            'sectionCarousel',
            'lastNotice',
            'lastCourse',
            'lastVideo',
            'lastEvent'
        ));
    }

    public function notices(NoticeFilterRequest $request)
    {
        $postNotice = $this->postCategoryRepository->getByAttribute('name', 'Noticias');

        try {
            $params = $this->postRepository->transformParameters($request->all());
            $query = $this->postRepository->search($params, $postNotice->id);
            $total = $query->count();

            $items = $this->postRepository->customPagination($query, $params, $request->get('page'), $total);

            return view($this->viewLocation . 'notices.index', compact('items'))
                ->nest('filters', $this->viewLocation . 'notices.filters', compact('params', 'total'))
                ->nest('table', $this->viewLocation . 'notices.table', compact('items'));
        } catch (Exception $th) {
            throw $th;
        }
    }

    /**
     * @param int $id
     */
    public function showNotice($id)
    {
        try {
            $item = $this->postRepository->getById($id);

            $imageHeader = $item->imageHeader();
            $images = $item->images()->whereNotIn('id', [$imageHeader->id])->get();

            if($item->getCountVideo()>0){
                $videoHeader = $item->videoHeader();
                //$videos =$item->videos()->whereNotIn('id', [$videoHeader->id])->get();
                return view($this->viewLocation . 'notices.show', compact('item', 'imageHeader', 'images', 'videoHeader'));
            }else{

                return view($this->viewLocation . 'notices.show', compact('item', 'imageHeader', 'images'));
            }
           

            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function courses(CourseFilterRequest $request)
    {
        $postCourse = $this->postCategoryRepository->getByAttribute('name', 'Cursos');

        try {
            $params = $this->postRepository->transformParameters($request->all());
            $query = $this->postRepository->search($params, $postCourse->id);
            $total = $query->count();

            $items = $this->postRepository->customPagination($query, $params, $request->get('page'), $total);

            return view($this->viewLocation . 'courses.index', compact('items'))
                ->nest('filters', $this->viewLocation . 'courses.filters', compact('params', 'total'))
                ->nest('table', $this->viewLocation . 'courses.table', compact('items'));
        } catch (Exception $th) {
            throw $th;
        }
    }

    /**
     * @param int $id
     */
    public function showCourse($id)
    {
        try {
            $item = $this->postRepository->getById($id);

            $imageHeader = $item->imageHeader();
            $images = $item->images()->whereNotIn('id', [$imageHeader->id])->get();


            if($item->getCountVideo()>0){
                $videoHeader = $item->videoHeader();
                return view($this->viewLocation . 'courses.show', compact('item', 'imageHeader', 'images', 'videoHeader'));
            }else{
                return view($this->viewLocation . 'courses.show', compact('item', 'imageHeader', 'images'));
            }
          
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function events(EventFilterRequest $request)
    {
        $postEvent = $this->postCategoryRepository->getByAttribute('name', 'Eventos');

        try {
            $params = $this->postRepository->transformParameters($request->all());
            $query = $this->postRepository->search($params, $postEvent->id);
            $total = $query->count();

            $items = $this->postRepository->customPagination($query, $params, $request->get('page'), $total);

            return view($this->viewLocation . 'events.index', compact('items'))
                ->nest('filters', $this->viewLocation . 'events.filters', compact('params', 'total'))
                ->nest('table', $this->viewLocation . 'events.table', compact('items'));
        } catch (Exception $th) {
            throw $th;
        }
    }

    /**
     * @param int $id
     */
    public function showEvent($id)
    {
        try {
            $item = $this->postRepository->getById($id);

            $imageHeader = $item->imageHeader();
            $images = $item->images()->whereNotIn('id', [$imageHeader->id])->get();


            
            if($item->getCountVideo()>0){
                $videoHeader = $item->videoHeader();
                return view($this->viewLocation . 'events.show', compact('item', 'imageHeader', 'images', 'videoHeader'));
            }else{
                return view($this->viewLocation . 'events.show', compact('item', 'imageHeader', 'images'));
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function gallerys(GalleryFilterRequest $request)
    {
        $postGallery = $this->postCategoryRepository->getByAttribute('name', 'Galería');

        try {
            $params = $this->postRepository->transformParameters($request->all());
            $query = $this->postRepository->search($params, $postGallery->id);
            $total = $query->count();

            $items = $this->postRepository->customPagination($query, $params, $request->get('page'), $total);

            return view($this->viewLocation . 'gallerys.index', compact('items'))
                ->nest('filters', $this->viewLocation . 'gallerys.filters', compact('params', 'total'))
                ->nest('table', $this->viewLocation . 'gallerys.table', compact('items'));
        } catch (Exception $th) {
            throw $th;
        }
    }

    /**
     * @param int $id
     */
    public function showGallery($id)
    {
        try {
            $item = $this->postRepository->getById($id);

            $imageHeader = $item->imageHeader();
            $images = $item->images()->whereNotIn('id', [$imageHeader->id])->get();

            return view($this->viewLocation . 'gallerys.show', compact('item', 'imageHeader', 'images'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function videos(VideoFilterRequest $request)
    {
        $postGallery = $this->postCategoryRepository->getByAttribute('name', 'Vídeos');

        try {
            $params = $this->postRepository->transformParameters($request->all());
            
            if (!$postGallery) {
                $items = collect();
                $total = 0;
            } else {
                $query = $this->postRepository->search($params, $postGallery->id);
                $total = $query->count();
                $items = $this->postRepository->customPagination($query, $params, $request->get('page'), $total);
            }

            return view($this->viewLocation . 'videos.index', compact('items'))
                ->nest('filters', $this->viewLocation . 'videos.filters', compact('params', 'total'))
                ->nest('table', $this->viewLocation . 'videos.table', compact('items'));
        } catch (Exception $th) {
            throw $th;
        }
    }


        /**
     * @param int $id
     */
    public function showVideo($id)
    {
        try {
            $item = $this->postRepository->getById($id);
                    
            if($item->getCountVideo()>0 && !is_null($item->videoHeader())){
                $videoHeader = $item->videoHeader();
                return view($this->viewLocation . 'videos.show', compact('item', 'videoHeader'));
            }else{
                return view($this->viewLocation . 'videos.show', compact('item'));
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Visualización embebida de la bolsa de empleo UFPS
     */
    public function bolsaEmpleo()
    {
        return view($this->viewLocation . 'bolsa-empleo');
    }

}
