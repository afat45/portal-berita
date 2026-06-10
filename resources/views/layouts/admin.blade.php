<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Boardgame Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
      :root { --primary: #1a1a1a; --accent: #d63031; }
      body { padding-top: 56px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
      .navbar { background: var(--primary) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
      .sidebar { height: calc(100vh - 56px); position: fixed; left: 0; top: 56px; width: 260px; background: #2c2c2c; color: #fff; padding-top: 1.5rem; overflow-y: auto; }
      .sidebar .sidebar-header { padding: 0 1.5rem 2rem; border-bottom: 1px solid #444; }
      .content { margin-left: 260px; }
      .sidebar a { color: #b0b0b0; text-decoration: none; transition: color 0.3s; }
      .sidebar a:hover, .sidebar a.active { color: var(--accent); }
      .nav-item a { padding: 0.75rem 1.5rem; display: block; }
      .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 8px; }
      .btn-primary { background: var(--primary); border: none; }
      .btn-primary:hover { background: var(--accent); }
      table { font-size: 0.95rem; }
      .badge { padding: 0.5rem 0.75rem; }
      @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); z-index: 1000; transition: transform 0.3s; }
        .sidebar.show { transform: translateX(0); }
        .content { margin-left: 0; }
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="/admin"><i class="bi bi-dice-4"></i> Boardgame Hub Admin</a>
        <div class="d-flex align-items-center gap-2">
          <a href="{{ url('/') }}" class="btn btn-sm btn-outline-light" title="Lihat Situs"><i class="bi bi-eye"></i></a>
        </div>
      </div>
    </nav>

    <div class="sidebar">
      <div class="sidebar-header">
        <div><strong>{{ auth()->user()?->name ?? 'Admin' }}</strong></div>
        <small class="text-muted">{{ auth()->user()?->email ?? '' }}</small>
      </div>
      <ul class="nav flex-column">
        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-dice-4"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('admin.posts.index') }}" class="nav-link"><i class="bi bi-file-text"></i> Artikel Game</a></li>
        <li class="nav-item"><a href="{{ route('admin.categories.index') }}" class="nav-link"><i class="bi bi-dice-5"></i> Kategori Game</a></li>
        <li class="nav-item mt-4">
          <form action="{{ route('logout') }}" method="post">
            @csrf
            <button class="btn btn-sm btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
          </form>
        </li>
      </ul>
    </div>

    <main class="content">
      <div class="container-fluid py-4">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif
        @yield('content')
      </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
