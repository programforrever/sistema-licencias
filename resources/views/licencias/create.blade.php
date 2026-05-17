@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt me-2"></i>Nuevo Certificado ITSE</h2>
    <a href="{{ route('licencias.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('licencias.store') }}" method="POST">
            @csrf
            
            {{-- Pasar el ID de solicitud si viene de una solicitud --}}
            @if($solicitud)
                <input type="hidden" name="solicitud_id" value="{{ $solicitud->id }}">
            @endif

            {{-- TIPO DE CERTIFICADO --}}
            <div class="row mb-4">
                <div class="col-12">
                    <label class="form-label fw-bold fs-5">Tipo de Certificado</label>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="tipo_certificado" id="anexo_14" value="anexo_14" {{ (old('tipo_certificado') ?? $solicitud?->tipo_certificado ?? 'anexo_14') === 'anexo_14' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger w-100 py-3" for="anexo_14">
                                <i class="fas fa-exclamation-triangle d-block fa-2x mb-1"></i>
                                <strong>ANEXO 14</strong><br>
                                <small>Riesgo Alto / Muy Alto</small>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="tipo_certificado" id="anexo_13" value="anexo_13" {{ (old('tipo_certificado') ?? $solicitud?->tipo_certificado) === 'anexo_13' ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning w-100 py-3" for="anexo_13">
                                <i class="fas fa-exclamation-circle d-block fa-2x mb-1"></i>
                                <strong>ANEXO 13</strong><br>
                                <small>Riesgo Bajo / Medio</small>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="tipo_certificado" id="evento_publico" value="evento_publico" {{ (old('tipo_certificado') ?? $solicitud?->tipo_certificado) === 'evento_publico' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary w-100 py-3" for="evento_publico">
                                <i class="fas fa-calendar-alt d-block fa-2x mb-1"></i>
                                <strong>EVENTO PÚBLICO</strong><br>
                                <small>Espectáculo Público</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DATOS GENERALES --}}
            <div class="card mb-3 border-primary">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-info-circle me-2"></i>Datos Generales
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Contribuyente</label>
                            <select name="contribuyente_id" class="form-select @error('contribuyente_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar --</option>
                                @foreach($contribuyentes as $c)
                                <option value="{{ $c->id }}" {{ (old('contribuyente_id') ?? $contribuyentePrellenado?->id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->dni_ruc }} - {{ $c->nombres_razon_social }}
                                </option>
                                @endforeach
                            </select>
                            @error('contribuyente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Actividad Económica</label>
                            <select name="actividad_economica_id" class="form-select select2 @error('actividad_economica_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar --</option>
                                @foreach($actividades as $a)
                                <option value="{{ $a->id }}" {{ old('actividad_economica_id') == $a->id ? 'selected' : '' }}>
                                    {{ $a->codigo }} - {{ $a->descripcion }}
                                </option>
                                @endforeach
                            </select>
                            @error('actividad_economica_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="estado" class="form-select" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="aprobado">Aprobado</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Observaciones</label>
                            <input type="text" name="observaciones" class="form-control" value="{{ old('observaciones') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- CAMPOS ANEXO 13 Y 14 --}}
            <div id="campos-anexo" class="card mb-3 border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-building me-2"></i>Datos del Establecimiento
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nombre Comercial</label>
                            <input type="text" name="nombre_comercial" class="form-control @error('nombre_comercial') is-invalid @enderror" value="{{ old('nombre_comercial') ?? $solicitud?->nombre_comercial ?? '' }}">
                            @error('nombre_comercial')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Dirección del Establecimiento</label>
                            <input type="text" name="direccion_establecimiento" class="form-control @error('direccion_establecimiento') is-invalid @enderror" value="{{ old('direccion_establecimiento') ?? $solicitud?->direccion ?? '' }}">
                            @error('direccion_establecimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Provincia</label>
                            <input type="text" name="provincia" class="form-control" value="{{ old('provincia') ?? $solicitud?->provincia ?? 'HUAMANGA' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Departamento</label>
                            <input type="text" name="departamento" class="form-control" value="{{ old('departamento') ?? $solicitud?->departamento ?? 'AYACUCHO' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Solicitado por</label>
                            <input type="text" name="solicitado_por" class="form-control @error('solicitado_por') is-invalid @enderror" value="{{ old('solicitado_por') ?? $solicitud?->nombres_solicitante ?? '' }}">
                            @error('solicitado_por')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Capacidad Máxima (N°)</label>
                            <input type="number" name="capacidad_maxima" class="form-control" value="{{ old('capacidad_maxima') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Capacidad (En letras)</label>
                            <input type="text" name="capacidad_letras" class="form-control" value="{{ old('capacidad_letras') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Área Edificación (m2)</label>
                            <input type="number" step="0.01" name="area_edificacion" class="form-control" value="{{ old('area_edificacion') ?? $solicitud?->area_edificacion ?? '' }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Vigencia</label>
                            <select name="vigencia" class="form-select">
                                <option value="1 AÑO">1 AÑO</option>
                                <option value="2 AÑOS" selected>2 AÑOS</option>
                                <option value="3 AÑOS">3 AÑOS</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Aprobado mediante Informe N°</label>
                            <input type="text" name="informe_aprobacion" class="form-control @error('informe_aprobacion') is-invalid @enderror" value="{{ old('informe_aprobacion') }}">
                            @error('informe_aprobacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- CAMPOS EVENTO PÚBLICO --}}
            <div id="campos-evento" class="card mb-3 border-primary" style="display:none;">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-calendar-alt me-2"></i>Datos del Evento
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nombre del Establecimiento/Lugar</label>
                            <input type="text" name="nombre_establecimiento" class="form-control @error('nombre_establecimiento') is-invalid @enderror" value="{{ old('nombre_establecimiento') ?? $solicitud?->nombre_comercial ?? '' }}">
                            @error('nombre_establecimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Dirección del Lugar</label>
                            <input type="text" name="direccion_establecimiento" class="form-control @error('direccion_establecimiento') is-invalid @enderror" value="{{ old('direccion_establecimiento') ?? $solicitud?->direccion ?? '' }}">
                            @error('direccion_establecimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Nombre del Evento</label>
                            <input type="text" name="nombre_evento" class="form-control @error('nombre_evento') is-invalid @enderror" value="{{ old('nombre_evento') ?? $solicitud?->nombre_evento ?? '' }}">
                            @error('nombre_evento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Fecha del Evento</label>
                            <input type="date" name="fecha_evento" class="form-control @error('fecha_evento') is-invalid @enderror" value="{{ old('fecha_evento') ?? $solicitud?->fecha_evento?->format('Y-m-d') ?? '' }}">
                            @error('fecha_evento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold">Días de Vigencia</label>
                            <input type="number" name="dias_evento" class="form-control @error('dias_evento') is-invalid @enderror" value="{{ old('dias_evento') ?? $solicitud?->dias_evento ?? 1 }}" min="1" max="365">
                            <small class="text-muted">1-365 días</small>
                            @error('dias_evento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Organizador (Nombre completo)</label>
                            <input type="text" name="organizador_nombre" class="form-control @error('organizador_nombre') is-invalid @enderror" value="{{ old('organizador_nombre') ?? $solicitud?->organizador_nombre ?? '' }}">
                            @error('organizador_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">DNI del Organizador</label>
                            <input type="text" name="organizador_dni" class="form-control @error('organizador_dni') is-invalid @enderror" value="{{ old('organizador_dni') ?? $solicitud?->organizador_dni ?? '' }}">
                            @error('organizador_dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Capacidad Máxima</label>
                            <input type="number" name="capacidad_maxima" class="form-control" value="{{ old('capacidad_maxima') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Representante Legal</label>
                            <input type="text" name="representante_legal" class="form-control" value="{{ old('representante_legal') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Empresa Organizadora</label>
                            <input type="text" name="empresa_organizadora" class="form-control" value="{{ old('empresa_organizadora') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">N° Informe ECSE</label>
                            <input type="text" name="numero_informe_ecse" class="form-control @error('numero_informe_ecse') is-invalid @enderror" value="{{ old('numero_informe_ecse') }}">
                            @error('numero_informe_ecse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Horario de Inicio</label>
                            <input type="time" name="horario_inicio" class="form-control @error('horario_inicio') is-invalid @enderror" value="{{ old('horario_inicio') }}">
                            @error('horario_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Horario de Finalización</label>
                            <input type="time" name="horario_fin" class="form-control @error('horario_fin') is-invalid @enderror" value="{{ old('horario_fin') }}">
                            @error('horario_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Restricciones</label>
                            <textarea name="restricciones" class="form-control" rows="4" placeholder="Ingrese las restricciones del evento...">{{ old('restricciones') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save me-2"></i>Registrar Certificado
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function toggleCampos() {
        const tipo = document.querySelector('input[name="tipo_certificado"]:checked').value;
        const esEvento = tipo === 'evento_publico';
        document.getElementById('campos-anexo').style.display = esEvento ? 'none' : 'block';
        document.getElementById('campos-evento').style.display = esEvento ? 'block' : 'none';
    }

    document.querySelectorAll('input[name="tipo_certificado"]').forEach(function(radio) {
        radio.addEventListener('change', toggleCampos);
    });

    toggleCampos();
</script>
@endsection