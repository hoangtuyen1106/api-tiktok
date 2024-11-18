<?php

namespace App\Services;

use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class FileService
{
    public function updateImage($model, $request)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('image'));
        // $image = Image::make($request->file('image'));
        // $image = Image::read($request->file('image'));

        if (!empty($model->image)) {
            $currentImage = public_path() . $model->image;

            if (file_exists($currentImage) && $currentImage != public_path() . '/user-placeholder.png') {
                unlink($currentImage);
            }
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        $top = !empty($request->top) ? $request->top : 0;
        $left = !empty($request->left) ? $request->left : 0;
        $image->crop(
            $request->width,
            $request->height,
            $left,
            $top
        );

        $name = time() . '.' . $extension;
        $image->save(public_path() . '/files/' . $name);
        $model->image = '/files/' . $name;

        return $model;
    }

    public function addVideo($model, $request)
    {
        $video = $request->file('video');
        $extension = $video->getClientOriginalExtension();
        $name = time() . '.' . $extension;
        $video->move(public_path() . '/files/', $name);
        $model->video = '/files/' . $name;

        return $model;
    }
}