<?php

namespace App\Http\Controllers;

use App\Traits\MediaUploadingTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use MediaUploadingTrait;

    public function store_media($file)
    {
        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }
        $name = uniqid().'_'.trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function store_files($files)
    {
        $list = [];
        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }
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
