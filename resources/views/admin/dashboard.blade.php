@extends('layouts.admin')

@section('content')
  <div class="mb-5">
    <h2 class="fw-bold mb-1"><i class="bi bi-dice-4"></i> Dashboard Boardgame Hub</h2>
    <p class="text-muted mb-4">Selamat datang di Admin Panel Boardgame Hub</p>

    <div class="row g-4">
      <!-- Stat Card: Total Berita -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100" style="border-top: 4px solid #d63031; transition: all 0.3s;">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <h6 class="text-muted mb-2 fw-semibold">Total Artikel Game</h6>
                <h2 class="fw-bold text-danger mb-0">{{ $totalPosts }}</h2>
              </div>
              <div class="rounded-3 bg-danger bg-opacity-10 p-3">
                <i class="bi bi-file-earmark-text text-danger" style="font-size: 1.5rem;"></i>
              </div>
            </div>
            <small class="text-muted"><i class="bi bi-graph-up"></i> Artikel game yang dipublikasikan</small>
          </div>
        </div>
      </div>

      <!-- Stat Card: Total Kategori -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100" style="border-top: 4px solid #1a1a1a; transition: all 0.3s;">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <h6 class="text-muted mb-2 fw-semibold">Total Kategori</h6>
                <h2 class="fw-bold mb-0" style="color: #1a1a1a;">{{ $totalCategories }}</h2>
              </div>
              <div class="rounded-3 bg-dark bg-opacity-10 p-3">
                <i class="bi bi-tags" style="font-size: 1.5rem; color: #1a1a1a;"></i>
              </div>
            </div>
            <small class="text-muted"><i class="bi bi-folder2-open"></i> Kategori tersedia</small>
          </div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="col-md-12 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge"></i> Quick Links</h6>
            <div class="d-grid gap-2">
              <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-danger"><i class="bi bi-plus-circle"></i> Tambah Artikel Game</a>
              <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-plus-circle"></i> Tambah Kategori Game</a>
              <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-list-ul"></i> Lihat Semua Artikel</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
