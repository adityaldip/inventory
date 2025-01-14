<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(private ImageService $imageService)
    {
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // 2MB Max
            'type' => 'required|in:product,post',
            'id' => 'required|uuid|exists:' . $request->type . 's,id'
        ]);

        $image = $this->imageService->uploadImage($request->file('image'));
        
        // Attach image to the model
        $modelClass = 'App\\Models\\' . ucfirst($request->type);
        $model = $modelClass::find($request->id);
        $model->images()->save($image);

        return new ImageResource($image);
    }

    public function destroy(Image $image)
    {
        $this->imageService->deleteImage($image);
        return response()->noContent();
    }
} 