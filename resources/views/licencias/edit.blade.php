@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i>Editar Certificado</h2>
    <a href="{{ route('licencias.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('licencias.update', $licencia) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- TIPO --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Tipo de Certificado</label>
                <input type="text" class="form-control" value="
                    @if($licencia->tipo_certificado == 'anexo_14') ANEXO 14 — Riesgo Alto/Muy Alto
                    @elseif($licencia->tipo_certificado == 'anexo_13') ANEXO 13 — Riesgo Bajo/Medio
                    @else EVENTO PÚBLICO @endif" disabled>
                <input type="hidden" name="tipo_certificado" value="{{ $licencia->tipo_certificado }}">
            </div>

            {{-- DATOS GENERALES --}}
            <div class="card mb-3 border-primary">
                <div class="card-header bg-primary text-white">Datos Generales</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">N° Certificado</label>
                            <input type="text" class="form-control" value="{{ $licencia->numero_licencia }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="estado" class="form-select" required>
                                <option value="pendiente" {{ $licencia->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="aprobado" {{ $licencia->estado == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                <option value="rechazado" {{ $licencia->estado == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                <option value="suspendido" {{ $licencia->estado == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Titular</label>
                            <select name="contribuyente_id" class="form-select" required>
                                @foreach($contribuyentes as $c)
                                <option value="{{ $c->id }}" {{ $licencia->contribuyente_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->dni_ruc }} - {{ $c->nombres_razon_social }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Actividad Económica</label>
                            <select name="actividad_economica_id" class="form-select" required>
                                @foreach($actividades as $a)
                                <option value="{{ $a->id }}" {{ $licencia->actividad_economica_id == $a->id ? 'selected' : '' }}>
                                    {{ $a->codigo }} - {{ $a->descripcion }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Fecha de Emisión</label>
                            <input type="date" name="fecha_emision" class="form-control" value="{{ $licencia->fecha_emision }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Fecha de Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" class="form-control" value="{{ $licencia->fecha_vencimiento }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="2">{{ $licencia->observaciones }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CAMPOS ANEXO 13 / 14 --}}
            @if($licencia->tipo_certificado != 'evento_publico')
            <div class="card mb-3 border-danger">
                <div class="card-header bg-danger text-white">Datos del Establecimiento</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nombre Comercial</label>
                            <input type="text" name="nombre_comercial" class="form-control" value="{{ $licencia->nombre_comercial }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Dirección</label>
                            <input type="text" name="direccion_establecimiento" class="form-control" value="{{ $licencia->direccion_establecimiento }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Provincia</label>
                            <input type="text" name="provincia" class="form-control" value="{{ $licencia->provincia }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Departamento</label>
                            <input type="text" name="departamento" class="form-control" value="{{ $licencia->departamento }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Solicitado por</label>
                            <input type="text" name="solicitado_por" class="form-control" value="{{ $licencia->solicitado_por }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Capacidad Máxima (N°)</label>
                            <input type="number" name="capacidad_maxima" class="form-control" value="{{ $licencia->capacidad_maxima }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Capacidad (Letras)</label>
                            <input type="text" name="capacidad_letras" class="form-control" value="{{ $licencia->capacidad_letras }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Área Edificación (m2)</label>
                            <input type="number" step="0.01" name="area_edificacion" class="form-control" value="{{ $licencia->area_edificacion }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Vigencia</label>
                            <select name="vigencia" class="form-select">
                                <option value="1 AÑO" {{ $licencia->vigencia == '1 AÑO' ? 'selected' : '' }}>1 AÑO</option>
                                <option value="2 AÑOS" {{ $licencia->vigencia == '2 AÑOS' ? 'selected' : '' }}>2 AÑOS</option>
                                <option value="3 AÑOS" {{ $licencia->vigencia == '3 AÑOS' ? 'selected' : '' }}>3 AÑOS</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Aprobado mediante Informe N°</label>
                            <input type="text" name="informe_aprobacion" class="form-control" value="{{ $licencia->informe_aprobacion }}">
                        </div>
                    </div>
                </div>
            </div>
            @else
            {{-- CAMPOS EVENTO PÚBLICO --}}
            <div class="card mb-3 border-primary">
                <div class="card-header bg-primary text-white">Datos del Evento</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nombre del Establecimiento/Lugar</label>
                            <input type="text" name="nombre_establecimiento" class="form-control" value="{{ $licencia->nombre_establecimiento }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Dirección</label>
                            <input type="text" name="direccion_establecimiento" class="form-control" value="{{ $licencia->direccion_establecimiento }}">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Nombre del Evento</label>
                            <input type="text" name="nombre_evento" class="form-control" value="{{ $licencia->nombre_evento }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Fecha del Evento</label>
                            <input type="date" name="fecha_evento" class="form-control" value="{{ $licencia->fecha_evento }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Organizador</label>
                            <input type="text" name="organizador_nombre" class="form-control" value="{{ $licencia->organizador_nombre }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">DNI Organizador</label>
                            <input type="text" name="organizador_dni" class="form-control" value="{{ $licencia->organizador_dni }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Capacidad Máxima</label>
                            <input type="number" name="capacidad_maxima" class="form-control" value="{{ $licencia->capacidad_maxima }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Representante Legal</label>
                            <input type="text" name="representante_legal" class="form-control" value="{{ $licencia->representante_legal }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Empresa Organizadora</label>
                            <input type="text" name="empresa_organizadora" class="form-control" value="{{ $licencia->empresa_organizadora }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">N° Informe ECSE</label>
                            <input type="text" name="numero_informe_ecse" class="form-control" value="{{ $licencia->numero_informe_ecse }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Horario Inicio</label>
                            <input type="time" name="horario_inicio" class="form-control" value="{{ $licencia->horario_inicio }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Horario Fin</label>
                            <input type="time" name="horario_fin" class="form-control" value="{{ $licencia->horario_fin }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Restricciones</label>
                            <textarea name="restricciones" class="form-control" rows="3">{{ $licencia->restricciones }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <button type="submit" class="btn btn-warning btn-lg">
                <i class="fas fa-save me-2"></i>Actualizar Certificado
            </button>
        </form>
    </div>
</div>
@endsection