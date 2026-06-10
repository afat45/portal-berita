@extends('layouts.admin')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-5">
    <div>
      <h2 class="fw-bold mb-1"><i class="bi bi-dice-5 me-2"></i>Manajemen Kategori Game</h2>
      <p class="text-muted mb-0">Kelola semua kategori game Anda di satu tempat</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-danger px-4 py-2 shadow-sm">
      <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
    </a>
  </div>

  <!-- Search & Sort -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
      <form method="get" class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="form-label small fw-semibold text-uppercase text-muted mb-2">
            <i class="bi bi-search me-1"></i>Pencarian
          </label>
          <input type="text" name="search" class="form-control form-control-lg border-0 bg-light" placeholder="Cari nama kategori..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold text-uppercase text-muted mb-2">
            <i class="bi bi-sort-down me-1"></i>Urutkan
          </label>
          <select name="sort" class="form-select form-select-lg border-0 bg-light">
            <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A-Z</option>
            <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z-A</option>
            <option value="most" {{ request('sort') == 'most' ? 'selected' : '' }}>Terbanyak Artikel</option>
            <option value="least" {{ request('sort') == 'least' ? 'selected' : '' }}>Tersedikit Artikel</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-dark btn-lg w-100 shadow-sm">
            <i class="bi bi-funnel me-2"></i>Filter
          </button>
        </div>
        @if(request('search') || request('sort'))
          <div class="col-12">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
              <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
            </a>
          </div>
        @endif
      </form>
    </div>
  </div>

  <!-- Categories Grid - Simple & Clean -->
  <div class="row g-4">
    @forelse($categories as $cat)
      <div class="col-md-6 col-lg-4">
        <div class="category-card">
          <div class="category-card-header">
            <div class="d-flex align-items-center gap-3">
              <div class="category-icon">
                <i class="bi bi-tag-fill"></i>
              </div>
              <div class="flex-grow-1">
                <h5 class="category-title mb-0">{{ $cat->nama_kategori }}</h5>
              </div>
              <div class="category-badge">
                {{ $cat->posts_count ?? 0 }}
              </div>
            </div>
          </div>

          <div class="category-card-body">
            <p class="category-description">
              <i class="bi bi-file-text me-2"></i>{{ $cat->posts_count ?? 0 }} artikel terpublikasi
            </p>
          </div>

          <div class="category-card-footer">
            <a href="{{ route('admin.categories.edit', $cat) }}" class="btn-category btn-edit">
              <i class="bi bi-pencil-square"></i>
              <span>Edit</span>
            </a>
            <form action="{{ route('admin.categories.destroy', $cat) }}" method="post" class="btn-category-form" onsubmit="return confirm('Yakin ingin menghapus kategori {{ $cat->nama_kategori }}?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn-category btn-delete">
                <i class="bi bi-trash3"></i>
                <span>Hapus</span>
              </button>
            </form>
          </div>
        </div>
      </div>

    @empty
      <div class="col-12">
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center" role="alert">
          <i class="bi bi-info-circle-fill me-3 fs-4"></i>
          <div>
            <strong>Belum ada kategori.</strong> 
            <a href="{{ route('admin.categories.create') }}" class="alert-link fw-semibold">Buat kategori pertama Anda</a>
          </div>
        </div>
      </div>
    @endforelse
  </div>

  <!-- Pagination -->
  @if($categories->hasPages())
    <div class="mt-5">
      {{ $categories->links() }}
    </div>
  @endif
@endsection

<style>
  /* Category Card - Simple & Clean Design */
  .category-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-color: #d63031;
  }

  /* Card Header */
  .category-card-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%);
    border-bottom: 3px solid #d63031;
  }

  .category-icon {
    width: 48px;
    height: 48px;
    background: rgba(214, 48, 49, 0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d63031;
    font-size: 1.5rem;
    flex-shrink: 0;
  }

  .category-title {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    line-height: 1.3;
    word-break: break-word;
  }

  .category-badge {
    background: #d63031;
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 1rem;
    min-width: 50px;
    text-align: center;
    flex-shrink: 0;
  }

  /* Card Body */
  .category-card-body {
    padding: 1.5rem;
    background: #ffffff;
  }

  .category-description {
    color: #6b7280;
    font-size: 0.95rem;
    margin: 0;
    font-weight: 500;
  }

  /* Card Footer - Perfect 50/50 Buttons */
  .category-card-footer {
    padding: 1.25rem;
    background: #f9fafb;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    border-top: 1px solid #e5e7eb;
  }

  .btn-category-form {
    margin: 0;
    padding: 0;
    display: contents;
  }

  .btn-category {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    width: 100%;
  }

  .btn-category i {
    font-size: 1.1rem;
  }

  /* Edit Button */
  .btn-edit {
    background: #1a1a1a;
    color: #ffffff;
  }

  .btn-edit:hover {
    background: #2c2c2c;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(26, 26, 26, 0.3);
    color: #ffffff;
  }

  /* Delete Button */
  .btn-delete {
    background: #d63031;
    color: #ffffff;
  }

  .btn-delete:hover {
    background: #b91c1c;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(214, 48, 49, 0.4);
  }

  .btn-category:active {
    transform: translateY(0);
  }

  /* Animation */
  .category-card {
    animation: fadeInUp 0.5s ease backwards;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Stagger animation */
  .col-md-6:nth-child(1) .category-card { animation-delay: 0.05s; }
  .col-md-6:nth-child(2) .category-card { animation-delay: 0.1s; }
  .col-md-6:nth-child(3) .category-card { animation-delay: 0.15s; }
  .col-md-6:nth-child(4) .category-card { animation-delay: 0.2s; }
  .col-md-6:nth-child(5) .category-card { animation-delay: 0.25s; }
  .col-md-6:nth-child(6) .category-card { animation-delay: 0.3s; }
  .col-md-6:nth-child(7) .category-card { animation-delay: 0.35s; }
  .col-md-6:nth-child(8) .category-card { animation-delay: 0.4s; }
  .col-md-6:nth-child(9) .category-card { animation-delay: 0.45s; }

  /* Responsive */
  @media (max-width: 768px) {
    .category-card-header {
      padding: 1.25rem;
    }

    .category-icon {
      width: 40px;
      height: 40px;
      font-size: 1.25rem;
    }

    .category-title {
      font-size: 1.1rem;
    }

    .category-badge {
      padding: 0.4rem 0.8rem;
      font-size: 0.9rem;
      min-width: 45px;
    }

    .category-card-body {
      padding: 1.25rem;
    }

    .category-card-footer {
      padding: 1rem;
      gap: 0.5rem;
    }

    .btn-category {
      padding: 0.7rem 0.75rem;
      font-size: 0.85rem;
    }

    .btn-category span {
      display: none;
    }

    .btn-category i {
      font-size: 1.25rem;
    }
  }
</style>
