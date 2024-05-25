<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('nik', 'password');

        if (Auth::attempt($credentials)) {
            $accessToken = Auth::user()->createToken('access_token', ['*'], Carbon::now()->addMinutes(config('sanctum.ac_expiration')))->plainTextToken;
            $refreshToken = Auth::user()->createToken('refresh_token', ['*'], Carbon::now()->addMinutes(config('sanctum.rt_expiration')))->plainTextToken;
            return response()->json(['user' => Auth::user(), 'accessToken' => $accessToken, 'refreshToken' => $refreshToken]);
        } else {
            return response()->json(["msg" => "wrong email or password"], 400);
        }
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "nik" => 'required|email',
                "password" => 'required',
                "username" => 'required',
            ]);
            if ($validatedData) {
                $validatedData['password'] = Hash::make($request->password);
                $data = User::create($validatedData);
                return response()->json(["msg" => "user successfully created", "data" => $data], 201);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens()->delete();
            return response()->json(["msg" => "Berhasil Logout"], 200);
        } else {
            return response()->json(["msg" => "unauthorized"]);
        }
    }
}
