@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>Nueva Actividad Económica</h2>
    <a href="{{ route('actividades.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('actividades.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Código</label>
                    <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{ old('codigo') }}" required>
                    @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion') }}" required>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Categoría</label>
                    <input type="text" name="categoria" class="form-control" value="{{ old('categoria') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tasa de Derecho (S/)</label>
                    <input type="number" name="tasa_derecho" step="0.01" min="0" class="form-control @error('tasa_derecho') is-invalid @enderror" value="{{ old('tasa_derecho', 0) }}" required>
                    @error('tasa_derecho')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Guardar Actividad
            </button>
        </form>
    </div>
</div>
@endsection