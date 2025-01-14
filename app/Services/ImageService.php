<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function uploadImage(UploadedFile $file, string $folder = 'images'): Image
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 's3');
        
        return Image::create([
            'filename' => $filename,
            'full_path' => Storage::disk('s3')->url($path),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize() / 1024, // Convert to KB
            'created_by_id' => auth()->id() ?? User::first()->id,
        ]);
    }

    public function deleteImage(Image $image): bool
    {
        Storage::disk('s3')->delete($image->filename);
        return $image->delete();
    }
} 