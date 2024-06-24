<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BukuController extends Controller
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
            $data = Buku::all();
        } else {
            $data = Buku::where('penerbit', '=', $request->input('penerbit'))->orWhere('judul_buku', $request->input('judul_buku'))->get();
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
                'judul_buku' => 'required',
                'penerbit' => 'required',
                'deskripsi' => 'required',
                'tipe' => 'required',
                'image' => 'string',
                'qty' => 'required|integer',
            ]);
            if ($validatedData['image'] != null) {
                $imageData = $validatedData['image'];

                preg_match('/^data:image\/(\w+);base64,/', $imageData, $type);
                $extension = strtolower($type[1]);

                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);

                $fileName = uniqid() . '.' . $extension;
                $filePath = public_path("storage\\book\\" . $fileName);

                file_put_contents($filePath, $imageData);
                $validatedData['image'] = $fileName;
                
                // $path = Storage::disk('public')->put('book', $imageData);
                // $validatedData['image'] = $path;
            }

            $data = Buku::create($validatedData);
            return response()->json(["msg" => "Succesfully Created Data", "data" => $data], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'msg' => $e->errors()
            ], 400);
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
            $data = Buku::findOrFail($id);
            return response()->json($data);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'Data not found'
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
            'judul_buku' => 'required',
            'penerbit' => 'required',
            'deskripsi' => 'required',
            'tipe' => 'required',
            'image' => 'file',
            'qty' => 'required|number',
        ]);

        // $previousImage = Buku::findOrFail($id)->image;

        // if($validatedData){
        //     Storage::delete($previousImage);
        // }

        $path = Storage::disk('public')->put('book', $validatedData['image']);
        $validatedData['image'] = $path;
        $data = Buku::create($validatedData);
        $data = Buku::where('id_buku', '=', $id)->update($validatedData);

        if ($data) {
            return response()->json(['msg' => "Data Updated"]);
        } else {
            return response()->json(['msg' => "Data Gagal Di perbaharui"], 400);
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
            $user = Buku::where('id_buku', '=', $id);
            if (!$user->first()) {
                return response()->json(['msg' => 'Data Not Found']);
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
