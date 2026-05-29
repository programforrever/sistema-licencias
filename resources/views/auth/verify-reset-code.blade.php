<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código — Municipalidad</title>
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

        .password-strength {
            font-size: 12px;
            margin-top: 6px;
            padding: 8px;
            border-radius: 6px;
            display: none;
        }

        .password-weak {
            background: #ffe6e6;
            color: #dc3545;
        }

        .password-medium {
            background: #fff3cd;
            color: #856404;
        }

        .password-strong {
            background: #d4edda;
            color: #155724;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 10px;
        }

        .loading-spinner i {
            font-size: 20px;
            color: #12961d;
        }

        @media (max-width: 768px) {
            .login-card { padding: 25px 20px; }
            .login-header img { width: 70px; }
            .login-header h5 { font-size: 13px; }
            .form-control { font-size: 13px; }
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
            <i class="fas fa-lock me-2" style="color:#12961d;"></i>
            Ingresa el código que recibiste en tu correo
        </div>

        {{-- Errores --}}
        <div id="error-container" class="alert alert-danger mb-3" style="font-size:13px; border-radius:8px; border:none; background:#fff0f0; color:#dc3545; display:none;">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span id="error-message"></span>
        </div>

        {{-- Mensaje de éxito desde envío de código --}}
        @if(session('status'))
            <div class="alert alert-success mb-3" style="font-size:13px; border-radius:8px; border:none; background:#d4edda; color:#155724;">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        {{-- PASO 1: VALIDAR CÓDIGO --}}
        <div id="step1-code">
            <p class="step-text">
                <i class="fas fa-key me-1"></i><strong>Paso 1 de 2:</strong> Verificar código
            </p>

            <input type="hidden" id="email-hidden" value="{{ session('email') }}">

            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" id="email-input" class="form-control"
                        value="{{ session('email') }}"
                        placeholder="correo@municipalidad.gob.pe"
                        readonly>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Código de 6 dígitos</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    <input type="text" id="code-input" class="form-control"
                        inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        maxlength="6"
                        placeholder="000000"
                        required>
                </div>
            </div>

            <button type="button" class="btn btn-login" onclick="validateCode()">
                <i class="fas fa-check me-2"></i>VERIFICAR CÓDIGO
            </button>

            <div class="loading-spinner" id="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i> Verificando...
            </div>
        </div>

        {{-- PASO 2: ACTUALIZAR CONTRASEÑA (oculto inicialmente) --}}
        <div id="step2-password" style="display:none;">
            <p class="step-text">
                <i class="fas fa-lock me-1"></i><strong>Paso 2 de 2:</strong> Establecer nueva contraseña
            </p>

            <form method="POST" action="{{ route('password.update-code') }}" id="password-form">
                @csrf

                <input type="hidden" id="email-field" name="email" value="{{ session('email') }}">
                <input type="hidden" id="code-field" name="code">

                <div class="mb-3">
                    <label class="form-label">Nueva contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Mínimo 8 caracteres"
                            required
                            onkeyup="checkPasswordStrength()">
                    </div>
                    <div id="password-strength" class="password-strength"></div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirmar contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Repite tu nueva contraseña"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-save me-2"></i>GUARDAR CONTRASEÑA
                </button>
            </form>
        </div>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fas fa-arrow-left me-1"></i> Volver al inicio de sesión
        </a>
    </div>
</div>

<script>
    function validateCode() {
        const email = document.getElementById('email-input').value;
        const code = document.getElementById('code-input').value;
        const loadingSpinner = document.getElementById('loading-spinner');
        const errorContainer = document.getElementById('error-container');

        if (code.length !== 6) {
            showError('El código debe tener 6 dígitos');
            return;
        }

        loadingSpinner.style.display = 'block';
        errorContainer.style.display = 'none';

        fetch('{{ route("password.validate-code") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify({ email: email, code: code })
        })
        .then(response => response.json())
        .then(data => {
            loadingSpinner.style.display = 'none';
            if (data.success) {
                // Guardar código en campo oculto
                document.getElementById('code-field').value = code;
                document.getElementById('email-field').value = email;

                // Cambiar a paso 2
                document.getElementById('step1-code').style.display = 'none';
                document.getElementById('step2-password').style.display = 'block';
            } else {
                showError(data.message || 'Error al validar el código');
            }
        })
        .catch(error => {
            loadingSpinner.style.display = 'none';
            showError('Error: ' + error.message);
        });
    }

    function showError(message) {
        const errorContainer = document.getElementById('error-container');
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = message;
        errorContainer.style.display = 'block';
    }

    function checkPasswordStrength() {
        const password = document.getElementById('password').value;
        const strengthDiv = document.getElementById('password-strength');

        if (password.length === 0) {
            strengthDiv.style.display = 'none';
            return;
        }

        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;

        strengthDiv.style.display = 'block';
        
        if (strength < 3) {
            strengthDiv.className = 'password-strength password-weak';
            strengthDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i><strong>Contraseña débil.</strong> Usa mayúsculas, números y caracteres especiales.';
        } else if (strength < 5) {
            strengthDiv.className = 'password-strength password-medium';
            strengthDiv.innerHTML = '<i class="fas fa-info-circle me-1"></i><strong>Contraseña media.</strong> Considera agregar más variedad.';
        } else {
            strengthDiv.className = 'password-strength password-strong';
            strengthDiv.innerHTML = '<i class="fas fa-check-circle me-1"></i><strong>Contraseña fuerte.</strong> Excelente seguridad.';
        }
    }

    // Permitir Enter para validar código
    document.getElementById('code-input')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') validateCode();
    });
</script>

</body>
</html>
