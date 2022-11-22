<?php

namespace App\Http\Controllers;

use App\Traits\MediaUploadingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $user = auth()->user();
        $albums = $user->albums;

        return view('albums.index', compact('albums'));
    }

    public function create()
    {
        return view('albums.create');
    }

    public function show($slug)
    {
        $user = auth()->user();
        $album = $user->albums()->where('slug', $slug)->first();
        abort_if(!$album, 404);

        return view('albums.show', compact('album'));
    }

    public function edit($slug)
    {
        $user = auth()->user();
        $album = $user->albums()->where('slug', $slug)->first();
        abort_if(!$album, 404);

        return view('albums.edit', compact('album'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $album = $user->albums()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-').time(),
            'description' => $request->description,
        ]);

        if ($request->input('cover', false)) {
            $album->addMedia(storage_path('tmp/uploads/'.basename($request->input('cover'))))->toMediaCollection('cover');
        }

        if ($request->input('images', false)) {
            foreach ($request->input('images') as $image) {
                $album->addMedia(storage_path('tmp/uploads/'.basename($image)))->toMediaCollection('images');
            }
        }

        return redirect()->route('my_albums');
    }

    public function update($slug, Request $request)
    {
        $user = auth()->user();

        $album = $user->albums()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-').time(),
            'description' => $request->description,
        ]);

        return redirect()->route('my_albums');
    }

    public function delete($slug)
    {
        $user = auth()->user();
        $album = $user->albums()->where('slug', $slug)->first();
        if (!$album) {
            throw new \Exception('Error Processing Request', 1);
        }
        if (count($album->images) > 0) {
            $response['status'] = 1;
            $response['message'] = 'Album not empty , choose delete all or move to another album.';
        } elseif (0 == count($album->images)) {
            $response['status'] = 2;
            $response['message'] = 'Album has been deleted.';
            $album->delete();
        }

        return response()->json($response, 200);
    }

    public function confirm_delete($slug)
    {
        $user = auth()->user();
        $album = $user->albums()->where('slug', $slug)->first();
        if (!$album) {
            throw new \Exception('Error Processing Request', 1);
        }

        $response['status'] = 1;
        $response['message'] = 'Album has been deleted.';

        $album->delete();

        return response()->json($response, 200);
    }

    public function move_images($slug, Request $request)
    {
        $user = auth()->user();
        $album = $user->albums()->where('slug', $slug)->first();
        if ($request->new_album) {
            if ($album->id == $request->new_album) {
                $response['status'] = -2;
                $response['message'] = 'Choose another Album';
            } else {
                $new_album = $user->albums()->find($request->new_album);
                foreach ($album->images as $image) {
                    $image->model_id = $new_album->id;
                    $image->save();
                }
                $album->delete();

                $response['status'] = 1;
                $response['message'] = 'Album has been deleted.';
            }

            return response()->json($response, 200);
        } else {
            abort(404);
        }
    }
}
