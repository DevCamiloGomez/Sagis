<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\S3UploadService;

class CarouselController extends Controller
{
    protected $s3UploadService;

    public function __construct(S3UploadService $s3UploadService)
    {
        $this->middleware('auth:admin');
        $this->s3UploadService = $s3UploadService;
    }

    public function index()
    {
        $images = CarouselImage::orderBy('order')->get();
        return view('admin.pages.carousel.index', compact('images'));
    }

    public function create()
    {
        return view('admin.pages.carousel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'type' => 'required|string|in:main,section'
        ]);

        try {
            $uploadedFile = $this->s3UploadService->uploadFile(
                $request->file('image'),
                'carousel',
                'public'
            );

            CarouselImage::create([
                'title' => $request->title,
                'asset_url' => $uploadedFile['url'],
                'asset' => $uploadedFile['filename'],
                'order' => $request->order ?? 0,
                'is_active' => $request->is_active ?? true,
                'type' => $request->type
            ]);

            return redirect()->route('admin.carousel.index')
                ->with('alert', [
                    'title' => '¡Éxito!',
                    'icon' => 'success',
                    'message' => 'Imagen agregada correctamente al carrusel.'
                ]);
        } catch (\Exception $e) {
            return back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'Error al subir la imagen: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(CarouselImage $carousel)
    {
        return view('admin.pages.carousel.edit', compact('carousel'));
    }

    public function update(Request $request, CarouselImage $carousel)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'type' => 'required|string|in:main,section'
        ]);

        try {
            $data = [
                'title' => $request->title,
                'order' => $request->order ?? $carousel->order,
                'is_active' => $request->has('is_active'),
                'type' => $request->type
            ];

            if ($request->hasFile('image')) {
                // Eliminar imagen anterior
                if ($carousel->asset) {
                    Storage::disk('s3')->delete('carousel/' . $carousel->asset);
                }

                // Subir nueva imagen
                $uploadedFile = $this->s3UploadService->uploadFile(
                    $request->file('image'),
                    'carousel',
                    'public'
                );

                $data['asset_url'] = $uploadedFile['url'];
                $data['asset'] = $uploadedFile['filename'];
            }

            $carousel->update($data);

            return redirect()->route('admin.carousel.index')
                ->with('alert', [
                    'title' => '¡Éxito!',
                    'icon' => 'success',
                    'message' => 'Imagen actualizada correctamente.'
                ]);
        } catch (\Exception $e) {
            return back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'Error al actualizar la imagen: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(CarouselImage $carousel)
    {
        try {
            if ($carousel->asset) {
                Storage::disk('s3')->delete('carousel/' . $carousel->asset);
            }
            
            $carousel->delete();

            return back()->with('alert', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'message' => 'Imagen eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            return back()->with('alert', [
                'title' => '¡Error!',
                'icon' => 'error',
                'message' => 'Error al eliminar la imagen: ' . $e->getMessage()
            ]);
        }
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:carousel_images,id',
            'orders.*.order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($request->orders as $item) {
                CarouselImage::where('id', $item['id'])->update(['order' => $item['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Orden actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el orden: ' . $e->getMessage()
            ], 500);
        }
    }
} 