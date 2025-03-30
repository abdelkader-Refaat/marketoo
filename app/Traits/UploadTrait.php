<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;

trait UploadTrait
{
    public static function getImage($name, $directory)
    {
        return asset("storage/images/$directory/".$name);
    }

    /**
     * Upload different file types (images, documents, etc.)
     */
    public function uploadAllTypes($file, $directory, $width = null, $height = null)
    {
        if (!File::isDirectory(storage_path("app/public/{$directory}"))) {
            File::makeDirectory(storage_path("app/public/{$directory}"), 0755, true);
        }
        $fileMimeType = $file->getClientMimeType();
        if (str_starts_with($fileMimeType, 'image')) {
            return $this->uploadImage($file, $directory, $width, $height);
        }
        return $this->uploadFile($file, $directory);
    }

    /**
     * Upload an image with optional resizing
     */
    public function uploadImage($file, $directory, $width = null, $height = null): string
    {
        $manager = ImageManager::gd(autoOrientation: false);
        $image = $manager->read($file)->orient();
        $filename = time().'_'.bin2hex(random_bytes(4)).'.webp';
        $path = storage_path("app/public/images/{$directory}/{$filename}");
        if ($width || $height) {
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $image->save($path, 90, 'webp');
        return $filename;
    }

    public function uploadFile($file, $directory): string
    {
        $filename = time().rand(1000000, 9999999).'.'.$file->getClientOriginalExtension();
        $path = 'uploads/'.$directory;
        $file->storeAs($path, $filename);
        return $filename;
    }

    /**
     * Delete a file
     */
    public function deleteFile(string $filename, string $directory): void
    {
        $path = storage_path("app/public/{$directory}/{$filename}");
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
