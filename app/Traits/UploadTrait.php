<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\ImageManager;
use Laravolt\Avatar\Facade as Avatar;

trait UploadTrait
{
    /**
     * Get the avatar or image dynamically.
     */
    public function getAvatarOrImageAttribute()
    {
        $table = $this->getTable();
        $attribute = Schema::hasColumn($table, 'avatar') ? 'avatar' : 'image';

        if (!empty($this->attributes[$attribute])) {
            return $this->getImage($this->attributes[$attribute], static::filePath);
        } else {
            $fileName = $this->attributes['id'].'.png';
            $this->saveAvatar($this->name, static::filePath, $fileName);
            return $this->getImage($fileName, static::filePath);
        }
    }

    /**
     * Retrieve an image URL.
     */
    public static function getImage($name, $directory)
    {
        return asset("storage/images/$directory/".$name);
    }

    /**
     * Generate and save an avatar.
     */
    protected function saveAvatar($name, $path, $fileName)
    {
        if (!File::isDirectory(storage_path("app/public/{$path}"))) {
            File::makeDirectory(storage_path("app/public/{$path}"), 0777, true, true);
        }

        $avatarPath = storage_path("app/public/{$path}/{$fileName}");
        if (!File::exists($avatarPath)) {
            Avatar::create($name)->save($avatarPath);
        }

        $this->attributes['avatar'] = $fileName;
    }

    /**
     * Set the avatar or image dynamically.
     */
    public function setAvatarOrImageAttribute($value)
    {
        $table = $this->getTable();
        $attribute = Schema::hasColumn($table, 'avatar') ? 'avatar' : 'image';

        if (!empty($value)) {
            if (!empty($this->attributes[$attribute])) {
                $this->deleteFile($this->attributes[$attribute], static::filePath);
            }

            $this->attributes[$attribute] = $this->uploadAllTypes($value, static::filePath);
        }
    }

    /**
     * Delete a file.
     */
    public function deleteFile($file_name, $directory = 'unknown'): void
    {
        if ($file_name && $file_name !== 'default.png' && file_exists("storage/images/$directory/$file_name")) {
            unlink("storage/images/$directory/$file_name");
        }
    }

    /**
     * Upload different file types (images, documents, etc.).
     */
    public function uploadAllTypes($file, $directory, $width = null, $height = null)
    {
        if (!File::isDirectory('storage/images/'.$directory)) {
            File::makeDirectory('storage/images/'.$directory, 0777, true, true);
        }

        $fileMimeType = $file->getClientMimeType();
        $isImage = explode('/', $fileMimeType)[0] === 'image';

        if ($isImage) {
            $allowedImagesMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];

            if (!in_array($fileMimeType, $allowedImagesMimeTypes)) {
                return 'default.png';
            }

            return $this->uploadImage($file, $directory, $width, $height);
        }

        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/octet-stream'
        ];

        if (!in_array($fileMimeType, $allowedMimeTypes)) {
            return 'default.webp';
        }

        return $this->uploadFile($file, $directory);
    }

    /**
     * Upload an image with optional resizing.
     */
    public function uploadImage($file, $directory, $width = null, $height = null)
    {
        $manager = ImageManager::gd(autoOrientation: false);

        // Read and orient the image
        $img = $manager->read($file)->orient();

        $thumbsPath = 'storage/images/'.$directory;
        if (!File::exists($thumbsPath)) {
            File::makeDirectory($thumbsPath, 0777, true, true);
        }

        $name = time().'_'.rand(1111, 9999).'.webp';

        if ($width && $height) {
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $img->save($thumbsPath.'/'.$name, 90, 'webp');
        return (string) $name;
    }

    /**
     * Upload a file.
     */
    public function uploadFile($file, $directory)
    {
        $filename = time().rand(1000000, 9999999).'.'.$file->getClientOriginalExtension();
        $path = 'images/'.$directory;
        $file->storeAs($path, $filename);
        return $filename;
    }

    /**
     * Get a formatted created_at timestamp.
     */
    public function getCreatedAtFormatAttribute()
    {
        return Carbon::parse($this->created_at)->translatedFormat('j F Y g:i A');
    }

    /**
     * Get the default image.
     */
    public function defaultImage($directory)
    {
        return asset("/storage/images/users/default.webp");
    }

    /**
     * Convert an attribute to JSON with UTF-8 encoding.
     */
    protected function asJson($value, $flags = 0)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | $flags);
    }

    /**
     * Delete files related to a model.
     */
    protected function deleteFiles($model)
    {
        foreach (static::FILES as $key => $file) {
            $directory = array_key_exists($key, static::FILES) && !is_int($key)
                ? static::FILES[$key]
                : static::FILEPATH;

            foreach ([$file, $key] as $attribute) {
                if (isset($model->attributes[$attribute])) {
                    $model->deleteFile($model->attributes[$attribute], $directory);
                }
            }
        }
    }
}
