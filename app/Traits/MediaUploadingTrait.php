<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait MediaUploadingTrait
{
    public function storeMedia(Request $request)
    {
        // Validates file size
        if (request()->has('size')) {
            $this->validate(request(), [
                'file' => 'max:'.request()->input('size') * 1024,
            ]);
        }

        // If width or height is preset - we are validating it as an image
        if (request()->has('width') || request()->has('height')) {
            $this->validate(request(), [
                'file' => sprintf(
                    'image|dimensions:max_width=%s,max_height=%s',
                    request()->input('width', 100000),
                    request()->input('height', 100000)
                ),
            ]);
        }
        // dd($request->all());

        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }

        $file = $request->file('file');

        $name = uniqid().'_'.trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function storeFiles(Request $request)
    {
        $list = [];
        // dd(request()->all());
        // Validates file size
        if (request()->has('size')) {
            $this->validate(request(), [
                'file' => 'max:'.request()->input('size') * 1024,
            ]);
        }

        // If width or height is preset - we are validating it as an image
        if (request()->has('width') || request()->has('height')) {
            $this->validate(request(), [
                'file.*' => sprintf(
                    'image|dimensions:max_width=%s,max_height=%s',
                    request()->input('width', 100000),
                    request()->input('height', 100000)
                ),
            ]);
        }
        // dd($request->all());

        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }
        $files = $request->file;
        foreach ($files as $file) {
            $name = uniqid().'_'.trim($file->getClientOriginalName());

            $file->move($path, $name);
            $list[] = [
                    'name' => $name,
                    'original_name' => $file->getClientOriginalName(),
                    ];
        }

        return response()->json($list);
    }
}
