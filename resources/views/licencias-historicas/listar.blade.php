@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-list me-2"></i>Licencias Importadas</h2>
        <small class="text-muted">Visualizando datos de tabla de importación</small>
    </div>
    <div>
        <a href="{{ route('licencias-historicas.importar') }}" class="btn btn-success me-2">
            <i class="fas fa-upload me-2"></i>Importar Más
        </a>
        <a href="{{ route('licencias-historicas.exportar-excel', request()->query()) }}" class="btn btn-info me-2" title="Descargar en formato Excel">
            <i class="fas fa-file-excel me-2"></i>Excel
        </a>
        <a href="{{ route('licencias-historicas.exportar-pdf', request()->query()) }}" class="btn btn-danger me-2" title="Descargar en formato PDF">
            <i class="fas fa-file-pdf me-2"></i>PDF
        </a>
        <a href="{{ route('licencias-historicas.index') }}" class="btn btn-secondary">
            <i class="fas fa-chart-bar me-2"></i>Dashboard
        </a>
    </div>
</div>

<!-- Filtros Avanzados -->
<div class="card shadow-sm mb-4 border-primary">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-lg-3">
                <label class="form-label fw-bold">🔍 Buscar</label>
                <input type="text" name="buscar" class="form-control" placeholder="Nº, Solicitante, Ubicación..."
                    value="{{ request('buscar') }}">
            </div>
            
            <div class="col-lg-2">
                <label class="form-label fw-bold">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="anexo_13" {{ request('tipo') === 'anexo_13' ? 'selected' : '' }}>ITSE 13</option>
                    <option value="anexo_14" {{ request('tipo') === 'anexo_14' ? 'selected' : '' }}>ITSE 14</option>
                </select>
            </div>
            
            <div class="col-lg-2">
                <label class="form-label fw-bold">Estado</label>
                <select name="vigencia" class="form-select">
                    <option value="">Todos</option>
                    <option value="vigente" {{ request('vigencia') === 'vigente' ? 'selected' : '' }}>✅ Vigentes</option>
                    <option value="vencido" {{ request('vigencia') === 'vencido' ? 'selected' : '' }}>❌ Vencidos</option>
                </select>
            </div>
            
            <div class="col-lg-2">
                <label class="form-label fw-bold">Desde</label>
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            
            <div class="col-lg-2">
                <label class="form-label fw-bold">Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            
            <div class="col-lg-1 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('licencias-historicas.listar') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-lg-4">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title">✅ Vigentes</h6>
                <h3>{{ $stats['vigentes'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card bg-danger text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title">❌ Vencidos</h6>
                <h3>{{ $stats['vencidos'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title">📋 Total</h6>
                <h3>{{ $stats['total'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Licencias -->
<div class="card shadow-sm">
    <div class="card-body">
        @if($licencias->count())
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nº Licencia</th>
                        <th>Tipo</th>
                        <th>Solicitante</th>
                        <th>Nombre Comercial</th>
                        <th>Ubicación</th>
                        <th>Fecha Emisión</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th>Días</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($licencias as $lic)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $lic->numero_licencia ?? '-' }}</strong>
                        </td>
                        
                        <td>
                            @if($lic->tipo === 'anexo_13')
                                <span class="badge bg-warning text-dark">ITSE 13</span>
                            @elseif($lic->tipo === 'anexo_14')
                                <span class="badge bg-info">ITSE 14</span>
                            @else
                                <span class="badge bg-secondary">ECSE</span>
                            @endif
                        </td>
                        
                        <td>
                            <small>{{ $lic->solicitante ?? '-' }}</small>
                        </td>
                        
                        <td>
                            <small>{{ $lic->nombre_comercial ?? '-' }}</small>
                        </td>
                        
                        <td>
                            <small class="text-muted">{{ $lic->ubicacion ?? '-' }}</small>
                        </td>
                        
                        <td>
                            @if($lic->fecha_emision)
                                <strong>{{ $lic->fecha_emision->format('d/m/Y') }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        
                        <td>
                            @if($lic->fecha_vencimiento)
                                <strong class="text-danger">{{ $lic->fecha_vencimiento->format('d/m/Y') }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        
                        <td>
                            @switch($lic->estado_vigencia)
                                @case('vigente')
                                    <span class="badge bg-success">Vigente</span>
                                @break
                                @case('vencido')
                                    <span class="badge bg-danger">Vencido</span>
                                @break
                            @endswitch
                        </td>
                        
                        <td>
                            @if($lic->dias_restantes !== null)
                                @if($lic->estado_vigencia === 'vigente')
                                    <span class="badge bg-info" title="Días hasta el vencimiento">{{ $lic->dias_restantes }} d</span>
                                @else
                                    <span class="badge bg-danger" title="Días desde que venció">{{ abs($lic->dias_restantes) }} d</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                data-bs-target="#detalleModal{{ $lic->id }}" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Modal Detalle -->
                    <div class="modal fade" id="detalleModal{{ $lic->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h6 class="modal-title">Detalle: {{ $lic->numero_licencia }}</h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nº Licencia:</strong> {{ $lic->numero_licencia }}</p>
                                            <p><strong>Tipo:</strong> {{ $lic->tipo }}</p>
                                            <p><strong>Mes:</strong> {{ $lic->mes ?? '-' }}</p>
                                            <p><strong>Anexo:</strong> {{ $lic->anexo ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Solicitante:</strong> {{ $lic->solicitante ?? '-' }}</p>
                                            <p><strong>Nombre Comercial:</strong> {{ $lic->nombre_comercial ?? '-' }}</p>
                                            <p><strong>Ubicación:</strong> {{ $lic->ubicacion ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Fecha Emisión:</strong> 
                                                @if($lic->fecha_emision)
                                                    {{ $lic->fecha_emision->format('d/m/Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                            <p><strong>Vencimiento:</strong> 
                                                @if($lic->fecha_vencimiento)
                                                    {{ $lic->fecha_vencimiento->format('d/m/Y') }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Estado:</strong>
                                                @switch($lic->estado_vigencia)
                                                    @case('vigente')
                                                        <span class="badge bg-success">Vigente</span>
                                                    @break
                                                    @case('vencido')
                                                        <span class="badge bg-danger">Vencido</span>
                                                    @break
                                                @endswitch
                                            </p>
                                            <p><strong>Nº Expediente:</strong> {{ $lic->numero_expediente ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <p><strong>Actividad:</strong> {{ $lic->actividad ?? '-' }}</p>
                                    <p><small class="text-muted">Importado: {{ $lic->created_at->format('d/m/Y H:i') }}</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <small class="text-muted">
                Mostrando {{ $licencias->firstItem() ?? 0 }} a {{ $licencias->lastItem() ?? 0 }} de {{ $licencias->total() }} registros
            </small>
            {{ $licencias->links('pagination::bootstrap-5') }}
        </div>

        @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
            <h5>No hay licencias importadas</h5>
            <p class="text-muted mb-0">Comienza importando licencias desde el formulario</p>
        </div>
        @endif
    </div>
</div>

@endsection
