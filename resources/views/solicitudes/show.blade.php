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
                            @if($solicitud->estado == 'registrado')
                                <span class="badge bg-info fs-6">REGISTRADO</span>
                            @elseif($solicitud->estado == 'aceptado')
                                <span class="badge bg-success fs-6">ACEPTADO</span>
                            @elseif($solicitud->estado == 'enviado_a_revision')
                                <span class="badge bg-warning text-dark fs-6">ENVIADO A REVISIÓN</span>
                            @elseif($solicitud->estado == 'recibido')
                                <span class="badge bg-primary fs-6">RECIBIDO</span>
                            @elseif($solicitud->estado == 'en_revision')
                                <span class="badge bg-warning text-dark fs-6">EN REVISIÓN</span>
                            @elseif($solicitud->estado == 'aprobado')
                                <span class="badge bg-success fs-6">APROBADO</span>
                            @else
                                <span class="badge bg-danger fs-6">{{ strtoupper($solicitud->estado) }}</span>
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
                    <tr>
                        <td class="fw-bold text-muted">Estado de Pago</td>
                        <td>
                            @if(is_null($solicitud->estado_pago))
                                <span class="badge bg-secondary">PENDIENTE</span>
                            @elseif($solicitud->estado_pago == 'pago_validado')
                                <span class="badge bg-success">VALIDADO</span>
                            @elseif($solicitud->estado_pago == 'pago_rechazado')
                                <span class="badge bg-danger">RECHAZADO</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $solicitud->estado_pago)) }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- DOCUMENTOS --}}
        @if($solicitud->doc_solicitud || $solicitud->doc_plano || $solicitud->doc_dni_copia || $solicitud->doc_comprobante_pago || $solicitud->doc_otros)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-paperclip me-2"></i>Documentos Adjuntos
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @if($solicitud->doc_solicitud)
                    <div class="col-md-4">
                        <a href="{{ route('solicitudes.descargar', ['solicitud' => $solicitud, 'tipo' => 'doc_solicitud']) }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="fas fa-file me-2"></i>Solicitud / FUT
                        </a>
                    </div>
                    @endif
                    @if($solicitud->doc_plano)
                    <div class="col-md-4">
                        <a href="{{ route('solicitudes.descargar', ['solicitud' => $solicitud, 'tipo' => 'doc_plano']) }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="fas fa-file me-2"></i>Plano / Croquis
                        </a>
                    </div>
                    @endif
                    @if($solicitud->doc_dni_copia)
                    <div class="col-md-4">
                        <a href="{{ route('solicitudes.descargar', ['solicitud' => $solicitud, 'tipo' => 'doc_dni_copia']) }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="fas fa-id-card me-2"></i>DNI Copia
                        </a>
                    </div>
                    @endif
                    @if($solicitud->doc_comprobante_pago)
                    <div class="col-md-4">
                        <a href="{{ route('solicitudes.descargar', ['solicitud' => $solicitud, 'tipo' => 'doc_comprobante_pago']) }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="fas fa-receipt me-2"></i>Comprobante de Pago
                        </a>
                    </div>
                    @endif
                    @if($solicitud->doc_otros)
                    <div class="col-md-4">
                        <a href="{{ route('solicitudes.descargar', ['solicitud' => $solicitud, 'tipo' => 'doc_otros']) }}" target="_blank" class="btn btn-outline-primary w-100">
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

        {{-- REVISIONES RECIBIDAS --}}
        @if($solicitud->revisiones->count() > 0)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <i class="fas fa-user-check me-2"></i>Revisiones Recibidas ({{ $solicitud->revisiones->count() }})
            </div>
            <div class="card-body">
                @foreach($solicitud->revisiones as $revision)
                <div class="card mb-3" style="border-left: 4px solid {{ $revision->resultado_revision == 'aprobado' ? '#28a745' : ($revision->resultado_revision == 'requiere_cambios' ? '#ffc107' : '#dc3545') }};">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="mb-2">
                                    <strong>{{ $revision->revisor->nombre_revisor }}</strong>
                                    <small class="text-muted">({{ $revision->revisor->email }})</small>
                                </h6>
                                <p class="mb-2">
                                    <strong style="font-size:12px; text-transform:uppercase; color:#667eea;">Resultado:</strong>
                                    @if($revision->resultado_revision == 'aprobado')
                                        <span class="badge bg-success">APROBADO</span>
                                    @elseif($revision->resultado_revision == 'requiere_cambios')
                                        <span class="badge bg-warning text-dark">REQUIERE CAMBIOS</span>
                                    @else
                                        <span class="badge bg-danger">RECHAZADO</span>
                                    @endif
                                </p>
                                <p class="mb-2" style="font-size:13px; color:#555; line-height:1.5;">
                                    <strong>Notas:</strong><br>
                                    {{ $revision->notas }}
                                </p>
                                @if($revision->documento_revision)
                                <p class="mb-0">
                                    <a href="{{ asset('storage/' . $revision->documento_revision) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file me-1"></i>Ver documento
                                    </a>
                                </p>
                                @endif
                            </div>
                            <div class="col-md-4 text-end">
                                <small class="text-muted">
                                    Enviada: {{ $revision->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- PANEL ACCIÓN FUNCIONARIO --}}
    <div class="col-md-4">
        @if($solicitud->estado != 'aprobado' && $solicitud->estado != 'rechazado')
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning">
                <i class="fas fa-tasks me-2"></i><strong>Cambiar Estado</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('solicitudes.procesar', $solicitud) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nuevo estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="">-- Seleccionar estado --</option>
                            <!-- Aceptar: desde registrado o recibido -->
                            @if(in_array($solicitud->estado, ['registrado', 'recibido']))
                                <option value="aceptado">✅ Aceptar</option>
                            @endif
                            <!-- Enviar a revisión: desde aceptado -->
                            @if($solicitud->estado == 'aceptado')
                                <option value="enviado_a_revision">📋 Enviar a Revisión</option>
                                <option value="aprobado">✅ Aprobar directamente</option>
                                <option value="rechazado">❌ Rechazar</option>
                            @endif
                            <!-- Aprobar o Rechazar: desde enviado_a_revision o en_revision -->
                            @if(in_array($solicitud->estado, ['enviado_a_revision', 'en_revision']))
                                <option value="aprobado">✅ Aprobar</option>
                                <option value="rechazado">❌ Rechazar</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado de Pago</label>
                        <select name="estado_pago" class="form-select">
                            <option value="">-- Seleccionar --</option>
                            <option value="pago_pendiente" {{ $solicitud->estado_pago == 'pago_pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="pago_validado" {{ $solicitud->estado_pago == 'pago_validado' ? 'selected' : '' }}>✅ Validado</option>
                            <option value="pago_rechazado" {{ $solicitud->estado_pago == 'pago_rechazado' ? 'selected' : '' }}>❌ Rechazado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="3"
                            placeholder="Motivo de aprobación, rechazo o notas...">{{ $solicitud->observaciones }}</textarea>
                    </div>

                    <!-- Opciones de notificación -->
                    <div class="mb-3 border-top pt-3">
                        <label class="form-label fw-bold small">
                            <i class="fas fa-bell me-2"></i>Enviar Notificación
                        </label>
                        <div class="d-grid gap-2">
                            <button type="submit" name="enviar_notificacion" value="email" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Guardar + Email
                            </button>
                            <button type="submit" name="enviar_notificacion" value="whatsapp" class="btn btn-sm btn-outline-success">
                                <i class="fab fa-whatsapp me-2"></i>Guardar + WhatsApp
                            </button>
                            <button type="submit" name="enviar_notificacion" value="ambos" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-share-alt me-2"></i>Guardar + Ambos
                            </button>
                            <button type="submit" class="btn btn-sm btn-warning">
                                <i class="fas fa-save me-2"></i>Solo Guardar
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">
                            ✉️ Email: {{ $solicitud->email ?? 'No registrado' }}<br>
                            📱 WhatsApp: {{ $solicitud->telefono_whatsapp ?? 'No registrado' }}
                        </small>
                    </div>
                </form>
            </div>
        </div>

        {{-- ENVIAR A REVISIÓN --}}
        @if($solicitud->estado == 'aceptado')
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-info text-white">
                <i class="fas fa-envelope-open-text me-2"></i><strong>Enviar a Revisión</strong>
            </div>
            <div class="card-body">
                <button class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#modalRevisores">
                    <i class="fas fa-paper-plane me-2"></i>Enviar a Revisores
                </button>
                <small class="text-muted d-block mt-2">
                    Se enviarán los documentos a los revisores seleccionados (mínimo 1).
                </small>
            </div>
        </div>
        @endif

        {{-- REVISOR ESTADO --}}
        @if($solicitud->revisores->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-users me-2"></i><strong>Estado de Revisores</strong>
            </div>
            <div class="card-body" style="font-size: 13px;">
                @foreach($solicitud->revisores as $revisor)
                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ $revisor->nombre_revisor }}</span>
                        @if($revisor->estado_revision == 'pendiente')
                            <span class="badge bg-warning text-dark">PENDIENTE</span>
                        @elseif($revisor->estado_revision == 'revisado')
                            <span class="badge bg-success">✓ REVISADO</span>
                        @else
                            <span class="badge bg-danger">RECHAZADO</span>
                        @endif
                    </div>
                    <small class="text-muted">{{ $revisor->email }}</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif

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

{{-- MODAL SELECCIONAR REVISORES --}}
<div class="modal fade" id="modalRevisores" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-users me-2"></i>Seleccionar Revisores
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('solicitudes.enviar-revision', $solicitud) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-4">
                        Ingrese los datos de los revisores que revisarán esta solicitud (mínimo 1). Se les enviará un correo con los documentos.
                    </p>

                    <div id="revisoresList"></div>

                    <button type="button" class="btn btn-sm btn-outline-info" onclick="agregarRevisor()">
                        <i class="fas fa-plus me-2"></i>Agregar Revisor
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-paper-plane me-2"></i>Enviar a Revisión
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let contadorRevisores = 0;
    let cantidadRevisores = 1;

    // Inicializar modal con 1 revisor
    document.getElementById('modalRevisores').addEventListener('show.bs.modal', function () {
        if (cantidadRevisores === 1 && contadorRevisores === 0) {
            agregarRevisor();
        }
    });

    function agregarRevisor() {
        const revisoresList = document.getElementById('revisoresList');
        const indice = contadorRevisores;
        
        const revisorHTML = `
            <div class="card mb-3 revisor-card" id="revisor-${indice}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Revisor ${indice + 1}</h6>
                        ${cantidadRevisores > 1 ? `
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removerRevisor(${indice})">
                                <i class="fas fa-trash me-1"></i>Remover
                            </button>
                        ` : ''}
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="revisores[${indice}][nombre]" class="form-control revisor-nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="revisores[${indice}][email]" class="form-control revisor-email" required>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        revisoresList.insertAdjacentHTML('beforeend', revisorHTML);
        contadorRevisores++;
        cantidadRevisores++;
        actualizarBotonesRemover();
    }

    function removerRevisor(indice) {
        const revisorElement = document.getElementById(`revisor-${indice}`);
        if (revisorElement) {
            revisorElement.remove();
            cantidadRevisores--;
            actualizarBotonesRemover();
        }
    }

    function actualizarBotonesRemover() {
        const revisorCards = document.querySelectorAll('.revisor-card');
        revisorCards.forEach((card, index) => {
            const removerBtn = card.querySelector('button[onclick*="removerRevisor"]');
            if (revisorCards.length > 1) {
                if (!removerBtn) {
                    const titulo = card.querySelector('.card-title').parentElement;
                    const btnRemover = document.createElement('button');
                    btnRemover.type = 'button';
                    btnRemover.className = 'btn btn-sm btn-outline-danger';
                    btnRemover.onclick = function() { removerRevisor(Array.from(revisorCards).indexOf(card)); };
                    btnRemover.innerHTML = '<i class="fas fa-trash me-1"></i>Remover';
                    titulo.querySelector('h6').parentElement.appendChild(btnRemover);
                }
            } else if (removerBtn) {
                removerBtn.remove();
            }
        });
    }
</script>

@php
    $whatsappLink = session('whatsapp_link');
@endphp

@if($whatsappLink)
<script>
    // Abrir WhatsApp automáticamente
    window.addEventListener('load', function() {
        const link = `{{ $whatsappLink }}`;
        console.log('Abriendo WhatsApp con link:', link.substring(0, 50) + '...');
        setTimeout(function() {
            window.open(link, '_blank');
        }, 800);
    });
</script>
@endif

@endsection