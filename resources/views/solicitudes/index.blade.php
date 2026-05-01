@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-inbox me-2"></i>Solicitudes de Trámite Online</h2>
    <span class="badge bg-danger fs-6">
        {{ \App\Models\Solicitud::where('estado','recibido')->count() }} nuevas
    </span>
</div>

{{-- FILTROS --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('solicitudes.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4 col-12">
                    <label class="form-label fw-bold small">Buscar</label>
                    <input type="text" name="buscar" class="form-control form-control-sm"
                        placeholder="Código, nombre, DNI..."
                        value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label fw-bold small">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="recibido" {{ request('estado') == 'recibido' ? 'selected' : '' }}>ENVIADA</option>
                        <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                        <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label fw-bold small">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="anexo_14" {{ request('tipo') == 'anexo_14' ? 'selected' : '' }}>Anexo 14</option>
                        <option value="anexo_13" {{ request('tipo') == 'anexo_13' ? 'selected' : '' }}>Anexo 13</option>
                        <option value="evento_publico" {{ request('tipo') == 'evento_publico' ? 'selected' : '' }}>Evento Público</option>
                    </select>
                </div>
                <div class="col-md-2 col-12 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('solicitudes.index') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        {{-- VISTA TABLA (desktop) --}}
        <div class="d-none d-md-block table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Solicitante</th>
                        <th>DNI/RUC</th>
                        <th>WhatsApp</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                    <tr>
                        <td><strong>{{ $solicitud->codigo_seguimiento }}</strong></td>
                        <td>
                            @if($solicitud->tipo_certificado == 'anexo_14')
                                <span class="badge bg-danger">Anexo 14</span>
                            @elseif($solicitud->tipo_certificado == 'anexo_13')
                                <span class="badge bg-warning text-dark">Anexo 13</span>
                            @else
                                <span class="badge bg-primary">Evento</span>
                            @endif
                        </td>
                        <td>{{ $solicitud->nombres_solicitante }}</td>
                        <td>{{ $solicitud->dni_ruc }}</td>
                        <td><i class="fab fa-whatsapp text-success me-1"></i>{{ $solicitud->telefono_whatsapp }}</td>
                        <td>
                            @if($solicitud->estado == 'recibido')
                                <span class="badge bg-primary">ENVIADA</span>
                            @elseif($solicitud->estado == 'en_revision')
                                <span class="badge bg-warning text-dark">EN REVISIÓN</span>
                            @elseif($solicitud->estado == 'aprobado')
                                <span class="badge bg-success">APROBADO</span>
                            @else
                                <span class="badge bg-danger">RECHAZADO</span>
                            @endif
                        </td>
                        <td>{{ $solicitud->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('solicitudes.show', $solicitud) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            No hay solicitudes
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- VISTA CARDS (móvil) --}}
        <div class="d-md-none">
            @forelse($solicitudes as $solicitud)
            <div class="card mb-2 border-0 shadow-sm">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong class="text-primary" style="font-size:13px;">{{ $solicitud->codigo_seguimiento }}</strong><br>
                            <span style="font-size:12px;">{{ $solicitud->nombres_solicitante }}</span><br>
                            <small class="text-muted">DNI/RUC: {{ $solicitud->dni_ruc }}</small><br>
                            <small><i class="fab fa-whatsapp text-success me-1"></i>{{ $solicitud->telefono_whatsapp }}</small>
                        </div>
                        <div class="text-end">
                            @if($solicitud->tipo_certificado == 'anexo_14')
                                <span class="badge bg-danger">Anexo 14</span>
                            @elseif($solicitud->tipo_certificado == 'anexo_13')
                                <span class="badge bg-warning text-dark">Anexo 13</span>
                            @else
                                <span class="badge bg-primary">Evento</span>
                            @endif
                            <br>
                            @if($solicitud->estado == 'recibido')
                                <span class="badge bg-primary mt-1">RECIBIDO</span>
                            @elseif($solicitud->estado == 'en_revision')
                                <span class="badge bg-warning text-dark mt-1">EN REVISIÓN</span>
                            @elseif($solicitud->estado == 'aprobado')
                                <span class="badge bg-success mt-1">APROBADO</span>
                            @else
                                <span class="badge bg-danger mt-1">RECHAZADO</span>
                            @endif
                            <br>
                            <small class="text-muted">{{ $solicitud->created_at->format('d/m/Y') }}</small><br>
                            <a href="{{ route('solicitudes.show', $solicitud) }}" class="btn btn-sm btn-info mt-1">
                                <i class="fas fa-eye me-1"></i>Ver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-4">
                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                No hay solicitudes
            </div>
            @endforelse
        </div>

        {{ $solicitudes->appends(request()->query())->links() }}
    </div>
</div>
@endsection