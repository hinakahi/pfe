<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Résidence Si Oukli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url("/photo/7.jpg") center center / cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(26, 60, 94, 0.5);
            z-index: 0;
        }
        .register-card {
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        .register-logo { text-align: center; margin-bottom: 25px; }
        .register-logo h4 { color: #1a3c5e; font-weight: 700; margin-top: 10px; }
        .btn-register {
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
        }
        .btn-register:hover { opacity: 0.9; color: #fff; }
    </style>
</head>
<body>
<div class="register-card">
    <div class="register-logo">
        <img src="{{ asset('photo/mon_logo.jpg') }}" alt="Logo" style="width:80px; height:auto; border-radius:10px;">
        <h4>Inscription — Résidence Si Oukli</h4>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
        </div>
    @endif

  <form method="POST" action="{{ route('inscription.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-3">
        <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Prénom Nom" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Matricule <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
            <input type="text" name="matricule" class="form-control" value="{{ old('matricule') }}" placeholder="ETU2024001" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Photo de profil</label>
        <input type="file" name="photo" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="votre@email.com" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Téléphone <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-phone"></i></span>
            <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="05XXXXXXXX" pattern="[0-9]{10}" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', 'eye1')">
                <i class="bi bi-eye" id="eye1"></i>
            </button>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">Confirmer le mot de passe <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="password_confirmation" id="confirm_password" class="form-control" placeholder="••••••••" required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password', 'eye2')">
                <i class="bi bi-eye" id="eye2"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-register">
        <i class="bi bi-person-plus me-2"></i>Créer mon compte
    </button>
</form>
    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Déjà un compte ? Se connecter
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
</body>
</html>