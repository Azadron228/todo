<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function store(ImageRequest $request)
    {
        if ($request->hasFile('upload')) {
            $taskData = Task::findOrFail($request->id);

            $this->deletePreviousImages($taskData);

            $image = $request->file('upload');
            $imageName = time() . $image->getClientOriginalName();

            $image->storeAs('public/uploaded', $imageName);
            $image->storeAs('public/uploaded/thumbnail', $imageName);

            $imagePath = 'uploaded/' . $imageName;
            $thumbPath = 'uploaded/thumbnail/' . $imageName;
            $thumbnailPath = public_path('storage/' . $thumbPath);

            $thumbnail = Image::make($thumbnailPath)->resize(150, 150);
            $thumbnail->save($thumbnailPath);

            $taskData->update([
                'img'   => $imagePath,
                'thumb' => $thumbPath
            ]);
        }

        return redirect()->back();
    }

    public function removeImage($id)
    {
        $taskItem = Task::findOrFail($id);

        $this->deletePreviousImages($taskItem);

        $taskItem->update([
            'img'   => "",
            'thumb' => ""
        ]);

        return redirect()->back();
    }

    private function deletePreviousImages(Task $taskItem)
    {
        if ($taskItem->img !== '') {
            $imagePaths = ['public/' . $taskItem->img, 'public/' . $taskItem->thumb];

            if (Storage::exists($imagePaths[0])) {
                Storage::delete($imagePaths);
            }
        }
    }
}
