<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Résidence Si Oukli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background: url("/photo/7.jpg") center center / cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            box-sizing: border-box;
        }

body::before {
    content: '';
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    z-index: 0;
}

        .login-card {
    background: #fff;
    border-radius: 16px;
    padding: 40px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    position: relative;
    z-index: 1;
}
   @media (max-width: 400px) {
            .login-card {
                padding: 24px;
            }
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo i {
            font-size: 3rem;
            color: #1a3c5e;
        }
        .login-logo h4 {
            color: #1a3c5e;
            font-weight: 700;
            margin-top: 10px;
        }
        .btn-login {
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
        }
        .btn-login:hover { opacity: 0.9; color: #fff; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
           <img src="{{ asset('photo/mon_logo.jpg') }}" alt="Logo" style="width:100px; height:auto; border-radius:10px;">
           <h4>Résidence Si Oukli</h4>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="votre@email.com" required>
                </div>
            </div>
          <div class="mb-4">
    <label class="form-label fw-semibold">Mot de passe</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock"></i></span>
        <input type="password" name="password" id="password" class="form-control"
               placeholder="••••••••" required>
        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
            <i class="bi bi-eye" id="eyeIcon"></i>
        </button>
    </div>
</div>
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
            </button>
        </form>

       <div class="text-center mt-3">
    <a href="{{ route('password.request') }}" class="text-muted small">
        Mot de passe oublié ?
    </a>
</div>
<div class="text-center mt-2">
    <a href="{{ route('inscription') }}" class="text-muted small">
        <i class="bi bi-person-plus me-1"></i>Pas encore de compte ? S'inscrire
    </a>
</div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>
</body>
</html>