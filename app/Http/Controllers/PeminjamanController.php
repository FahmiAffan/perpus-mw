<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Error;
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

        // $data = Peminjaman::find(2);
        // $date = Carbon::parse($data->tgl_pinjam);
        // $data->tgl_pinjam = $date->diffForHumans();


        // dd($data);

        if ($request->input() == null) {
            $data = Peminjaman::with(['siswa', 'list_buku'])->get();
            foreach ($data as $key => $datas) {
                $data[$key]->tgl_pinjam = Carbon::parse($datas->tgl_pinjam)->diffForHumans();
                $data[$key]->tgl_pengembalian = Carbon::parse($datas->tgl_pengembalian)->diffForHumans();
            }
            return response()->json(["msg" => "Successfuly Get Data", "data" => $data], 200);
        } else {
            $data = Peminjaman::where('penerbit', '=', $request->input('penerbit'))->orWhere('judul_buku', $request->input('judul_buku'))->get();
        }
        // if (!$data->first()) {
        //     return response()->json(["msg" => "data not found"]);
        // } else {
        //     return response()->json(["msg" => "Succesfuly Get Data", "data" => $data], 200);
        // }
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
                'approval' => 'null',
                'id_user' => 'required',
                'list_buku' => 'required',
            ]);


            if ($validatedData) {
                $date1 = Carbon::createFromDate($request->input('tgl_pengembalian'))->format('Y-m-d');
                $date2 = Carbon::createFromDate($request->input('tgl_pinjam'))->format('Y-m-d');

                $validatedData['tgl_pengembalian'] = $date1;
                $validatedData['tgl_pinjam'] = $date2;

                $data = Peminjaman::create($validatedData);

                foreach ($request->list_buku as $key => $field) {
                    DetailPeminjaman::create([
                        "id_peminjaman" => $data->id_peminjaman,
                        "id_buku" => $field['id_buku'],
                        "qty" => $field['qty']
                    ]);

                    $dataBuku[$key] = Buku::find($field['id_buku']);

                    if ($dataBuku[$key]->qty == null || $dataBuku[$key]->qty == 0 || $dataBuku[$key]->qty <= 0) {
                        return response()->json(["msg" => "Stok Buku dengan nama " . $dataBuku[$key]->judul_buku . " Habis!!"], 400);
                    } else {
                        $dataBuku[$key]->decrement('qty', $field['qty']);
                        return response()->json(["msg" => "Succesfully Created Data", "data" => $data], 201);
                    }
                }
            }
        } catch (ValidationException $e) {
            return response()->json([
                'msg' => $e->errors()
            ], 400);
        }

        // try {
        //     $validatedData = $request->validate([
        //         'tgl_pinjam' => 'required',
        //         'tgl_pengembalian' => 'required',
        //         'status_peminjaman' => 'required',
        //         'approval' => 'null',
        //         'id_user' => 'required',
        //         'id_buku' => 'required',
        //     ]);

        //     if ($validatedData) {
        //         $date1 = Carbon::createFromDate($request->input('tgl_pengembalian'))->format('Y-m-d');
        //         $date2 = Carbon::createFromDate($request->input('tgl_pinjam'))->format('Y-m-d');

        //         $validatedData['tgl_pengembalian'] = $date1;
        //         $validatedData['tgl_pinjam'] = $date2;
        //         $data = Peminjaman::create($validatedData);
        //     }
        //     return response()->json(["msg" => "Succesfully Created Data", "data" => $data], 201);
        // } catch (ValidationException $e) {
        //     return response()->json([
        //         'msg' => $e->errors()
        //     ], 400);
        // }
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
        $data = Peminjaman::with('list_buku')->find($id);

        if ($data->status_peminjaman == 'returned') {
            return response()->json(["msg" => "Peminjaman Telah Berakhir"], 400);
        } else {
            try {
                $data->update([
                    'status_peminjaman' => $request->input('status_peminjaman')

                ]);

                foreach ($data->list_buku as $key => $datas) {
                    // $totalQty = [];
                    // $totalQty[$key] = $datas->qty;
                    // $totalQty[$key] = $datas->qty;

                    $dataBuku[$key] = Buku::find($datas['id_buku']);

                    $dataBuku[$key]->increment('qty', $datas->qty);
                }
                return response()->json(["msg" => "Berhasil Update Status"], 201);
            } catch (Error $e) {
                return response()->json(["msg" => $e->getMessage()], 400);
            }
        }


        // return response()->json(array_sum($totalQty));
        return response()->json(["msg" => "Data Berhasil Di Update", "data" => $data, "list_buku" => $data->list_buku], 201);
    }
}
