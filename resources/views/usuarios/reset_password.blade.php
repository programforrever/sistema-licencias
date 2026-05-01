@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-key me-2"></i>Restablecer Contraseña</h2>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <i class="fas fa-user me-2"></i>
                <strong>{{ $usuario->name }}</strong> — {{ $usuario->email }}
            </div>
            <div class="card-body">
                <form action="{{ route('usuarios.update-password', $usuario) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nueva Contraseña</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Mínimo 6 caracteres" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation"
                            class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-save me-2"></i>Actualizar Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection