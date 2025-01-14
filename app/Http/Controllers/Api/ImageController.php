<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    public function __construct(private ImageService $imageService)
    {
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB Max
            'type' => 'required|in:product,post',
            'id' => 'required|uuid|exists:' . $request->type . 's,id'
        ]);

        try {
            DB::beginTransaction();

            $image = $this->imageService->uploadImage($request->file('image'));
            
            // Attach image to model
            $modelClass = 'App\\Models\\' . ucfirst($request->type);
            $model = $modelClass::findOrFail($request->id);
            $model->images()->save($image);

            DB::commit();

            return new ImageResource($image);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to upload image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Image $image)
    {
        try {
            $this->imageService->deleteImage($image);
            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 