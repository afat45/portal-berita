@extends('layouts.admin')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="bi bi-dice-4"></i> Manajemen Artikel Game</h2>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-danger"><i class="bi bi-plus-circle"></i> Tambah Artikel</a>
  </div>

  <!-- Search & Sort -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="get" class="row g-3 align-items-end">
        <div class="col-md-5">
          <label class="form-label small text-muted">Cari Artikel</label>
          <input type="text" name="search" class="form-control" placeholder="Cari judul, author, atau konten..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label small text-muted">Urutkan Berdasarkan</label>
          <select name="sort" class="form-select">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
            <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A-Z</option>
            <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z-A</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted">Urutan</label>
          <select name="order" class="form-select">
            <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Terbaru / Z-A</option>
            <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Terlama / A-Z</option>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
        </div>
        @if(request('search') || request('sort') || request('order'))
          <div class="col-12">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset Filter</a>
          </div>
        @endif
      </form>
    </div>
  </div>

  <!-- Table -->
  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Author</th>
            <th>Tanggal Terbit</th>
            <th style="width: 150px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($posts as $post)
            <tr>
              <td>
                <strong>{{ Illuminate\Support\Str::limit($post->title, 50) }}</strong>
              </td>
              <td>
                @if($post->categories && $post->categories->count() > 0)
                  @foreach($post->categories as $cat)
                    <span class="badge bg-danger me-1">{{ $cat->nama_kategori }}</span>
                  @endforeach
                @else
                  <span class="badge bg-danger">{{ $post->category->nama_kategori }}</span>
                @endif
              </td>
              <td>{{ $post->author }}</td>
              <td>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y') : '-' }}</td>
              <td>
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('admin.posts.destroy', $post) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">
                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                <p>Tidak ada berita ditemukan.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <div class="mt-4">
    {{ $posts->links() }}
  </div>
@endsection
