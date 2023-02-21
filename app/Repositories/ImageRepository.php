<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class ImageRepository implements Contract\ImageRepositoryContract
{

    /**
     * @throws \Exception
     */
    public function attach(Model $model, string $methodName, array $images = [], string $directory = null)
    {
        if (!method_exists($model, $methodName)) {
            throw new \Exception("Method {$methodName} doesn't exist in model {$model}");
        }

        if (!empty($images)) {

            foreach ($images as $image) {
                call_user_func([$model, $methodName])->create(['directory' => $directory, 'path' => $image]);
            }
        }
    }

    public function update(Model $model, string $methodName, array $images = null, string $directory = null)
    {
        if (!method_exists($model, $methodName)) {
            throw new \Exception("Method {$methodName} doesn't exist in model {$model}");
        }

        if (!empty($images)) {

            Image::where('imageable_id', $model->id)
                ->where('imageable_type', $model::class)
                ->delete();
            $images = collect($images);

            foreach ($images as $image) {
                call_user_func([$model, $methodName])->create(['directory' => $directory, 'path' => $image]);
            }
        }
    }
}
