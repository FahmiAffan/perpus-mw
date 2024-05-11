<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('My Token')->plainTextToken;
            return response()->json(['user' => Auth::user(), 'token' => $token]);
        }
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "email" => 'required|email',
                "password" => 'required',
                "nama_petugas" => 'required',
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
        }else{
            return response()->json(["msg" => "unauthorized"]);
        }
    }
}
