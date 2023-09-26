<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terapis;

class TerapisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('terapis.index');
    }

    public function data()
    {
        $terapis = Terapis::orderBy('kode_terapis')->get();

        return datatables()
            ->of($terapis)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_terapis[]" value="'. $produk->id_terapis .'">
                ';
            })
            ->addColumn('kode_member', function ($terapis) {
                return '<span class="label label-success">'. $terapis->kode_terapis .'<span>';
            })
            ->addColumn('aksi', function ($terapis) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('terapis.update', $terapis->id_terapis) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('terapis.destroy', $terapis->id_terapis) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_member'])
            ->make(true);
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
        $terapis = Terapis::latest()->first() ?? new Terapis();
        $request['kode_terapis'] = 'terapis-'. tambah_nol_didepan((int)$terapis->id_terapis+1, 5);

        $terapis = Terapis::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $terapis = Terapis::find($id);

        return response()->json($terapis);
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
        $terapis = Terapis::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $terapis = Terapis::find($id);
        $terapis->delete();

        return response(null, 204);
    }
}
