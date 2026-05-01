@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt me-2"></i>Detalle de Certificado</h2>
    <div>
        <a href="{{ route('licencias.edit', $licencia) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
        <a href="{{ route('licencias.pdf', $licencia) }}" class="btn btn-danger me-2" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Generar PDF
        </a>
        <a href="{{ route('licencias.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-info-circle me-2"></i>Información del Certificado
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%" class="fw-bold text-muted">N° Certificado</td>
                        <td><strong class="text-primary fs-5">{{ $licencia->numero_licencia }}</strong></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Tipo</td>
                        <td>
                            @if($licencia->tipo_certificado == 'anexo_14')
                                <span class="badge bg-danger fs-6">ANEXO 14 — Riesgo Alto/Muy Alto</span>
                            @elseif($licencia->tipo_certificado == 'anexo_13')
                                <span class="badge bg-warning text-dark fs-6">ANEXO 13 — Riesgo Bajo/Medio</span>
                            @else
                                <span class="badge bg-primary fs-6">EVENTO PÚBLICO</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Estado</td>
                        <td>
                            <span class="badge badge-{{ $licencia->estado }} fs-6">
                                {{ strtoupper($licencia->estado) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">N° Expediente</td>
                        <td>{{ $licencia->numero_expediente ?? '-' }}</td>
                    </tr>

                    @if($licencia->tipo_certificado != 'evento_publico')
                    <tr>
                        <td class="fw-bold text-muted">Nombre Comercial</td>
                        <td>{{ $licencia->nombre_comercial }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Dirección</td>
                        <td>{{ $licencia->direccion_establecimiento }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Distrito / Provincia / Dpto.</td>
                        <td>Andrés Avelino Cáceres D. / {{ $licencia->provincia }} / {{ $licencia->departamento }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Solicitado por</td>
                        <td>{{ $licencia->solicitado_por }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Capacidad Máxima</td>
                        <td>{{ $licencia->capacidad_maxima }} — {{ $licencia->capacidad_letras }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Área Edificación</td>
                        <td>{{ $licencia->area_edificacion }} m2</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Actividad Económica</td>
                        <td>{{ $licencia->actividadEconomica->descripcion }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Informe Aprobación</td>
                        <td>{{ $licencia->informe_aprobacion }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Vigencia</td>
                        <td>{{ $licencia->vigencia }}</td>
                    </tr>
                    @else
                    <tr>
                        <td class="fw-bold text-muted">Establecimiento/Lugar</td>
                        <td>{{ $licencia->nombre_establecimiento }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Dirección</td>
                        <td>{{ $licencia->direccion_establecimiento }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Nombre del Evento</td>
                        <td>{{ $licencia->nombre_evento }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Fecha del Evento</td>
                        <td>{{ $licencia->fecha_evento ? \Carbon\Carbon::parse($licencia->fecha_evento)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Organizador</td>
                        <td>{{ $licencia->organizador_nombre }} — DNI: {{ $licencia->organizador_dni }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Empresa Organizadora</td>
                        <td>{{ $licencia->empresa_organizadora ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Representante Legal</td>
                        <td>{{ $licencia->representante_legal ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Capacidad Máxima</td>
                        <td>{{ $licencia->capacidad_maxima ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Horario</td>
                        <td>{{ $licencia->horario_inicio }} — {{ $licencia->horario_fin }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">N° Informe ECSE</td>
                        <td>{{ $licencia->numero_informe_ecse }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Restricciones</td>
                        <td>{{ $licencia->restricciones ?? '-' }}</td>
                    </tr>
                    @endif

                    <tr>
                        <td class="fw-bold text-muted">Fecha Emisión</td>
                        <td>{{ $licencia->fecha_emision ? \Carbon\Carbon::parse($licencia->fecha_emision)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Fecha Vencimiento</td>
                        <td>{{ $licencia->fecha_vencimiento ? \Carbon\Carbon::parse($licencia->fecha_vencimiento)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Observaciones</td>
                        <td>{{ $licencia->observaciones ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-user me-2"></i>Datos del Titular
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong><br>{{ $licencia->contribuyente->nombres_razon_social }}</p>
                <p><strong>DNI/RUC:</strong><br>{{ $licencia->contribuyente->dni_ruc }}</p>
                <p><strong>Tipo:</strong><br>{{ strtoupper($licencia->contribuyente->tipo_persona) }}</p>
                <p><strong>Dirección:</strong><br>{{ $licencia->contribuyente->direccion }}</p>
                <p><strong>Teléfono:</strong><br>{{ $licencia->contribuyente->telefono ?? '-' }}</p>
                <p class="mb-0"><strong>Email:</strong><br>{{ $licencia->contribuyente->email ?? '-' }}</p>
            </div>
        </div>

        @if($licencia->estado == 'pendiente')
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>Acciones
            </div>
            <div class="card-body">
                <form action="{{ route('licencias.aprobar', $licencia) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fecha de Emisión</label>
                        <input type="date" name="fecha_emision" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check me-2"></i>Aprobar Certificado
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection