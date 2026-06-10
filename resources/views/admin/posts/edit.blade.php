@extends('layouts.admin')

@section('content')
  <h2 class="fw-bold mb-4"><i class="bi bi-pencil"></i> Edit Artikel Game</h2>

  <form action="{{ route('admin.posts.update', $post) }}" method="post" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
      <div class="col-lg-8">
        <div class="mb-3">
          <label class="form-label">Judul Artikel</label>
          <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
          @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Kategori Game Utama</label>
          <select name="category_id" class="form-select" required>
            <option value="">-- Pilih Kategori Utama --</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" {{ $post->category_id == $c->id ? 'selected' : '' }}>{{ $c->nama_kategori }}</option>
            @endforeach
          </select>
          @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Kategori Tambahan (Opsional)</label>
          <small class="text-muted d-block mb-2">Pilih satu atau lebih kategori tambahan dengan menekan Ctrl (Windows) atau Cmd (Mac)</small>
          <select name="additional_categories[]" class="form-select" multiple size="5">
            @foreach($categories as $c)
              <option value="{{ $c->id }}" 
                {{ $post->categories->contains($c->id) && $c->id != $post->category_id ? 'selected' : '' }}>
                {{ $c->nama_kategori }}
              </option>
            @endforeach
          </select>
          <small class="text-muted">Kategori utama akan otomatis termasuk, tidak perlu dipilih lagi di sini</small>
          @error('additional_categories')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Author</label>
          <input type="text" name="author" class="form-control" value="{{ old('author', $post->author) }}" required>
          @error('author')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <!-- IMAGE SECTION: Toggle Upload vs URL -->
        <div class="card mb-3">
          <div class="card-header bg-light">
            <h6 class="mb-0">Gambar Artikel</h6>
          </div>
          <div class="card-body">
            <div class="btn-group w-100 mb-3" role="group">
              <input type="radio" class="btn-check" name="image_type" id="image_file" value="file" {{ !$post->image || str_starts_with($post->image, 'images/') ? 'checked' : '' }}>
              <label class="btn btn-outline-primary" for="image_file"><i class="bi bi-upload"></i> Upload File</label>

              <input type="radio" class="btn-check" name="image_type" id="image_url" value="url" {{ $post->image && str_starts_with($post->image, 'http') ? 'checked' : '' }}>
              <label class="btn btn-outline-primary" for="image_url"><i class="bi bi-link-45deg"></i> URL Gambar</label>
            </div>

            <!-- File Upload -->
            <div id="image_file_section" style="{{ str_starts_with($post->image, 'http') ? 'display: none;' : '' }}">
              <input type="file" name="image_file" class="form-control" accept="image/*">
              <small class="text-muted">Format: JPG, PNG, GIF (Max 5MB)</small>
              @if($post->image && str_starts_with($post->image, 'images/'))
                <div class="mt-2">
                  <img src="{{ asset('storage/'.$post->image) }}" style="height:120px;object-fit:cover;border-radius:4px;">
                </div>
              @endif
            </div>

            <!-- URL Input -->
            <div id="image_url_section" style="{{ $post->image && str_starts_with($post->image, 'http') ? '' : 'display: none;' }}">
              <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg" value="{{ $post->image && str_starts_with($post->image, 'http') ? $post->image : '' }}">
              <small class="text-muted">Masukkan URL gambar lengkap</small>
              @if($post->image && str_starts_with($post->image, 'http'))
                <div class="mt-2">
                  <img src="{{ $post->image }}" style="height:120px;object-fit:cover;border-radius:4px;">
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Isi Artikel</label>
          <textarea name="content" class="form-control" rows="8" required>{{ old('content', $post->content) }}</textarea>
          @error('content')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Terbit</label>
          <input type="datetime-local" name="published_at" class="form-control" value="{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('Y-m-d\TH:i') : '' }}" required>
          @error('published_at')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle"></i> Perbarui Artikel</button>
          <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
        </div>
      </div>
    </div>
  </form>

  <script>
    document.getElementById('image_file').addEventListener('change', function() {
      document.getElementById('image_file_section').style.display = 'block';
      document.getElementById('image_url_section').style.display = 'none';
    });

    document.getElementById('image_url').addEventListener('change', function() {
      document.getElementById('image_file_section').style.display = 'none';
      document.getElementById('image_url_section').style.display = 'block';
    });
  </script>
@endsection
