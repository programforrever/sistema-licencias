@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt me-2"></i>Detalle de Licencia Histórica</h2>
    <a href="{{ route('licencias-historicas.listar') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Información Principal -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas {{ $licenciaHistorica->tipo_icono }} me-2"></i>{{ $licenciaHistorica->tipo_nombre }}
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%" class="fw-bold text-muted">Nº Licencia</td>
                        <td><strong class="text-primary">{{ $licenciaHistorica->numero_licencia }}</strong></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Solicitante</td>
                        <td>{{ $licenciaHistorica->solicitante }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Nombre Comercial</td>
                        <td>{{ $licenciaHistorica->nombre_comercial ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Actividad</td>
                        <td>{{ $licenciaHistorica->actividad ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Ubicación</td>
                        <td>{{ $licenciaHistorica->ubicacion ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Información Administrativa -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-briefcase me-2"></i>Información Administrativa
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%" class="fw-bold text-muted">Nº Expediente</td>
                        <td>{{ $licenciaHistorica->numero_expediente ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Nº de Informe</td>
                        <td>{{ $licenciaHistorica->informe_numero ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Fecha Emisión</td>
                        <td>{{ $licenciaHistorica->fecha_emision->format('d/m/Y') }}</td>
                    </tr>
                    @if(in_array($licenciaHistorica->tipo_certificado, ['anexo_13', 'anexo_14']))
                    <tr>
                        <td class="fw-bold text-muted">Vigencia (años)</td>
                        <td>{{ $licenciaHistorica->vigencia }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Fecha Vencimiento</td>
                        <td><strong>{{ $licenciaHistorica->fecha_vencimiento->format('d/m/Y') }}</strong></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Estado -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Estado
            </div>
            <div class="card-body text-center">
                @if($licenciaHistorica->estado === 'vigente')
                    <div class="display-4 text-success mb-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 class="text-success">Vigente</h5>
                    <small class="text-muted">
                        Vence el {{ $licenciaHistorica->fecha_vencimiento->format('d/m/Y') }}<br>
                        ({{ $licenciaHistorica->fecha_vencimiento->diffForHumans() }})
                    </small>
                @elseif($licenciaHistorica->estado === 'vencido')
                    <div class="display-4 text-danger mb-2">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h5 class="text-danger">Vencido</h5>
                    <small class="text-muted">
                        Venció el {{ $licenciaHistorica->fecha_vencimiento->format('d/m/Y') }}<br>
                        ({{ $licenciaHistorica->fecha_vencimiento->diffForHumans() }})
                    </small>
                @else
                    <div class="display-4 text-secondary mb-2">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h5 class="text-secondary">Sin Vencimiento</h5>
                    <small class="text-muted">Este tipo no tiene vencimiento</small>
                @endif
            </div>
        </div>

        <!-- Tipo de Certificado -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <i class="fas fa-certificate me-2"></i>Tipo de Certificado
            </div>
            <div class="card-body">
                <div class="badge bg-info p-2">{{ $licenciaHistorica->tipo_nombre }}</div>
                <small class="d-block mt-2 text-muted">
                    @if($licenciaHistorica->tipo_certificado === 'anexo_13')
                        Certificado ITSE para establecimientos de riesgo bajo/medio
                    @elseif($licenciaHistorica->tipo_certificado === 'anexo_14')
                        Certificado ITSE para establecimientos de riesgo alto
                    @else
                        Certificado ECSE para eventos públicos
                    @endif
                </small>
            </div>
        </div>

        <!-- Información del Registro -->
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-history me-2"></i>Información del Registro
            </div>
            <div class="card-body small">
                <p class="mb-1"><strong>Importado:</strong><br>{{ $licenciaHistorica->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-0"><strong>Última actualización:</strong><br>{{ $licenciaHistorica->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Botones de Acción -->
<div class="row mt-4">
    <div class="col-lg-8">
        <form action="{{ route('licencias-historicas.destroy', $licenciaHistorica) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este registro?')">
                <i class="fas fa-trash me-2"></i>Eliminar Registro
            </button>
        </form>
    </div>
</div>
@endsection
