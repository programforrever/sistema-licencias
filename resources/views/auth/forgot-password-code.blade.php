<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña con Código — Municipalidad</title>
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
            margin-bottom: 25px;
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
            margin-bottom: 20px;
        }

        .info-box {
            background: #f0faf1;
            border-left: 4px solid #12961d;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 13px;
            color: #555;
            margin-bottom: 20px;
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

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #12961d;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
            color: #0f2812;
        }

        .step-text {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 15px;
            text-align: center;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: rgba(255,255,255,0.6);
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

        <div class="info-box">
            <i class="fas fa-info-circle me-2" style="color:#12961d;"></i>
            Ingresa tu correo para recibir un código en tu bandeja de entrada
        </div>

        {{-- Mensaje de éxito --}}
        @if(session('status'))
            <div class="alert alert-success mb-3" style="font-size:13px; border-radius:8px; border:none; background:#f0faf1; color:#12961d;">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        {{-- Errores --}}
        @if($errors->any())
            <div class="alert alert-danger mb-3" style="font-size:13px; border-radius:8px; border:none; background:#fff0f0; color:#dc3545;">
                <i class="fas fa-exclamation-circle me-2"></i>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <p class="step-text">
            <i class="fas fa-envelope-open-text me-1"></i><strong>Paso 1 de 2:</strong> Solicitar código
        </p>

        <form method="POST" action="{{ route('password.send-code') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">Correo electrónico registrado</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="correo@municipalidad.gob.pe"
                        required autofocus>
                </div>
                @error('email')
                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-send me-2"></i>ENVIAR CÓDIGO
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fas fa-arrow-left me-1"></i> Volver al inicio de sesión
        </a>
    </div>
</div>

</body>
</html>
