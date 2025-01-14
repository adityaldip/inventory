<?php

namespace App\Services;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function uploadImage(UploadedFile $file, string $folder = 'images'): Image
    {
        try {
            // Generate unique filename
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Store file in S3
            $path = Storage::disk('s3')->putFileAs(
                $folder,
                $file,
                $filename,
                'public'
            );

            // Get the full URL
            $url = Storage::disk('s3')->url($path);

            // Create image record
            return Image::create([
                'filename' => $filename,
                'full_path' => $url,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'created_by_id' => auth()->id() ?? User::first()->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            throw new \Exception('Failed to upload image');
        }
    }

    public function deleteImage(Image $image): bool
    {
        try {
            // Delete from S3
            Storage::disk('s3')->delete('images/' . $image->filename);
            
            // Delete from database
            return $image->delete();
        } catch (\Exception $e) {
            \Log::error('Image deletion failed: ' . $e->getMessage());
            throw new \Exception('Failed to delete image');
        }
    }
} 