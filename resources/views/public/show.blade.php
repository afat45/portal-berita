@extends('layouts.public')

@section('content')
  <div class="row">
    <div class="col-lg-8">
      <!-- Article Header -->
      <div class="mb-4">
        <div class="mb-3">
          @if($post->categories && $post->categories->count() > 0)
            @foreach($post->categories as $cat)
              <span class="badge bg-danger me-1">{{ $cat->nama_kategori }}</span>
            @endforeach
          @else
            <span class="badge bg-danger">{{ $post->category->nama_kategori }}</span>
          @endif
        </div>
        <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
        <div class="d-flex align-items-center text-muted mb-4" style="gap: 1rem;">
          <span><i class="bi bi-person"></i> {{ $post->author }}</span>
          <span><i class="bi bi-calendar3"></i> {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y') : '-' }}</span>
        </div>
      </div>

      <!-- Featured Image -->
      @if($post->image)
        @if(str_starts_with($post->image, 'http'))
          <img src="{{ $post->image }}" class="img-fluid rounded-3 mb-4" style="max-height: 500px; object-fit: cover; width: 100%;" alt="{{ $post->title }}" onerror="this.onerror=null; this.src='https://via.placeholder.com/800x500?text=Image+Not+Found';">
        @else
          <img src="{{ asset('storage/'.$post->image) }}" class="img-fluid rounded-3 mb-4" style="max-height: 500px; object-fit: cover; width: 100%;" alt="{{ $post->title }}" onerror="this.onerror=null; this.src='https://via.placeholder.com/800x500?text=Image+Not+Found';">
        @endif
      @else
        <div class="bg-secondary d-flex align-items-center justify-content-center rounded-3 mb-4" style="height: 400px;"><i class="bi bi-image text-white" style="font-size: 4rem;"></i></div>
      @endif

      <!-- Article Content -->
      <article class="mb-5" style="line-height: 1.8; font-size: 1.05rem;">
        {!! nl2br(e($post->content)) !!}
      </article>

      <!-- Related Articles -->
      @if($related->count() > 0)
        <hr class="my-5">
        <h4 class="fw-bold mb-4">Artikel Terkait</h4>
        <div class="row g-3">
          @foreach($related as $r)
            <div class="col-md-6">
              <div class="card h-100">
                @if($r->image)
                  @if(str_starts_with($r->image, 'http'))
                    <img src="{{ $r->image }}" class="card-img-top" style="height: 180px; object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-secondary d-flex align-items-center justify-content-center\' style=\'height: 180px;\'><i class=\'bi bi-image text-white\' style=\'font-size: 2rem;\'></i></div>';">
                  @else
                    <img src="{{ asset('storage/'.$r->image) }}" class="card-img-top" style="height: 180px; object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-secondary d-flex align-items-center justify-content-center\' style=\'height: 180px;\'><i class=\'bi bi-image text-white\' style=\'font-size: 2rem;\'></i></div>';">
                  @endif
                @else
                  <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;"><i class="bi bi-image text-white" style="font-size: 2rem;"></i></div>
                @endif
                <div class="card-body">
                  <h6 class="card-title fw-bold"><a href="{{ route('posts.show', $r->slug) }}" class="text-decoration-none">{{ \Illuminate\Support\Str::limit($r->title, 60) }}</a></h6>
                  <small class="text-muted">{{ $r->published_at ? \Carbon\Carbon::parse($r->published_at)->format('d M Y') : '-' }}</small>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
      <div class="sticky-top" style="top: 80px;">
        <!-- Share -->
        <div class="card mb-4">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Bagikan Artikel</h6>
            <div class="d-flex gap-2">
              <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-facebook"></i></a>
              <a href="#" class="btn btn-sm btn-outline-info"><i class="bi bi-twitter"></i></a>
              <a href="#" class="btn btn-sm btn-outline-danger"><i class="bi bi-pinterest"></i></a>
            </div>
          </div>
        </div>

        <!-- About Portal -->
        <div class="card">
          <div class="card-body">
            <h6 class="fw-bold mb-2"><i class="bi bi-dice"></i> Boardgame Hub</h6>
            <p class="small">Boardgame Hub adalah sumber paduan informasi terpercaya untuk boardgame</p>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary">Kembali ke Beranda</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
