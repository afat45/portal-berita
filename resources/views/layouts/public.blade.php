<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Boardgame Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
      :root {
        --primary: #1a1a1a;
        --secondary: #2c2c2c;
        --accent: #d63031;
        --light: #f5f5f5;
      }
      body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: var(--primary); background: #fff; }
      .navbar { background: var(--primary) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1rem 0; }
      .navbar-brand { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; }
      .nav-link { margin: 0 0.5rem; transition: color 0.3s; }
      .nav-link:hover { color: var(--accent) !important; }
      main { background: white; }
      footer { background: var(--primary); color: white; padding: 3rem 0; margin-top: 4rem; font-size: 0.95rem; }
      .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; border-radius: 8px; }
      .card:hover { transform: translateY(-4px); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
      .card-img-top { height: 220px; object-fit: cover; }
      h1, h2, h3, h4, h5, h6 { color: var(--primary); font-weight: 600; }
      .accent { color: var(--accent); }
      /* SIMPLE PAGINATION - TEXT ONLY */
      .pagination { 
        gap: 0.3rem; 
        margin-bottom: 0;
        justify-content: center;
      }
      .pagination .page-link { 
        padding: 0.3rem 0.6rem; 
        font-size: 0.9rem; 
        border: 1px solid #ddd;
        border-radius: 3px;
        color: #333;
      }
      /* HIDE SVG COMPLETELY */
      .pagination svg { display: none !important; }
      /* SHOW ONLY TEXT */
      .pagination .page-link span { 
        display: inline !important;
        font-weight: 500;
      }
      /* PREV & NEXT TEXT */
      .pagination .page-item:first-child .page-link span::before { content: "« "; }
      .pagination .page-item:last-child .page-link span::before { content: " »"; }
      
      .pagination .page-link:hover { 
        background-color: #f5f5f5;
        border-color: var(--accent);
        color: var(--accent);
      }
      .pagination .active .page-link { 
        background-color: var(--accent);
        border-color: var(--accent);
        color: white;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container-lg">
        <a class="navbar-brand" href="{{ route('home') }}"><i class="bi bi-dice-4"></i> Boardgame Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="#tentang">Tentang Kami</a></li>
            <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
            @auth
            <li class="nav-item"><a class="nav-link" href="{{ url('/admin') }}"><i class="bi bi-speedometer2"></i> Admin</a></li>
            <li class="nav-item">
              <form action="{{ route('logout') }}" method="post" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link nav-link" style="display: inline;"><i class="bi bi-box-arrow-right"></i> Logout</button>
              </form>
            </li>
            @else
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="bi bi-lock"></i> Login</a></li>
            @endauth
          </ul>
        </div>
      </div>
    </nav>

    <main>
      <div class="container-lg py-5">
        @yield('content')
      </div>
    </main>

    <footer id="tentang" style="padding-top: 4rem;">
      <div class="container-lg">
        <div class="row">
          <div class="col-md-3 mb-4">
            <h5 class="text-white"><i class="bi bi-dice-4"></i> Boardgame Hub</h5>
            <p class="text-light">Sumber informasi terpercaya tentang dunia boardgame, card game, RPG, dan strategi gaming terkini.</p>
          </div>
          <div class="col-md-3 mb-4">
            <h5 class="text-white">Kategori Game</h5>
            <ul class="list-unstyled">
              <li><a href="#" class="text-light text-decoration-none small">Board Game</a></li>
              <li><a href="#" class="text-light text-decoration-none small">Card Game</a></li>
              <li><a href="#" class="text-light text-decoration-none small">Tabletop RPG</a></li>
              <li><a href="#" class="text-light text-decoration-none small">Miniature Wargaming</a></li>
            </ul>
          </div>
          <div class="col-md-3 mb-4">
            <h5 class="text-white">Tentang Kami</h5>
            <p class="small text-light">Boardgame Hub adalah platform informasi untuk para penggemar boardgame dan gaming strategy yang ingin tetap update dengan berita terbaru industri.</p>
          </div>
          <div class="col-md-3 mb-4" id="kontak">
            <h5 class="text-white">Kontak Kami</h5>
            <p class="small text-light">
              <i class="bi bi-envelope"></i> Email: darmapala9945@gmail.com<br>
              <i class="bi bi-telephone"></i> Telepon: +62 813-4577-3088
            </p>
          </div>
        </div>
        <hr class="bg-light">
        <div class="text-center mt-3">
          <p class="text-light">&copy; 2026 Boardgame Hub. Semua hak cipta terlindungi.</p>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
