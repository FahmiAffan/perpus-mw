<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Throwable;

class SiswaController extends Controller
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
            $data = Siswa::all();
        } else {
            $data = Siswa::where('nama_siswa', '=', $request->input('nama_siswa'))->orWhere('kelas', $request->input('kelas'))->orWhere('NIK', $request->input('NIK'))->get();
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
                'nama_siswa' => 'required',
                'kelas' => 'required',
                'NIK' => 'required',
            ]);
            $data = Siswa::create($validatedData);
            return response()->json(["message" => "Succesfully Created Data", "data" => $data], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'msg' => $e->errors()
            ]);
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
            $data = Siswa::findOrFail($id);
            return response()->json($data);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'siswa not found'
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
        $validatedData = $request->validate([
            'nama_siswa' => 'required',
            'kelas' => 'required',
            'NIK' => 'required',
        ]);
        $data = Siswa::where('id_siswa', '=', $id)->update($validatedData);
        // dd($validatedData);
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
            $user = Siswa::where('id_course', '=', $id);
            if (!$user->first()) {
                return response()->json(['msg' => 'Siswa Not Found']);
            } else {
                $user->delete();
                return response()->json(['msg' => 'Successfully Deleted Data']);
            }
            return response()->json($user == null);
        } catch (Throwable $e) {
            return response()->json($e->errorInfo);
        }
    }
}
