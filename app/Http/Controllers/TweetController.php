<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;
use Storage;

use App\Models\Tweet;

class TweetController extends Controller
{
    function index()
    {
        try {
            $response = Tweet::latest()->with('user')->get();
            return response()->json($response, 200);
        } catch (\Exception $e) {
            Logger($e);
            abort(404);
        }
    }


    function create(Request $request)
    {
        try {
            $request->validate(
                [
                    'comment_to' => 'nullable|numeric',
                    'body' => 'nullable|string',
                    'images' => 'nullable|array:image',
                    'images.*.image' => 'string'
                ]
            );

            $data = $request->except('images');
            $user = $request->user();

            $tweet = $user->tweets()->create($data);

            if ($request->has('images')) {
                $path = 'tweets/images';
                $request_images = $request->only('images');

                foreach ($request_images as $request_image) {
                    $image = Image::make($request_image)->orientate()->encode('jpg');
                    $hash = md5($image->__toString());
                    $file_name = "{$hash}.jpg";
                    Storage::makeDirectory($path);
                    Storage::put($path . '/' . $file_name, $image->encode(), 'public');

                    $image->destroy();
                    $tweet->images()->create(['image' => $file_name]);
                }
            }
        } catch (\Exception $e) {
            Logger($e);
            abort(404);
        }
    }
}
