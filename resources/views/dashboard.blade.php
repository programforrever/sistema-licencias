@extends('layouts.app')

@section('content')
<style>
    .stat-card {
        background: var(--card-bg);
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-top: 4px solid;
        color: var(--text-primary);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    
    .stat-card.primary { border-top-color: #0d6efd; }
    .stat-card.warning { border-top-color: #ffc107; }
    .stat-card.success { border-top-color: #198754; }
    .stat-card.info { border-top-color: #0dcaf0; }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all 0.3s ease;
    }
    
    .stat-card.primary .stat-icon { background: #e7f1ff; color: #0d6efd; }
    .stat-card.warning .stat-icon { background: #fff8e1; color: #ffc107; }
    .stat-card.success .stat-icon { background: #e8f5e9; color: #198754; }
    .stat-card.info .stat-icon { background: #e0f7fa; color: #0dcaf0; }
    
    body.dark-mode .stat-card.primary .stat-icon { background: rgba(13, 110, 253, 0.2); }
    body.dark-mode .stat-card.warning .stat-icon { background: rgba(255, 193, 7, 0.2); }
    body.dark-mode .stat-card.success .stat-icon { background: rgba(25, 135, 84, 0.2); }
    body.dark-mode .stat-card.info .stat-icon { background: rgba(13, 202, 240, 0.2); }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        margin-top: 10px;
    }
    
    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table-custom thead th {
        background: var(--table-hover);
        border: none;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-primary);
        padding: 15px;
    }
    
    .table-custom tbody td {
        padding: 15px;
        border: none;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
        color: var(--text-primary);
    }
    
    .table-custom tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-custom tbody tr:hover {
        background: var(--table-hover);
    }
    
    .badge-custom {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-pendiente { background: #fff3cd; color: #856404; }
    .badge-en_tramite { background: #d1ecf1; color: #0c5460; }
    .badge-aprobada { background: #d4edda; color: #155724; }
    .badge-rechazada { background: #f8d7da; color: #721c24; }
</style>

<!-- Header -->
<div class="mb-5">
    <h1 class="mb-2"><i class="fas fa-tachometer-alt me-2" style="color: #0d6efd;"></i>Dashboard</h1>
    <p class="text-muted mb-0">Bienvenido al Sistema de Gestión de Licencias</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card primary">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">Total Licencias</p>
                        <p class="stat-value">{{ $totalLicencias }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted">Licencias registradas en el sistema</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card warning">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">En Trámite</p>
                        <p class="stat-value">{{ $pendientes }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted">Requieren seguimiento</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card success">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">Aprobadas</p>
                        <p class="stat-value">{{ $aprobadas }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted">Licencias activas</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card info">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">Titulares</p>
                        <p class="stat-value">{{ $totalContribuyentes }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted">Contribuyentes registrados</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Licenses Table -->
<div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    <div style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); padding: 20px; color: white;">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Últimas Licencias</h5>
    </div>
    <div class="table-responsive" style="border: none;">
        <table class="table table-custom mb-0">
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
                    <td><span class="fw-bold">{{ $licencia->numero_licencia }}</span></td>
                    <td>{{ $licencia->contribuyente->nombres_razon_social }}</td>
                    <td>{{ $licencia->nombre_comercial }}</td>
                    <td>
                        @php
                            $estadoConfig = [
                                'pendiente' => 'badge-pendiente',
                                'en_tramite' => 'badge-en_tramite',
                                'aprobada' => 'badge-aprobada',
                                'rechazada' => 'badge-rechazada',
                            ];
                            $badgeClass = $estadoConfig[$licencia->estado] ?? 'badge bg-secondary';
                        @endphp
                        <span class="badge-custom {{ $badgeClass }}">
                            {{ ucfirst(str_replace('_', ' ', $licencia->estado)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 40px; color: #ccc;"></i>
                        <p class="text-muted mt-3">No hay licencias registradas</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection