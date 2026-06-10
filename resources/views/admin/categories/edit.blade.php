@extends('layouts.admin')

@section('content')
  <h3>Edit Kategori</h3>

  <form action="{{ route('admin.categories.update', $category) }}" method="post">
    @csrf @method('PUT')
    <div class="mb-3">
      <label class="form-label">Nama Kategori</label>
      <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori', $category->nama_kategori) }}">
    </div>
    <button class="btn btn-primary">Perbarui</button>
  </form>
@endsection
