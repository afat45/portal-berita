@extends('layouts.public')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h3><i class="bi bi-dice-4"></i> Login ke Boardgame Hub</h3>
      <form method="post" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email') }}">
          @error('email')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control">
          @error('password')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" name="remember" class="form-check-input" id="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <button class="btn btn-primary">Login</button>
      </form>
    </div>
  </div>
@endsection
