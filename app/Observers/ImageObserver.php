<?php

namespace App\Observers;

use App\Models\Image;
use App\Services\FileStorageService;

class ImageObserver
{
    public function deleted(Image $image)
    {
        FileStorageService::remove($image->path);
    }
}
