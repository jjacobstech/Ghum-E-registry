<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [

                'email' => ["required", "email", "string"],
                'password' => ['required', Rules\Password::defaults()]
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $credentials = $validator->getData();

        $email = $credentials['email'];
        $password = $credentials['password'];


        $login = Auth::attempt(['email' => $email, 'password' =>  $password]);

        if (!$login) {
            return response()->json(["message" => 'These Credentials are incorrect'], 400);
        }
        $user = Auth::user();

        $token = $user->createToken('api_token')->plainTextToken;

        $token = explode('|', $token);

        $api_token = $token[1];



        return response()->json(['user' => $user, 'token' => $api_token], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'log out route active'], 200);

        return response()->json(['message' => 'logged out sucessfully'], 200);
    }
}
