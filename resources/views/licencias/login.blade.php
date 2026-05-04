<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema ITSE M.A.A.C.D — Acceso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body { height: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* ── Contenedor raíz: toda la pantalla ── */
        .login-shell {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ════════════════════════════
           PANEL IZQUIERDO — Branding (80%)
        ════════════════════════════ */
        .panel-brand {
            flex: 0 0 80%;
            background: linear-gradient(155deg, #0c5412 0%, #051508 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .deco-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .brand-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .logo-ring {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.09);
            border: 2.5px solid rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .logo-ring img {
            width: 138px;
            height: 138px;
            object-fit: contain;
            border-radius: 50%;
        }

        .brand-title {
            font-size: 15px;
            font-weight: 700;
            color: rgba(255,255,255,0.92);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .brand-sub {
            font-size: 13px;
            color: rgba(255,255,255,0.42);
            line-height: 1.7;
            max-width: 340px;
            margin-bottom: 1.75rem;
        }

        .sys-badge {
            display: inline-block;
            background: rgba(18,150,29,0.28);
            border: 1px solid rgba(18,150,29,0.5);
            color: #80e887;
            font-size: 12px;
            padding: 7px 24px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            margin-bottom: 2rem;
        }

        .brand-divider {
            width: 40px;
            height: 2px;
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
            margin-bottom: 1.5rem;
        }

        .chips { display: flex; gap: 14px; }

        .chip {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 11px 22px;
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            text-align: center;
            line-height: 1.5;
        }

        .chip i {
            display: block;
            font-size: 18px;
            color: rgba(255,255,255,0.35);
            margin-bottom: 5px;
        }

        /* ════════════════════════════
           PANEL DERECHO — Formulario (20%)
        ════════════════════════════ */
        .panel-login {
            flex: 0 0 20%;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 2.2rem;
            border-left: 1px solid #eee;
        }

        .login-title {
            font-size: 32px;
            font-weight: 700;
            color: #0f2812;
            margin-bottom: 8px;
        }

        .login-sub {
            font-size: 16px;
            color: #666;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .accent-bar {
            width: 50px;
            height: 4px;
            background: #12961d;
            border-radius: 3px;
            margin-bottom: 2rem;
        }

        /* Campos */
        .field-label {
            font-size: 13px;
            font-weight: 700;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        .input-wrap {
            display: flex;
            align-items: center;
            border: 2px solid #d0d0d0;
            border-radius: 12px;
            background: #f9fbf9;
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-wrap:focus-within {
            border-color: #12961d;
            box-shadow: 0 0 0 4px rgba(18,150,29,0.15);
        }

        .i-icon {
            width: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #12961d;
            font-size: 18px;
            flex-shrink: 0;
        }

        .input-wrap input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 16px 12px 16px 0;
            font-size: 18px;
            color: #222;
            outline: none;
        }

        .input-wrap input::placeholder { 
            color: #bbb;
            font-size: 16px;
        }

        .eye-toggle {
            background: none;
            border: none;
            padding: 0 14px;
            color: #999;
            font-size: 16px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .eye-toggle:hover { color: #12961d; }

        /* Opciones — apiladas para que quepan en 20% */
        .opts-row {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 1.8rem;
        }

        .check-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #555;
            cursor: pointer;
            user-select: none;
            font-weight: 500;
        }

        .check-label input[type="checkbox"] {
            accent-color: #12961d;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 14px;
            color: #12961d;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover { 
            text-decoration: underline; 
            color: #0a5c12; 
        }

        /* Botón principal */
        .btn-ingresar {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #12961d 0%, #084010 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.6px;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-bottom: 1.2rem;
        }

        .btn-ingresar:hover { 
            opacity: 0.87; 
        }
        
        .btn-ingresar:active {
            transform: scale(0.98);
        }

        /* Botones secundarios — apilados */
        .sec-btns { 
            display: flex; 
            flex-direction: column; 
            gap: 10px; 
        }

        .sec-btns a {
            text-align: center;
            padding: 12px;
            font-size: 14px;
            color: #555;
            border: 2px solid #d0d0d0;
            border-radius: 10px;
            background: #f8f8f8;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }

        .sec-btns a:hover {
            background: #eaf5eb;
            color: #12961d;
            border-color: #12961d;
        }

        /* Alertas */
        .alert-login {
            font-size: 14px;
            border-radius: 10px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 1.5rem;
        }

        .alert-login.error  { background: #fff0f0; color: #c62828; }
        .alert-login.status { background: #f0fff2; color: #1b5e20; }

        /* Footer */
        .login-footer {
            margin-top: 1.75rem;
            font-size: 13px;
            color: #ccc;
            text-align: center;
            line-height: 1.7;
        }

        /* ── Responsivo ── */
        @media (max-width: 768px) {
            .login-shell   { flex-direction: column-reverse; }
            .panel-brand   { flex: none; width: 100%; min-height: 300px; padding: 2.5rem 1.5rem; }
            .panel-login   { flex: none; width: 100%; padding: 2.5rem 2rem; border-left: none; border-top: 1px solid #eee; }
            .login-title   { font-size: 28px; }
            .login-sub     { font-size: 14px; }
            .field-label   { font-size: 12px; }
            .input-wrap input { font-size: 16px; padding: 14px 12px 14px 0; }
            .btn-ingresar  { font-size: 15px; padding: 14px; }
            .sec-btns a    { font-size: 13px; padding: 11px; }
            .logo-ring     { width: 110px; height: 110px; }
            .logo-ring img { width: 84px; height: 84px; }
            .chips         { flex-wrap: wrap; justify-content: center; }
        }

        /* Ocultar íconos nativos del navegador */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none !important; }
    .panel-brand {
    flex: 0 0 70% !important;
    background: linear-gradient(155deg, #0c5412 0%, #051508 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 2.5rem;
    position: relative;
    overflow: hidden;
}
    </style>
</head>
<body>

<div class="login-shell">

    {{-- ══ PANEL IZQUIERDO 80%: Branding ══ --}}
    <div class="panel-brand">

        <div class="deco-ring" style="width:700px;height:700px;top:-220px;left:-220px;"></div>
        <div class="deco-ring" style="width:400px;height:400px;bottom:-120px;right:-120px;"></div>
        <div class="deco-ring" style="width:230px;height:230px;top:60px;right:100px;"></div>

        <div class="brand-inner">

            <div class="logo-ring">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo Municipalidad Distrital">
            </div>

            <p class="brand-title">
                Municipalidad Distrital<br>
                Andrés Avelino Cáceres
            </p>

            <p class="brand-sub">
                Dorregaray — Gestión municipal transparente, eficiente y al servicio del ciudadano.
            </p>

            <span class="sys-badge">Sistema ITSE M.A.A.C.D</span>

            <div class="brand-divider"></div>

            <div class="chips">
                <div class="chip">
                    <i class="fas fa-landmark"></i>Municipal
                </div>
                <div class="chip">
                    <i class="fas fa-shield-alt"></i>Seguro
                </div>
                <div class="chip">
                    <i class="fas fa-file-alt"></i>Trámites
                </div>
            </div>

        </div>
    </div>{{-- /panel-brand --}}

    {{-- ══ PANEL DERECHO 20%: Formulario ══ --}}
    <div class="panel-login">

        <p class="login-title">Bienvenido</p>
        <p class="login-sub">Accede al sistema</p>
        <div class="accent-bar"></div>

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="alert-login error">
                <i class="fas fa-exclamation-circle me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Mensaje de estado --}}
        @if(session('status'))
            <div class="alert-login status">
                <i class="fas fa-check-circle me-1"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="field-label">Correo electrónico</div>
            <div class="input-wrap">
                <div class="i-icon"><i class="fas fa-envelope"></i></div>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="correo@gmail.com"
                    required
                    autofocus
                    autocomplete="username"
                >
            </div>

            {{-- Contraseña --}}
            <div class="field-label">Contraseña</div>
            <div class="input-wrap">
                <div class="i-icon"><i class="fas fa-lock"></i></div>
                <input
                    type="password"
                    name="password"
                    id="passwordInput"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
                <button type="button" class="eye-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>

            {{-- Opciones --}}
            <div class="opts-row">
                <label class="check-label">
                    <input type="checkbox" name="remember"> Mantener sesión activa
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        ¿Olvidó su contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-ingresar">
                <i class="fas fa-sign-in-alt me-1"></i>INGRESAR
            </button>
        </form>

        <div class="sec-btns">
            <a href="javascript:history.back()">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
            <a href="{{ route('solicitudes.formulario') }}">
                <i class="fas fa-plus me-1"></i>Nuevo trámite
            </a>
        </div>

        <div class="login-footer">
            Sistema de Certificados ITSE<br>
            Realizado por Michael Illanes &copy; {{ date('Y') }}
        </div>

    </div>{{-- /panel-login --}}

</div>{{-- /login-shell --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
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
