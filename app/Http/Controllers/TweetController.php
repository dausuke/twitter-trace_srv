<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tweet;

class TweetController extends Controller
{
    function index()
    {
        $response = Tweet::latest()->with('user')->get();
        return response()->json($response, 200);
    }
}
