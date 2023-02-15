<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService implements Contracts\FileStorageServiceContract
{

    public static function upload(string|UploadedFile $file, string $additionalPath = ''): string
    {
        if (is_string($file)) {
            return str_replace('public/storage', '', $file);
        }

        $additionalPath = !empty($additionalPath) ? $additionalPath . '/' : $additionalPath;

        $filePath = "public/{$additionalPath}" . static::randName() . '.' . $file->getClientOriginalExtension();
        Storage::put($filePath, File::get($file));
        Storage::setVisibility($filePath, 'public'); //for Amazon

        return $filePath;
    }

    public static function remove(string $file)
    {
        Storage::delete($file);
    }

    public static function removeDirectory(string $dirName)
    {
        Storage::deleteDirectory($dirName);
    }

    protected static function randName(): string
    {
        return Str::random() . '_' . time();
    }
}
