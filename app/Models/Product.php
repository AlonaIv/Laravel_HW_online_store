<?php

namespace App\Models;

use App\Services\FileStorageService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;
use willvincent\Rateable\Rateable;

class Product extends Model
{
    use HasFactory, Sortable, Rateable;

    public mixed $quantty;

    protected $fillable = [
        'title',
        'description',
        'price',
        'discount',
        'thumbnail',
        'quantity',
        'SKU',
    ];

    protected $sortableAs = ['followers_count'];

    public $sortable = [
        'title',
        'id',
        'quantity',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'wish_list',
            'product_id',
            'user_id'
        );
    }

    public function setThumbnailAttribute($image)
    {
        if (!empty($this->attributes['thumbnail'])) {
            FileStorageService::remove($this->attributes['thumbnail']);
        }

        $this->attributes['thumbnail'] = FileStorageService::upload(
            $image,
            strtolower(str_replace(" ", "_", $this->attributes['title']))
        );
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::exists($this->attributes['thumbnail'])
                ? Storage::url($this->attributes['thumbnail'])
                : $this->attributes['thumbnail']
        );
    }

    public function slug(): Attribute
    {
        return Attribute::make(
            get: fn() => strtolower(str_replace(" ", "_", $this->attributes['title']))
        );
    }

    public function endPrice(): Attribute
    {
        return Attribute::get(function () {
            $price = is_null($this->attributes['discount']) || $this->attributes['discount'] === 0
                ? $this->attributes['price']
                : ($this->attributes['price'] - ($this->attributes['price'] * ($this->attributes['discount'] / 100)));

            return $price <= 0 ? 1 : round($price, 2);
        });
    }

    public function price(): Attribute
    {
        return Attribute::get(fn() => round($this->attributes['price'], 2));
    }

    public function available(): Attribute
    {
        return Attribute::get(fn() => $this->attributes['quantity'] > 0);
    }

    public function userRate(): Attribute
    {
        return Attribute::get(function () {
            return $this->ratings()->where([
                ['rateable_id', $this->id],
                ['user_id', auth()->id()]
            ])?->first();
        });
    }
}
