@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt me-2"></i>Detalle de Solicitud</h2>
    <a href="{{ route('solicitudes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-info-circle me-2"></i>Información de la Solicitud
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%" class="fw-bold text-muted">Código</td>
                        <td><strong class="text-primary">{{ $solicitud->codigo_seguimiento }}</strong></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Tipo</td>
                        <td>
                            @if($solicitud->tipo_certificado == 'anexo_14')
                                <span class="badge bg-danger">Anexo 14 — Riesgo Alto</span>
                            @elseif($solicitud->tipo_certificado == 'anexo_13')
                                <span class="badge bg-warning text-dark">Anexo 13 — Riesgo Bajo/Medio</span>
                            @else
                                <span class="badge bg-primary">Evento Público</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Estado</td>
                        <td>
                            @if($solicitud->estado == 'recibido')
                                <span class="badge bg-primary fs-6">ENVIADA</span>
                            @elseif($solicitud->estado == 'en_revision')
                                <span class="badge bg-warning text-dark fs-6">EN REVISIÓN</span>
                            @elseif($solicitud->estado == 'aprobado')
                                <span class="badge bg-success fs-6">APROBADO</span>
                            @else
                                <span class="badge bg-danger fs-6">RECHAZADO</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Solicitante</td>
                        <td>{{ $solicitud->nombres_solicitante }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">DNI/RUC</td>
                        <td>{{ $solicitud->dni_ruc }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">WhatsApp</td>
                        <td><i class="fab fa-whatsapp text-success me-1"></i>{{ $solicitud->telefono_whatsapp }}</td>
                    </tr>
                    @if($solicitud->email)
                    <tr>
                        <td class="fw-bold text-muted">Email</td>
                        <td>{{ $solicitud->email }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="fw-bold text-muted">
                            {{ $solicitud->tipo_certificado == 'evento_publico' ? 'Nombre del Evento' : 'Nombre Comercial' }}
                        </td>
                        <td>{{ $solicitud->nombre_comercial ?? $solicitud->nombre_evento }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Dirección</td>
                        <td>{{ $solicitud->direccion }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Provincia / Dpto.</td>
                        <td>{{ $solicitud->provincia }} / {{ $solicitud->departamento }}</td>
                    </tr>
                    @if($solicitud->actividad)
                    <tr>
                        <td class="fw-bold text-muted">Actividad</td>
                        <td>{{ $solicitud->actividad }}</td>
                    </tr>
                    @endif
                    @if($solicitud->area_edificacion)
                    <tr>
                        <td class="fw-bold text-muted">Área Edificación</td>
                        <td>{{ $solicitud->area_edificacion }} m2</td>
                    </tr>
                    @endif
                    @if($solicitud->tipo_certificado == 'evento_publico')
                    <tr>
                        <td class="fw-bold text-muted">Fecha del Evento</td>
                        <td>{{ $solicitud->fecha_evento ? $solicitud->fecha_evento->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Organizador</td>
                        <td>{{ $solicitud->organizador_nombre }} — {{ $solicitud->organizador_dni }}</td>
                    </tr>
                    @endif
                    @if($solicitud->observaciones)
                    <tr>
                        <td class="fw-bold text-muted">Observaciones</td>
                        <td>{{ $solicitud->observaciones }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="fw-bold text-muted">Fecha de solicitud</td>
                        <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- DOCUMENTOS --}}
        @if($solicitud->doc_solicitud || $solicitud->doc_plano || $solicitud->doc_otros)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-paperclip me-2"></i>Documentos Adjuntos
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @if($solicitud->doc_solicitud)
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $solicitud->doc_solicitud) }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="fas fa-file me-2"></i>Solicitud / FUT
                        </a>
                    </div>
                    @endif
                    @if($solicitud->doc_plano)
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $solicitud->doc_plano) }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="fas fa-file me-2"></i>Plano / Croquis
                        </a>
                    </div>
                    @endif
                    @if($solicitud->doc_otros)
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $solicitud->doc_otros) }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="fas fa-file me-2"></i>Otros documentos
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($solicitud->licencia_id)
        <div class="alert alert-success">
            <i class="fas fa-file-alt me-2"></i>
            Certificado generado:
            <a href="{{ route('licencias.show', $solicitud->licencia_id) }}" class="btn btn-success btn-sm ms-2">
                <i class="fas fa-eye me-1"></i>Ver certificado
            </a>
        </div>
        @endif
    </div>

    {{-- PANEL ACCIÓN FUNCIONARIO --}}
    <div class="col-md-4">
        @if($solicitud->estado != 'aprobado' && $solicitud->estado != 'rechazado')
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <i class="fas fa-tasks me-2"></i><strong>Procesar Solicitud</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('solicitudes.procesar', $solicitud) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cambiar estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="en_revision" {{ $solicitud->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                            <option value="aprobado">Aprobar</option>
                            <option value="rechazado">Rechazar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="3"
                            placeholder="Motivo de aprobación o rechazo...">{{ $solicitud->observaciones }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-save me-2"></i>Guardar
                    </button>
                </form>
            </div>
        </div>
       @else
<div class="card shadow-sm">
    <div class="card-header {{ $solicitud->estado == 'aprobado' ? 'bg-success' : 'bg-danger' }} text-white">
        <i class="fas fa-{{ $solicitud->estado == 'aprobado' ? 'check' : 'times' }}-circle me-2"></i>
        {{ $solicitud->estado == 'aprobado' ? 'Solicitud Aprobada' : 'Solicitud Rechazada' }}
    </div>
    <div class="card-body">
        <p class="text-muted">Esta solicitud ya fue procesada.</p>
        @if($solicitud->observaciones)
        <p><strong>Observaciones:</strong><br>{{ $solicitud->observaciones }}</p>
        @endif
        @if($solicitud->estado == 'aprobado' && !$solicitud->licencia_id)
        <a href="{{ route('licencias.crear-desde-solicitud', $solicitud) }}" class="btn btn-success w-100 mt-2">
            <i class="fas fa-file-alt me-2"></i>Generar Certificado
        </a>
        @endif
    </div>
</div>
@endif
    </div>
</div>
@endsection