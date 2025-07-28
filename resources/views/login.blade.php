<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - SDIT Arrahmah Lumajang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>
<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4">
          <div class="card-body">

            <div class="text-center">
              <img src="https://via.placeholder.com/50" alt="Logo" class="logo rounded-circle">
              <h4 class="mb-3">Selamat Datang</h4>
              <p class="text-muted small-text mb-4">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
              </div>

              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label small-text" for="remember">
                    Ingat saya
                  </label>
                </div>
                <a href="#" class="small-text text-decoration-none text-primary">Lupa sandi?</a>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
              </div>
            </form>

            <p class="text-center mt-4 mb-0 small-text">
              Belum punya akun? <a href="#" class="text-decoration-none text-primary fw-semibold">Daftar</a>
            </p>

          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
