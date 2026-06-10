@extends('layouts.admin')

@section('content')
  <h2 class="fw-bold mb-4"><i class="bi bi-plus-circle"></i> Tambah Artikel Game Baru</h2>

  <form action="{{ route('admin.posts.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-lg-8">
        <div class="mb-3">
          <label class="form-label">Judul Artikel</label>
          <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
          @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Kategori Game Utama</label>
          <select name="category_id" class="form-select" required>
            <option value="">-- Pilih Kategori Utama --</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->nama_kategori }}</option>
            @endforeach
          </select>
          @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Kategori Tambahan (Opsional)</label>
          <small class="text-muted d-block mb-2">Pilih satu atau lebih kategori tambahan dengan menekan Ctrl (Windows) atau Cmd (Mac)</small>
          <select name="additional_categories[]" class="form-select" multiple size="5">
            @foreach($categories as $c)
              <option value="{{ $c->id }}" {{ in_array($c->id, old('additional_categories', [])) ? 'selected' : '' }}>{{ $c->nama_kategori }}</option>
            @endforeach
          </select>
          <small class="text-muted">Kategori utama akan otomatis termasuk, tidak perlu dipilih lagi di sini</small>
          @error('additional_categories')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Author</label>
          <input type="text" name="author" class="form-control" value="{{ old('author', auth()->user()?->name ?? 'Admin') }}" required>
          @error('author')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <!-- IMAGE SECTION: Toggle Upload vs URL -->
        <div class="card mb-3">
          <div class="card-header bg-light">
            <h6 class="mb-0">Gambar Artikel</h6>
          </div>
          <div class="card-body">
            <div class="btn-group w-100 mb-3" role="group">
              <input type="radio" class="btn-check" name="image_type" id="image_file" value="file" checked>
              <label class="btn btn-outline-primary" for="image_file"><i class="bi bi-upload"></i> Upload File</label>

              <input type="radio" class="btn-check" name="image_type" id="image_url" value="url">
              <label class="btn btn-outline-primary" for="image_url"><i class="bi bi-link-45deg"></i> URL Gambar</label>
            </div>

            <!-- File Upload -->
            <div id="image_file_section">
              <input type="file" name="image_file" class="form-control" accept="image/*">
              <small class="text-muted">Format: JPG, PNG, GIF (Max 5MB)</small>
            </div>

            <!-- URL Input -->
            <div id="image_url_section" style="display: none;">
              <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
              <small class="text-muted">Masukkan URL gambar lengkap</small>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Isi Artikel</label>
          <textarea name="content" class="form-control" rows="8" required>{{ old('content') }}</textarea>
          @error('content')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Terbit</label>
          <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at') }}" required>
          @error('published_at')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle"></i> Simpan Artikel</button>
          <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
        </div>
      </div>
    </div>
  </form>

  <script>
    document.getElementById('image_file').addEventListener('change', function() {
      document.getElementById('image_file_section').style.display = 'block';
      document.getElementById('image_url_section').style.display = 'none';
      document.querySelector('input[name="image_file"]').required = true;
      document.querySelector('input[name="image_url"]').required = false;
    });

    document.getElementById('image_url').addEventListener('change', function() {
      document.getElementById('image_file_section').style.display = 'none';
      document.getElementById('image_url_section').style.display = 'block';
      document.querySelector('input[name="image_file"]').required = false;
      document.querySelector('input[name="image_url"]').required = true;
    });
  </script>
@endsection
