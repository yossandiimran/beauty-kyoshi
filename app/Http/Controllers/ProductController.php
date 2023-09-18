<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahbaris = 4;
        if (strlen($katakunci)) {
            $data = Product::where('name', 'like', "%$katakunci%")
                ->orWhere('name', 'like', "%$katakunci%")
                ->orWhere('description', 'like', "%$katakunci%")
                ->paginate($jumlahbaris);
        } else {
            $data = Product::orderBy('name', 'desc')->paginate($jumlahbaris);
        }
        return view('products.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Session::flash('name', $request->name);
        Session::flash('price', $request->price);
        Session::flash('description', $request->description);
        Session::flash('category_id', $request->category_id);

        $request->validate([
            // 'nim' => 'required|numeric|unique:mahasiswa,nim',
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'category_id' => 'required'
        ], [
            // 'nim.required' => 'NIM wajib diisi',
            // 'nim.numeric' => 'NIM wajib dalam angka',
            // 'nim.unique' => 'NIM yang diisikan sudah ada dalam database',
            'name.required' => 'Nama wajib diisi',
            'price.required' => 'Harga dalam angka',
        ]);
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ];
        Product::create($data);
        return redirect()->to('products')->with('success', 'Berhasil menambahkan data');
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::where('id', $id)->delete();
        return redirect()->to('products')->with('success', 'Berhasil melakukan delete data');
    }
}
