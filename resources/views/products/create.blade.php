@extends('layout.template')
<!-- START FORM -->
@section('konten') 

<form action='{{ url('products') }}' method='post'>
@csrf 
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <a href='{{ url('products') }}' class="btn btn-secondary"><< kembali</a>
    
    <div class="mb-3 row">
        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name='name' value="{{ Session::get('name') }}" id="name">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="nim" class="col-sm-2 col-form-label">Harga</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name='price' value="{{ Session::get('price') }}" id="price">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="jurusan" class="col-sm-2 col-form-label">Deskripsi</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name='description' value="{{ Session::get('description') }}" id="description">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="jurusan" class="col-sm-2 col-form-label">Category</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name='category_id' value="{{ Session::get('category_id') }}" id="category_id">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="jurusan" class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">SIMPAN</button></div>
    </div>
</div>
</form>
<!-- AKHIR FORM -->
@endsection