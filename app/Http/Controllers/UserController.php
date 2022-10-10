<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'username.required' => 'form username wajib di isi',
                'password.required' => 'form password wajib di isi'
            ]
        );

        if (!Auth::attempt($credentials)) {
            $response = [
                'status' => false,
                'message' => 'username atau password salah'
            ];

            return response()->json($response, Response::HTTP_UNAUTHORIZED);
        }

        //$token = User::where('username', $credentials['username'])->first();
        $token = Auth::user();
        $response = [
            'status' => true,
            'message' => 'berhasil login',
            'token' => $token->createToken(Str::random(100))->plainTextToken
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            'name'      => 'required',
            'username'  => 'required',
            'password'  => 'required',
            'level'     => 'required|numeric'
        ]);

        $credentials['password'] = Hash::make($credentials['password']);

        User::create($credentials);

        $response = [
            'status' => true,
            'message' => 'success create'
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        if (!$token) {
            $response = [
                'status' => false,
                'message' => 'invalid'
            ];

            return response()->json($response, Response::HTTP_UNAUTHORIZED);
        }

        $response = [
            'status' => true,
            'message' => 'berhasil logout'
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
