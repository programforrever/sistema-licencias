@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt me-2"></i>Certificados ITSE</h2>
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('ingeniero'))
        <a href="{{ route('licencias.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Certificado
        </a>
    @endif
</div>

{{-- FILTROS --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('licencias.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3 col-12">
                    <label class="form-label fw-bold small">Buscar</label>
                    <input type="text" name="buscar" class="form-control form-control-sm"
                        placeholder="N° cert., nombre, titular..."
                        value="{{ request('buscar') }}">
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label fw-bold small">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="anexo_14" {{ request('tipo') == 'anexo_14' ? 'selected' : '' }}>Anexo 14</option>
                        <option value="anexo_13" {{ request('tipo') == 'anexo_13' ? 'selected' : '' }}>Anexo 13</option>
                        <option value="evento_publico" {{ request('tipo') == 'evento_publico' ? 'selected' : '' }}>Evento Público</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label fw-bold small">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                        <option value="suspendido" {{ request('estado') == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label fw-bold small">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label fw-bold small">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-1 col-12 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('licencias.index') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- RESULTADOS --}}
<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">
                Mostrando <strong>{{ $licencias->total() }}</strong> resultado(s)
            </small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>N° Certificado</th>
                        <th>Tipo</th>
                        <th>Titular</th>
                        <th>Nombre / Evento</th>
                        <th>Estado</th>
                        <th>Fecha Emisión</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($licencias as $licencia)
                    <tr>
                        <td><strong>{{ $licencia->numero_licencia }}</strong></td>
                        <td>
                            @if($licencia->tipo_certificado == 'anexo_14')
                                <span class="badge bg-danger">Anexo 14</span>
                            @elseif($licencia->tipo_certificado == 'anexo_13')
                                <span class="badge bg-warning text-dark">Anexo 13</span>
                            @else
                                <span class="badge bg-primary">Evento</span>
                            @endif
                        </td>
                        <td>{{ $licencia->contribuyente->nombres_razon_social }}</td>
                        <td>{{ $licencia->nombre_comercial ?? $licencia->nombre_evento }}</td>
                        <td>
                            <span class="badge badge-{{ $licencia->estado }}">
                                {{ strtoupper($licencia->estado) }}
                            </span>
                        </td>
                        <td>{{ $licencia->fecha_emision ? \Carbon\Carbon::parse($licencia->fecha_emision)->format('d/m/Y') : '-' }}</td>
                        <td>
                            {{-- Ver: todos pueden --}}
                            <a href="{{ route('licencias.show', $licencia) }}" class="btn btn-sm btn-info" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Editar: solo admin e ingeniero --}}
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('ingeniero'))
                                <a href="{{ route('licencias.edit', $licencia) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif

                            {{-- PDF: todos pueden --}}
                            <a href="{{ route('licencias.pdf', $licencia) }}" class="btn btn-sm btn-danger" target="_blank" title="PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>

                            {{-- Aprobar: solo admin e ingeniero --}}
                            @if($licencia->estado == 'pendiente' && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('ingeniero')))
                                <button type="button" class="btn btn-sm btn-success" title="Aprobar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAprobar"
                                    data-id="{{ $licencia->id }}"
                                    data-numero="{{ $licencia->numero_licencia }}">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif

                            {{-- Eliminar: solo admin --}}
                            @if(auth()->user()->hasRole('admin'))
                                <form action="{{ route('licencias.destroy', $licencia) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('¿Eliminar este certificado?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-secondary" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-search fa-2x mb-2 d-block"></i>
                            No se encontraron certificados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $licencias->appends(request()->query())->links() }}
    </div>
</div>

<!-- Modal Aprobar -->
<div class="modal fade" id="modalAprobar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Aprobar Certificado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAprobar" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Estás aprobando: <strong id="numeroLicencia"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fecha de Emisión</label>
                        <input type="date" name="fecha_emision" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check me-2"></i>Aprobar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('modalAprobar').addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    document.getElementById('numeroLicencia').textContent = button.getAttribute('data-numero');
    document.getElementById('formAprobar').action = '/licencias/' + button.getAttribute('data-id') + '/aprobar';
});
</script>
@endsection