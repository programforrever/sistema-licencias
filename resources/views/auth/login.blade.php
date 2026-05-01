<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema ITSE M.A.A.C.D</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: linear-gradient(180deg, #12961d 0%, #0f2812 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            padding: 40px 35px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header img {
            width: 90px;
            height: auto;
            margin-bottom: 15px;
        }

        .login-header h5 {
            font-size: 15px;
            font-weight: 700;
            color: #0f2812;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .login-header p {
            font-size: 12px;
            color: #6c757d;
            margin: 0;
        }

        .divider {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, #12961d, #0f2812);
            border-radius: 2px;
            margin-bottom: 25px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #12961d;
            box-shadow: 0 0 0 3px rgba(18,150,29,0.15);
        }

        .input-group-text {
            background: #f8f9fa;
            border-color: #ddd;
            border-radius: 8px 0 0 8px;
            color: #12961d;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        .btn-login {
            background: linear-gradient(135deg, #12961d, #0f2812);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-size: 14px;
            font-weight: 600;
            width: 100%;
            letter-spacing: 0.5px;
            transition: opacity 0.2s;
        }

        .btn-login:hover {
            opacity: 0.9;
            color: white;
        }

        .forgot-link {
            font-size: 12px;
            color: #12961d;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #0f2812;
        }

        .alert-danger {
            font-size: 13px;
            border-radius: 8px;
            border: none;
            background: #fff0f0;
            color: #dc3545;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: rgba(255,255,255,0.6);
        }

        /* Ocultar ícono nativo del navegador */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear,
        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="search"]::-webkit-search-cancel-button {
            display: none !important;
        }
    </style>
</head>
<body>

<div>
    

    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo Municipalidad">
            <h5>Municipalidad Distrital</h5>
            <p>Andrés Avelino Cáceres Dorregaray</p>
        </div>

        <hr class="divider">

        {{-- Errores --}}
        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Status (ej: contraseña reseteada) --}}
        @if(session('status'))
            <div class="alert alert-success mb-3" style="font-size:13px; border-radius:8px;">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="correo@gmail.com"
                        required autofocus autocomplete="username">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="passwordInput"
                        class="form-control @error('password') is-invalid @enderror"
                        required autocomplete="current-password" placeholder="••••••••">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                        style="border-radius:0 8px 8px 0; border-color:#ddd;"
                        onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <label class="d-flex align-items-center gap-2" style="font-size:13px; cursor:pointer;">
                    <input type="checkbox" name="remember" class="form-check-input m-0">
                    Mantener sesión activa
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        ¿Olvidó su contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>INICIAR SESIÓN
            </button>
        </form>
    </div>


    <div class="d-flex justify-content-between mt-3" style="width:100%; max-width:420px;">
        <a href="javascript:history.back()" class="btn btn-sm" style="background:rgba(7,7,77,0.55); color:white; border:1px solid rgba(39,3,93,0.3);">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
        <a href="{{ route('solicitudes.formulario') }}" class="btn btn-sm" style="background:rgba(7,7,77,0.55); color:white; border:1px solid rgba(39,3,93,0.3);">
            <i class="fas fa-plus me-2"></i>Nuevo Trámite
        </a>
    </div>
    <div class="footer-text">
        Sistema de Certificados ITSE Realizado por MICHAEL ILLANES &copy; {{ date('Y') }}
    </div>
</div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>