<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *              title="E-registry Api",
 *              version="1.0.0",
 * ),
 *
 * @OA\PathItem(path="/api")
 *
 *
 */
class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
            'department' => ['required', 'string'],
            'job_title' => ['required', 'string'],
            'agree' => ['required', 'in:1']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->fullname,
            'department' => $request->department,
            'job_title' => $request->job_title,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        return response()->json(['status' => true, 'message' => 'registration successful'], 200);
    }
}