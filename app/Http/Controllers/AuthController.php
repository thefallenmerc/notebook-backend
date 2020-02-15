<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!User::whereEmail($request->email)->exists()) {
            User::create([
                'name' => 'New User',
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        }

        if (!auth()->attempt($validator->validated())) {
            return response()->json(['password' => ['Incorrect password!']], 422);
        } else {
            $user = auth()->user();
            $token = $user->createToken('PAT')->accessToken;

            $user->token = $token;

            return response()->json($user);
        }
    }
}
