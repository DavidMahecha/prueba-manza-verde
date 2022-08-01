<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\{Auth, Hash};
use App\Models\User;
use App\Traits\ResponseApi;

class AuthController extends Controller
{
    use ResponseApi;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return $this->errorResponse(['Usuario no autorizado'], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        return $this->successResponse([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], Response::HTTP_CREATED);
    }

    public function logout()
    {
        Auth::logout();

        return $this->successResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function refresh()
    {
        return $this->successResponse([
            'user' => Auth::user(),
            'token' => Auth::refresh(),
        ]);
    }
}
