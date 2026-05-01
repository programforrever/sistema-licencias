@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i>Editar Titular</h2>
    <a href="{{ route('contribuyentes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('contribuyentes.update', $contribuyente) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">DNI / RUC</label>
                    <input type="text" name="dni_ruc" class="form-control @error('dni_ruc') is-invalid @enderror" value="{{ $contribuyente->dni_ruc }}" required>
                    @error('dni_ruc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Persona</label>
                    <select name="tipo_persona" class="form-select" required>
                        <option value="natural" {{ $contribuyente->tipo_persona == 'natural' ? 'selected' : '' }}>Natural</option>
                        <option value="juridica" {{ $contribuyente->tipo_persona == 'juridica' ? 'selected' : '' }}>Jurídica</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Nombres / Razón Social</label>
                    <input type="text" name="nombres_razon_social" class="form-control @error('nombres_razon_social') is-invalid @enderror" value="{{ $contribuyente->nombres_razon_social }}" required>
                    @error('nombres_razon_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ $contribuyente->direccion }}" required>
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ $contribuyente->telefono }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $contribuyente->email }}">
                </div>
            </div>
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save me-2"></i>Actualizar Contribuyente
            </button>
        </form>
    </div>
</div>
@endsection