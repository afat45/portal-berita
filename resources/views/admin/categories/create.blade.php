@extends('layouts.admin')

@section('content')
  <h3>Tambah Kategori</h3>

  <form action="{{ route('admin.categories.store') }}" method="post">
    @csrf
    <div class="mb-3">
      <label class="form-label">Nama Kategori</label>
      <input type="text" name="nama_kategori" class="form-control">
    </div>
    <button class="btn btn-primary">Simpan</button>
  </form>
@endsection
