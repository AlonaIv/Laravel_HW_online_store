<?php

namespace App\Models;

use App\Services\FileStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function setPathAttribute($image)
    {
        $this->attributes['path'] = FileStorageService::upload($image, $this->attributes['directory'] ?? null);
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::exists($this->attributes['path'])
                ? Storage::url($this->attributes['path'])
                : $this->attributes['path']
        );
    }
}
