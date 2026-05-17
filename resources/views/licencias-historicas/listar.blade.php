@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-list me-2"></i>Licencias Históricas</h2>
    <a href="{{ route('licencias-historicas.index') }}" class="btn btn-secondary">
        <i class="fas fa-chart-bar me-2"></i>Dashboard
    </a>
</div>

<!-- Filtros -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="buscar" class="form-control" placeholder="🔍 Buscar por número, solicitante..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select">
                    <option value="">-- Todos los tipo --</option>
                    <option value="anexo_13" {{ request('tipo') === 'anexo_13' ? 'selected' : '' }}>ITSE 13</option>
                    <option value="anexo_14" {{ request('tipo') === 'anexo_14' ? 'selected' : '' }}>ITSE 14</option>
                    <option value="evento_publico" {{ request('tipo') === 'evento_publico' ? 'selected' : '' }}>ECSE</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">-- Todos los estados --</option>
                    <option value="vigente" {{ request('estado') === 'vigente' ? 'selected' : '' }}>Vigente</option>
                    <option value="vencido" {{ request('estado') === 'vencido' ? 'selected' : '' }}>Vencido</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Licencias -->
<div class="card shadow-sm">
    <div class="card-body">
        @if($licencias->count())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nº Licencia</th>
                        <th>Tipo</th>
                        <th>Solicitante</th>
                        <th>Ubicación</th>
                        <th>Fecha Emisión</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($licencias as $licencia)
                    <tr>
                        <td><strong>{{ $licencia->numero_licencia }}</strong></td>
                        <td>
                            <span class="badge bg-info">
                                <i class="fas {{ $licencia->tipo_icono }}"></i>
                                {{ substr($licencia->tipo_nombre, 0, 15) }}
                            </span>
                        </td>
                        <td>{{ $licencia->solicitante }}</td>
                        <td><small>{{ $licencia->ubicacion ?? '-' }}</small></td>
                        <td>{{ $licencia->fecha_emision->format('d/m/Y') }}</td>
                        <td>
                            @if($licencia->fecha_vencimiento)
                                {{ $licencia->fecha_vencimiento->format('d/m/Y') }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($licencia->estado === 'vigente')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Vigente
                                </span>
                            @elseif($licencia->estado === 'vencido')
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Vencido
                                </span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('licencias-historicas.show', $licencia) }}" class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $licencias->links() }}
        </div>
        @else
        <div class="alert alert-info mb-0">
            <i class="fas fa-info-circle me-2"></i>
            No hay licencias históricas registradas. <a href="{{ route('licencias-historicas.importar') }}">Importar datos</a>
        </div>
        @endif
    </div>
</div>
@endsection
