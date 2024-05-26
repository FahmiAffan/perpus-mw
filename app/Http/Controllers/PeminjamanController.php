<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Carbon\Carbon;
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
            // $data = Peminjaman::with(['siswa', 'buku'])->get();
            $data = Peminjaman::with(['buku'])->get();
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
                'nik' => 'required',
                'nama_siswa' => 'required',
                'tgl_pinjam' => 'required',
                'tgl_pengembalian' => 'required',
                'status_peminjaman' => 'required',
                // 'id_user' => 'required',
                'nama_siswa' => 'required',
                'nik' => 'required',
                'id_buku' => 'required',
            ]);

            if ($validatedData) {
                $date1 = Carbon::createFromDate($request->input('tgl_pengembalian'))->format('Y-m-d');
                $date2 = Carbon::createFromDate($request->input('tgl_pinjam'))->format('Y-m-d');

                $validatedData['tgl_pengembalian'] = $date1;
                $validatedData['tgl_pinjam'] = $date2;
                $data = Peminjaman::create($validatedData);
            }
            return response()->json(["msg" => "Succesfully Created Data", "data" => $data], 201);

            // INSERT INTO `peminjaman` (`id`, `tgl_pinjam`, `tgl_pengembalian`, `status`, `id_siswa`, `id_buku`, `created_at`, `updated_at`) VALUES (NULL, '2024-05-18', '2024-05-18', 'approved', '1', '1', NULL, NULL);
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
            'nik' => 'required',
            'nama_siswa' => 'required',
            'tgl_pinjam' => 'required',
            'tgl_pengembalian' => 'required',
            'status_peminjaman' => 'required',
            'id_user' => 'required',
            'id_buku' => 'required',
        ]);

        dd($validatedData);

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
                return response()->json(['msg' => 'Successfully Deleted Data'], 201);
            }
            // return response()->json($data == null);
        } catch (Throwable $e) {
            return response()->json($e->errorInfo);
        }
    }

    public function updateStatus($id, Request $request)
    {
        $data = Peminjaman::where('id_peminjaman', '=', $id)->update([
            'status_peminjaman' => $request->input('status_peminjaman')
        ]);

        if ($data) {
            return response()->json(["msg" => "Berhasil Update Status"], 201);
        }else{
            return response()->json(["msg" => "terjadi kesalahan"], 400);

        }
        dd($request->all());
    }
}
