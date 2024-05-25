<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Throwable;

class PeminjamanController extends Controller
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
            $data = Peminjaman::with(['siswa', 'buku'])->get();
        } else {
            $data = Peminjaman::where('penerbit', '=', $request->input('penerbit'))->orWhere('judul_buku', $request->input('judul_buku'))->get();
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
                'tgl_pinjam' => 'required',
                'tgl_pengembalian' => 'required',
                'status_peminjaman' => 'required',
                'id_user' => 'required',
                'id_buku' => 'required',
            ]);
            if ($validatedData) {
                $data = Peminjaman::create($request->all());
            }

            // INSERT INTO `peminjaman` (`id`, `tgl_pinjam`, `tgl_pengembalian`, `status`, `id_siswa`, `id_buku`, `created_at`, `updated_at`) VALUES (NULL, '2024-05-18', '2024-05-18', 'approved', '1', '1', NULL, NULL);
            return response()->json(["msg" => "Succesfully Created Data", "data" => $data], 201);
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
            $data = Peminjaman::findOrFail($id);
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
            'tgl_pinjam' => 'required',
            'tgl_pengembalian' => 'required',
            'status_peminjaman' => 'required',
            'id_user' => 'required',
            'id_buku' => 'required',
        ]);

        $data = Peminjaman::where('id_peminjaman', '=', $id)->update($validatedData);
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
            $data = Peminjaman::where('id_peminjaman', '=', $id);
            if (!$data->first()) {
                return response()->json(['msg' => 'Data Not Found']);
            } else {
                $data->delete();
                return response()->json(['msg' => 'Successfully Deleted Data']);
            }
            // return response()->json($data == null);
        } catch (Throwable $e) {
            return response()->json($e->errorInfo);
        }
    }
}
