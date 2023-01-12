<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;
use Illuminate\Support\Facades\Storage;

use App\Models\Tweet;

class TweetController extends Controller
{
    function index()
    {
        try {
            $response = Tweet::latest()->with('user', 'images')->get();
            return response()->json($response, 200);
        } catch (\Exception $e) {
            Logger($e);
            abort(404);
        }
    }


    function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'comment_to' => 'nullable|numeric',
                    'body' => 'nullable|string',
                    'images' => 'nullable|array',
                ]
            );

            $data = $request->except('images');
            $user = $request->user();

            $tweet = $user->tweets()->create($data);

            if ($request->has('images')) {
                $path = 'public/tweets/images';

                foreach ($request->images as $request_image) {
                    $image = Image::make($request_image)->orientate()->encode('png');
                    $hash = md5($image->__toString());
                    $file_name = "{$hash}.png";
                    Storage::put($path . '/' . $file_name, $image->encode(), 'public');

                    $image->destroy();
                    $tweet->images()->create(['image' => $file_name]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Logger($e);
            abort(404);
        }
    }
}
