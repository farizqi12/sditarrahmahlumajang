<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - SDIT Arrahmah Lumajang</title>

  <!-- ✅ Font Montserrat dari Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      background: linear-gradient(135deg, #ffffff 0%, #1e3a8a 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Montserrat', sans-serif;
      margin: 0;
      padding: 1rem;
    }

    .card {
      border: none;
      border-radius: 1rem;
      padding: 2rem;
      box-shadow: 0 10px 35px rgba(0, 0, 0, 0.1);
      background-color: #ffffff;
      z-index: 10;
      width: 100%;
      max-width: 420px;
      animation: fadeIn 0.5s ease-in-out;
    }

    .form-control, .btn {
      font-family: 'Montserrat', sans-serif;
      font-size: 0.95rem;
      border-radius: 0.5rem;
    }

    .logo {
      width: 50px;
      margin-bottom: 1rem;
    }

    .small-text {
      font-size: 0.875rem;
    }

    .form-label {
      font-weight: 500;
    }

    @media (max-width: 576px) {
      .card {
        padding: 1.5rem;
      }

      h4 {
        font-size: 1.3rem;
      }

      .btn {
        font-size: 0.9rem;
      }

      .form-control {
        font-size: 0.9rem;
      }

      .logo {
        width: 40px;
      }
    }

    @keyframes toastSlideIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    @keyframes toastSlideOut {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(100%); opacity: 0; }
    }

    .toast.show {
      animation: toastSlideIn 0.3s forwards;
    }

    .toast.hiding {
      animation: toastSlideOut 0.3s forwards;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    {{-- ini component saya --}}
    <x-notif></x-notif>

    <div class="d-flex justify-content-center align-items-center min-vh-100">
      <div class="card">
        <div class="card-body">
          <div class="text-center">
            <img src="https://via.placeholder.com/50" alt="Logo" class="logo rounded-circle">
            <h4 class="mb-3">Selamat Datang</h4>
            <p class="text-muted small-text mb-4">Masuk ke akun Anda untuk melanjutkan</p>
          </div>

          <form method="POST" action="#">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="email" name="email"
                class="form-control"
                value="" required autofocus>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Kata Sandi</label>
              <input type="password" id="password" name="password"
                class="form-control" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small-text" for="remember">Ingat saya</label>
              </div>
              <a href="#" class="small-text text-decoration-none text-primary">Lupa sandi?</a>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
            </div>
          </form>

          <p class="text-center mt-4 mb-0 small-text">
            Belum punya akun?
            <a href="#" class="text-decoration-none text-primary fw-semibold">Daftar</a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>