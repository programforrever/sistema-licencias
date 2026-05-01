@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-edit me-2"></i>Editar Usuario</h2>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre completo</label>
                    <input type="text" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ $usuario->name }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Correo electrónico</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ $usuario->email }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nueva contraseña <small class="text-muted">(dejar vacío para no cambiar)</small></label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Rol</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">-- Seleccionar rol --</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->name }}"
                                {{ $usuario->hasRole($rol->name) ? 'selected' : '' }}>
                                {{ ucfirst($rol->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save me-2"></i>Actualizar Usuario
            </button>
        </form>
    </div>
</div>
@endsection