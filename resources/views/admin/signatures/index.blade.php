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
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
    }
    .page-title .icon-wrap {
        width: 36px; height: 36px;
        background: var(--brand-light);
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        color: var(--brand); font-size: .9rem;
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        font-weight: 500;
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

    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .user-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        transition: all .2s;
    }
    .user-card:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--brand-light);
    }

    .user-info {
        margin-bottom: 1rem;
    }
    .user-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 .25rem;
    }
    .user-email {
        font-size: .85rem;
        color: var(--text-muted);
        margin: 0;
    }

    .signature-section {
        padding: 1rem;
        border-radius: var(--radius-md);
        margin-bottom: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .signature-section.has-sig {
        background: #fafafa;
        border: 1px solid var(--border);
    }
    .signature-section.no-sig {
        background: #fffbeb;
        border: 1px solid #fde68a;
    }

    .signature-preview {
        max-height: 60px;
        object-fit: contain;
        margin: .5rem 0;
    }

    .sig-label {
        font-size: .75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: .5rem;
    }
    .sig-text {
        font-size: .9rem;
        color: var(--text-main);
        margin: 0;
    }
    .sig-date {
        font-size: .75rem;
        color: var(--text-muted);
        margin-top: .5rem;
    }

    .btn-group {
        display: flex;
        gap: .75rem;
    }
    .btn-group-item {
        flex: 1;
    }

    .btn {
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
        width: 100%;
        text-align: center;
    }
    .btn-primary {
        background: var(--brand);
        color: #fff;
    }
    .btn-primary:hover {
        background: var(--brand-dark);
        transform: translateY(-1px);
    }
    .btn-danger {
        background: #ef4444;
        color: #fff;
    }
    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }
    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: .5;
    }
    .empty-state-text {
        font-size: 1.1rem;
        margin: 0;
    }
</style>

<div style="padding: 1.5rem; background: var(--bg); min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        {{-- Header --}}
        <div class="page-header">
            <h1 class="page-title">
                <span class="icon-wrap"><i class="fas fa-signature"></i></span>
                Gestionar Firmas Digitales
            </h1>
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

        {{-- Grid de usuarios --}}
        <div class="users-grid">
            @forelse($users as $user)
                <div class="user-card">
                    <div class="user-info">
                        <h2 class="user-name">{{ $user->name }}</h2>
                        <p class="user-email">{{ $user->email }}</p>
                    </div>

                    {{-- Sección de firma --}}
                    @if($user->signature && Storage::disk('public')->exists($user->signature->firma_path))
                        <div class="signature-section has-sig">
                            <p class="sig-label">Firma Registrada</p>
                            <img src="{{ url('storage/' . $user->signature->firma_path) }}" 
                                 alt="Firma de {{ $user->name }}" 
                                 class="signature-preview">
                            <p class="sig-date">
                                Cargada: {{ $user->signature->uploaded_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    @else
                        <div class="signature-section no-sig">
                            <p class="sig-label">Sin firma registrada</p>
                            <p class="sig-text">Este usuario aún no tiene firma</p>
                        </div>
                    @endif

                    {{-- Botones de acción --}}
                    <div class="btn-group">
                        <div class="btn-group-item">
                            <a href="{{ route('admin.signatures.edit', $user) }}" class="btn btn-primary">
                                <i class="fas fa-{{ $user->signature ? 'pen' : 'upload' }}"></i>
                                {{ $user->signature ? 'Actualizar' : 'Cargar' }}
                            </a>
                        </div>
                        @if($user->signature)
                            <div class="btn-group-item">
                                <form action="{{ route('admin.signatures.destroy', $user) }}" method="POST" 
                                      onsubmit="return confirm('¿Realmente deseas eliminar la firma de {{ addslashes($user->name) }}?');"
                                      style="display: contents;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1;">
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="fas fa-users"></i></div>
                        <p class="empty-state-text">No hay usuarios en el sistema</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
