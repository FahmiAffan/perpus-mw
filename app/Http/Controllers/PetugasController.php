<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->input() == null) {
            $data = User::all();
        } else {
            $data = User::where('username', '=', $request->input('username'))->orWhere('email', $request->input('email'))->get();
        }
        if (!$data->first()) {
            return response()->json(["msg" => "data not found"]);
        } else {
            return response()->json(["msg" => "Succesfuly Get Data", "data" => $data], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $validatedData = $request->validate([
                "nik" => 'required|integer',
                "password" => 'required',
                "username" => 'required',
                "role" => 'required',
                "telp" => 'required',
                "image" => 'file',
            ]);
            if ($validatedData) {
                $validatedData['password'] = Hash::make($request->password);
                if ($request->input('image') != null) {
                    $path = Storage::disk('public')->put('avatar', $validatedData['image']);
                    $validatedData['image'] = $path;
                };
                $data = User::create($validatedData);
                return response()->json(["msg" => "user successfully created", "data" => $data], 201);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $data = User::findOrFail($id);
            return response()->json($data);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'user not found'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $validatedData = $request->validate([
                "nik" => 'required|integer',
                "password" => 'required',
                "username" => 'required',
                "role" => 'required',
                "telp" => 'required',
                "image" => 'file',
            ]);

            if ($validatedData) {
                $oldImage = User::find($id)->image;
                Storage::delete($oldImage);

                $path = Storage::disk('public')->put('avatar', $validatedData['image']);
                $validatedData['image'] = $path;
                
                $data = User::where('id_buku', '=', $id)->update($validatedData);
            }

            return response()->json(["msg" => "Data Berhasil Di Perbarui", "data" => $data]);
        } catch (Exception $e) {
            return response()->json(["msg" => $e->getMessage(), "req" => $request]);
        }

        if ($data) {
            return response()->json(['msg' => "Data Updated"]);
        } else {
            return Response::HTTP_REQUEST_TIMEOUT;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $user = User::where('id', '=', $id);
            if (!$user->first()) {
                return response()->json(['msg' => 'User Not Found']);
            } else {
                $user->delete();
                return response()->json(['msg' => 'Successfully Deleted Data'], 201);
            }
            return response()->json($user == null);
        } catch (Throwable $e) {
            return response()->json($e->errorInfo);
        }
    }
}
