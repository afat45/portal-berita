@extends('layouts.public')

@section('content')
  <!-- Hero Carousel -->
  <div class="mb-5">
    <div id="heroCarousel" class="carousel slide rounded-3 overflow-hidden shadow-lg" data-bs-ride="carousel" style="height: 500px; position: relative;">
      <div class="carousel-inner h-100">
        @foreach($carousel as $i => $c)
          <div class="carousel-item h-100 {{ $i==0 ? 'active' : '' }}">
            @if($c->image)
              @if(str_starts_with($c->image, 'http'))
                <img src="{{ $c->image }}" class="d-block w-100 h-100" style="object-fit:cover; filter: brightness(0.75);" alt="{{ $c->title }}" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-secondary d-flex align-items-center justify-content-center h-100\'><i class=\'bi bi-image text-white\' style=\'font-size: 3rem;\'></i></div>';">
              @else
                <img src="{{ asset('storage/'.$c->image) }}" class="d-block w-100 h-100" style="object-fit:cover; filter: brightness(0.75);" alt="{{ $c->title }}" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-secondary d-flex align-items-center justify-content-center h-100\'><i class=\'bi bi-image text-white\' style=\'font-size: 3rem;\'></i></div>';">
              @endif
            @else
              <div class="bg-secondary d-flex align-items-center justify-content-center h-100"><i class="bi bi-image text-white" style="font-size: 3rem;"></i></div>
            @endif
            <!-- Overlay Gradient -->
            <div class="position-absolute w-100 h-100" style="top: 0; left: 0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);"></div>
            
            <!-- Caption with Better Spacing -->
            <div class="carousel-caption" style="bottom: 30px; left: 5%; right: 5%; text-align: left;">
              <div class="mb-3">
                @if($c->categories && $c->categories->count() > 0)
                  @foreach($c->categories as $cat)
                    <span class="badge bg-danger me-1">{{ $cat->nama_kategori }}</span>
                  @endforeach
                @else
                  <span class="badge bg-danger">{{ $c->category->nama_kategori }}</span>
                @endif
              </div>
              <h2 class="fw-bold mb-3 text-white" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.8);">{{ \Illuminate\Support\Str::limit($c->title, 60) }}</h2>
              <p class="mb-0 d-none d-md-block" style="font-size: 1rem; text-shadow: 1px 1px 4px rgba(0,0,0,0.8); max-width: 700px;">{{ \Illuminate\Support\Str::limit($c->content, 150) }}</p>
            </div>
          </div>
        @endforeach
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </div>

  <!-- Search & Filter Section -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <form method="get" action="{{ route('home') }}" class="row g-3 align-items-end">
        <div class="col-md-5">
          <label class="form-label small text-muted"><i class="bi bi-search"></i> Cari Artikel</label>
          <input type="text" name="search" class="form-control" placeholder="Cari judul, konten, atau author..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label small text-muted"><i class="bi bi-filter"></i> Filter Kategori</label>
          <select name="category" class="form-select">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted"><i class="bi bi-sort-down"></i> Urutkan</label>
          <select name="sort" class="form-select">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
            <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A-Z</option>
            <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z-A</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-danger w-100"><i class="bi bi-search"></i> Filter</button>
        </div>
        @if(request('search') || request('category') || request('sort'))
          <div class="col-12">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset Filter</a>
          </div>
        @endif
      </form>
    </div>
  </div>

  <!-- Featured Section with Sidebar -->
  <div class="mb-5">
    <h2 class="fw-bold mb-4">Berita & Ulasan Terbaru</h2>
    <div class="row g-4">
      <!-- Posts Grid (Col-8) -->
      <div class="col-lg-8">
        <div class="row g-4">
          @foreach($posts as $post)
          <div class="col-md-6">
            <div class="card h-100 shadow-sm hover" style="transition: all 0.3s;">
              @if($post->image)
                @if(str_starts_with($post->image, 'http'))
                  <img src="{{ $post->image }}" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x200?text=No+Image'">
                @else
                  <img src="{{ asset('storage/'.$post->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x200?text=No+Image'">
                @endif
              @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;"><i class="bi bi-image" style="font-size: 2rem; color: #ddd;"></i></div>
              @endif
              <div class="card-body d-flex flex-column">
                @if($post->categories && $post->categories->count() > 0)
                  <div class="mb-2">
                    @foreach($post->categories as $cat)
                      <span class="badge bg-danger me-1" style="font-size: 0.7rem;">{{ $cat->nama_kategori }}</span>
                    @endforeach
                  </div>
                @else
                  <span class="badge bg-danger mb-2" style="width: fit-content; font-size: 0.75rem;">{{ $post->category->nama_kategori }}</span>
                @endif
                <h6 class="card-title fw-bold"><a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none text-dark">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</a></h6>
                <p class="card-text text-muted" style="font-size: 0.85rem;">Oleh <strong>{{ $post->author }}</strong> • {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y') : '-' }}</p>
                <p class="card-text flex-grow-1" style="font-size: 0.9rem;">{{ \Illuminate\Support\Str::limit($post->content, 80) }}</p>
                <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-danger btn-sm align-self-start">Baca Selengkapnya</a>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Pagination Compact -->
        <div class="mt-4">
          <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm justify-content-center">
              {{ $posts->links() }}
            </ul>
          </nav>
        </div>
      </div>

      <!-- Sidebar (Col-4) -->
      <div class="col-lg-4">
        <!-- Categories Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-danger text-white">
            <h6 class="mb-0"><i class="bi bi-dice-5"></i> Kategori Game</h6>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              @foreach($categories as $c)
                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  <span>{{ $c->nama_kategori }}</span>
                  <span class="badge bg-danger rounded-pill">{{ $c->posts_count }}</span>
                </a>
              @endforeach
            </div>
          </div>
        </div>

        <!-- Trending Posts Card -->
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white">
            <h6 class="mb-0 text-white"><i class="bi bi-star-fill"></i> Trending Game</h6>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              @foreach($popular as $p)
                <a href="{{ route('posts.show', $p->slug) }}" class="list-group-item list-group-item-action">
                  <h6 class="mb-1" style="font-size: 0.95rem;">{{ \Illuminate\Support\Str::limit($p->title, 35) }}</h6>
                  <small class="text-muted">{{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->format('d M Y') : '-' }}</small>
                </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
