@extends('layouts.app')

@section('content')
<h2 class="mb-4"><i class="fas fa-file-excel me-2"></i>Importación Masiva</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fas fa-users me-2"></i>Importar Usuarios
            </div>
            <div class="card-body">
                <p class="text-muted">El archivo Excel debe tener estas columnas:</p>
                <code>dni_ruc | tipo_persona | nombres_razon_social | direccion | telefono | email</code>
                <hr>
                <form action="{{ route('importar.contribuyentes') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Seleccionar archivo Excel/CSV</label>
                        <input type="file" name="archivo" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-2"></i>Importar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-briefcase me-2"></i>Importar Actividades Económicas
            </div>
            <div class="card-body">
                <p class="text-muted">El archivo Excel debe tener estas columnas:</p>
                <code>codigo | descripcion | categoria | tasa_derecho</code>
                <hr>
                <form action="{{ route('importar.actividades') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Seleccionar archivo Excel/CSV</label>
                        <input type="file" name="archivo" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Importar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-dark text-white">
        <i class="fas fa-download me-2"></i>Plantillas de ejemplo
    </div>
    <div class="card-body">
        <p>Descarga las plantillas de ejemplo para llenar correctamente:</p>
        <a href="{{ route('importar.plantilla-contribuyentes') }}" class="btn btn-success">
    <i class="fas fa-download me-2"></i>Plantilla Titulares
</a>

<a href="{{ route('importar.plantilla-actividades') }}" class="btn btn-success">
    <i class="fas fa-download me-2"></i>Plantilla Actividades
</a>
    </div>
</div>
@endsection