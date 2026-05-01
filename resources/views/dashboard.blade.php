@extends('layouts.app')

@section('content')
<h2 class="mb-4">
    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
</h2>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Total Licencias</h6>
                        <h2>{{ $totalLicencias }}</h2>
                    </div>
                    <i class="fas fa-file-alt fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Pendientes</h6>
                        <h2>{{ $pendientes }}</h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Aprobadas</h6>
                        <h2>{{ $aprobadas }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Titular</h6>
                        <h2>{{ $totalContribuyentes }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <i class="fas fa-list me-2"></i>Últimas Licencias
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>N° Licencia</th>
                    <th>Titular</th>
                    <th>Nombre Comercial</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimasLicencias as $licencia)
                <tr>
                    <td><strong>{{ $licencia->numero_licencia }}</strong></td>
                    <td>{{ $licencia->contribuyente->nombres_razon_social }}</td>
                    <td>{{ $licencia->nombre_comercial }}</td>
                    <td>
                        <span class="badge badge-{{ $licencia->estado }}">
                            {{ strtoupper($licencia->estado) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay licencias registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection