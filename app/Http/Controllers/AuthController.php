<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'account_name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required'
            ]);
            $data = $request->all();

            $data['password'] = Hash::make($data['password']);
            $data['email_verified_at'] = date('Y-m-d H:i:s');

            $user = User::create($data);
            $response = $user->toArray();
            $response['token'] =  $user->createToken($user['email'])->plainTextToken;

            return response()->json($response, 200);
        } catch (\Exception $e) {
            Logger($e);
            abort(404);
        }
    }
}
