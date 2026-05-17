@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-history me-2"></i>Licencias Históricas</h2>
    <a href="{{ route('licencias-historicas.importar') }}" class="btn btn-primary">
        <i class="fas fa-upload me-2"></i>Importar Datos
    </a>
</div>

<!-- Dashboard Estadísticas -->
<div class="row mb-5">
    <!-- ITSE 13 -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-left-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-building me-2" style="color: #ffc107;"></i>ITSE 13 (Riesgo Bajo/Medio)
                    </h6>
                </div>
                
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-primary mb-1">{{ $stats['anexo_13']['total'] }}</h3>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <h3 class="text-success mb-1">{{ $stats['anexo_13']['vigentes'] }}</h3>
                            <small class="text-muted">Vigentes</small>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-6">
                        <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                            <h3 class="text-danger mb-1">{{ $stats['anexo_13']['vencidos'] }}</h3>
                            <small class="text-muted">Vencidos</small>
                        </div>
                    </div>
                    <div class="col-6">
                        @php
                            $total = $stats['anexo_13']['total'];
                            $vigentes = $stats['anexo_13']['vigentes'];
                            $porcentaje = $total > 0 ? round(($vigentes / $total) * 100) : 0;
                        @endphp
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <h3 class="text-info mb-1">{{ $porcentaje }}%</h3>
                            <small class="text-muted">Vigencia</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ITSE 14 -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-left-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-factory me-2" style="color: #dc3545;"></i>ITSE 14 (Riesgo Alto)
                    </h6>
                </div>
                
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-primary mb-1">{{ $stats['anexo_14']['total'] }}</h3>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <h3 class="text-success mb-1">{{ $stats['anexo_14']['vigentes'] }}</h3>
                            <small class="text-muted">Vigentes</small>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-6">
                        <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                            <h3 class="text-danger mb-1">{{ $stats['anexo_14']['vencidos'] }}</h3>
                            <small class="text-muted">Vencidos</small>
                        </div>
                    </div>
                    <div class="col-6">
                        @php
                            $total = $stats['anexo_14']['total'];
                            $vigentes = $stats['anexo_14']['vigentes'];
                            $porcentaje = $total > 0 ? round(($vigentes / $total) * 100) : 0;
                        @endphp
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <h3 class="text-info mb-1">{{ $porcentaje }}%</h3>
                            <small class="text-muted">Vigencia</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ECSE -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-left-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-ticket-alt me-2" style="color: #28a745;"></i>ECSE (Eventos Públicos)
                    </h6>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="text-center p-4 bg-light rounded">
                            <h2 class="text-success mb-1">{{ $stats['evento_publico']['total'] }}</h2>
                            <small class="text-muted">Total de eventos registrados</small>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Los eventos no tienen vencimiento</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Últimos Registros Importados -->
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-list me-2"></i>Últimos Registros Importados
    </div>
    <div class="card-body">
        @if($recientes->count())
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Nº Licencia</th>
                        <th>Tipo</th>
                        <th>Solicitante</th>
                        <th>Fecha Emisión</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recientes as $licencia)
                    <tr>
                        <td><strong>{{ $licencia->numero_licencia }}</strong></td>
                        <td>
                            <span class="badge bg-info">
                                <i class="fas {{ $licencia->tipo_icono }}"></i>
                                {{ substr($licencia->tipo_nombre, 0, 15) }}
                            </span>
                        </td>
                        <td>{{ $licencia->solicitante }}</td>
                        <td>{{ $licencia->fecha_emision->format('d/m/Y') }}</td>
                        <td>
                            @if($licencia->estado === 'vigente')
                                <span class="badge bg-success">Vigente</span>
                            @elseif($licencia->estado === 'vencido')
                                <span class="badge bg-danger">Vencido</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('licencias-historicas.show', $licencia) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('licencias-historicas.listar') }}" class="btn btn-sm btn-outline-primary mt-2">
            Ver todos →
        </a>
        @else
        <div class="alert alert-info mb-0">
            <i class="fas fa-info-circle me-2"></i>
            No hay datos importados aún. <a href="{{ route('licencias-historicas.importar') }}">Importar ahora</a>
        </div>
        @endif
    </div>
</div>

<style>
.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.border-left-danger {
    border-left: 4px solid #dc3545 !important;
}

.border-left-success {
    border-left: 4px solid #28a745 !important;
}
</style>
@endsection
