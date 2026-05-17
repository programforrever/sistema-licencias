@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root {
        --brand:       #2563eb;
        --brand-light: #eff6ff;
        --brand-dark:  #1e40af;
        --surface:     #ffffff;
        --bg:          #f8fafc;
        --border:      #e2e8f0;
        --text-main:   #0f172a;
        --text-muted:  #64748b;
        --radius-sm:   6px;
        --radius-md:   10px;
        --radius-lg:   14px;
        --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:   0 4px 16px rgba(0,0,0,.08);
    }
    body.dark-mode {
        --surface:    #1e293b;
        --bg:         #0f172a;
        --border:     #334155;
        --text-main:  #f1f5f9;
        --text-muted: #94a3b8;
        --brand-light:#1e3a5f;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .page-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .5rem 1rem;
        background: var(--bg);
        color: var(--text-muted);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-back:hover {
        background: var(--border);
        color: var(--text-main);
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }
    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .detail-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .card-head {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .85rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg);
    }
    .ch-icon {
        width: 28px;
        height: 28px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .78rem;
        background: #eff6ff;
        color: #2563eb;
    }
    .card-head h6 {
        margin: 0;
        font-size: .95rem;
        font-weight: 700;
        color: var(--text-main);
    }
    .card-body {
        padding: 1.25rem;
    }

    .data-row {
        display: flex;
        justify-content: space-between;
        padding: .75rem 0;
        border-bottom: 1px solid var(--border);
    }
    .data-row:last-child {
        border-bottom: none;
    }
    .dr-label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: .85rem;
    }
    .dr-value {
        color: var(--text-main);
        font-weight: 500;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        display: block;
        font-size: .85rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: .5rem;
        text-transform: uppercase;
        letter-spacing: .02em;
    }
    .form-input, .form-file {
        width: 100%;
        padding: .65rem .85rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: .9rem;
        font-family: inherit;
        transition: border-color .2s;
    }
    .form-input:focus, .form-file:focus {
        outline: none;
        border-color: var(--brand);
        box-shadow: 0 0 0 3px var(--brand-light);
    }
    .form-help {
        font-size: .8rem;
        color: var(--text-muted);
        margin-top: .4rem;
    }
    .form-error {
        font-size: .8rem;
        color: #dc2626;
        margin-top: .4rem;
    }

    .info-box {
        background: #eff6ff;
        border: 1px solid #bae6fd;
        border-radius: var(--radius-md);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .info-box h4 {
        margin: 0 0 .75rem;
        font-size: .95rem;
        font-weight: 700;
        color: #0c4a6e;
    }
    .info-box ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    .info-box li {
        font-size: .85rem;
        color: #0c4a6e;
        margin-bottom: .4rem;
    }

    .signature-preview {
        background: #fafafa;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1rem;
        text-align: center;
        margin-bottom: 1rem;
    }
    .signature-preview img {
        max-height: 120px;
        object-fit: contain;
    }
    .signature-date {
        font-size: .75rem;
        color: var(--text-muted);
        margin-top: .75rem;
    }

    .btn-group {
        display: flex;
        gap: .75rem;
    }
    .btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        padding: .65rem 1rem;
        border-radius: var(--radius-md);
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-primary {
        background: var(--brand);
        color: #fff;
    }
    .btn-primary:hover {
        background: var(--brand-dark);
        transform: translateY(-1px);
    }
    .btn-secondary {
        background: var(--bg);
        color: var(--text-muted);
        border: 1px solid var(--border);
    }
    .btn-secondary:hover {
        background: var(--border);
        color: var(--text-main);
    }
</style>

<div style="padding: 1.5rem; background: var(--bg); min-height: 100vh;">
    <div style="max-width: 700px; margin: 0 auto;">
        {{-- Header --}}
        <div class="page-header">
            <h1 class="page-title">
                @if($signature)
                    Actualizar Firma
                @else
                    Cargar Firma
                @endif
            </h1>
            <a href="{{ route('admin.signatures.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        {{-- Alertas --}}
        @if($message = session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ $message }}</span>
            </div>
        @endif

        @if($message = session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
            </div>
        @endif

        {{-- Información del usuario --}}
        <div class="detail-card">
            <div class="card-head">
                <span class="ch-icon"><i class="fas fa-user"></i></span>
                <h6>Información del Usuario</h6>
            </div>
            <div class="card-body">
                <div class="data-row">
                    <span class="dr-label">Nombre</span>
                    <span class="dr-value">{{ $user->name }}</span>
                </div>
                <div class="data-row">
                    <span class="dr-label">Email</span>
                    <span class="dr-value">{{ $user->email }}</span>
                </div>
            </div>
        </div>

        {{-- Firma actual --}}
        @if($firmaUrl)
        <div class="detail-card">
            <div class="card-head">
                <span class="ch-icon" style="background: #fef3c7; color: #92400e;"><i class="fas fa-pen-fancy"></i></span>
                <h6>Firma Actual</h6>
            </div>
            <div class="card-body">
                <div class="signature-preview">
                    <img src="{{ $firmaUrl }}" alt="Firma actual" style="max-height: 100px;">
                    <div class="signature-date">
                        Cargada: {{ $signature->uploaded_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Formulario de carga --}}
        <div class="detail-card">
            <div class="card-head">
                <span class="ch-icon" style="background: #dcfce7; color: #166534;"><i class="fas fa-upload"></i></span>
                <h6>{{ $signature ? 'Actualizar' : 'Cargar' }} Nueva Firma</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.signatures.store', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="firma" class="form-label">Seleccionar Imagen</label>
                        <input type="file" 
                               id="firma" 
                               name="firma" 
                               accept="image/jpeg,image/png,image/jpg" 
                               required
                               class="form-file">
                        <p class="form-help">Formatos permitidos: JPG, PNG (máximo 5MB)</p>
                        @error('firma')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Instrucciones --}}
                    <div class="info-box">
                        <h4>Recomendaciones</h4>
                        <ul>
                            <li>La firma debe estar sobre fondo transparente</li>
                            <li>Preferiblemente formato PNG</li>
                            <li>Tamaño mínimo recomendado: 200×100 píxeles</li>
                            <li>La imagen será almacenada en storage/signatures/</li>
                        </ul>
                    </div>

                    {{-- Botones --}}
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i>
                            {{ $signature ? 'Actualizar' : 'Cargar' }}
                        </button>
                        <a href="{{ route('admin.signatures.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
