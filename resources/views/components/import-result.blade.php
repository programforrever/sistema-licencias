@if(session()->has('import_result'))
@php
    $result = session('import_result');
    $porTipo = $result['por_tipo'] ?? [];
    $detalles = $result['detalles_omitidos'] ?? [];
    $errores = $result['errores'] ?? [];
@endphp

<!-- Resumen de Importación -->
<div class="alert alert-info border border-info mb-4" role="alert">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h5 class="alert-heading mb-3">
                <i class="fas fa-file-import me-2"></i>Resumen de Importación
            </h5>
            
            <!-- Totales -->
            <div class="row g-3 mb-3">
                <div class="col-auto">
                    <span class="badge bg-success p-2">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $result['total_importados'] }} Importados
                    </span>
                </div>
                @if($result['total_omitidos'] > 0)
                <div class="col-auto">
                    <span class="badge bg-warning text-dark p-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        {{ $result['total_omitidos'] }} Omitidos
                    </span>
                </div>
                @endif
            </div>

            <!-- Desglose por tipo -->
            <div class="row g-2 small">
                @foreach(['anexo_13' => 'ITSE Anexo 13', 'anexo_14' => 'ITSE Anexo 14', 'evento_publico' => 'Eventos Públicos'] as $key => $label)
                    @if(($porTipo[$key]['importados'] ?? 0) > 0 || ($porTipo[$key]['omitidos'] ?? 0) > 0)
                    <div class="col-md-3 col-6">
                        <div class="p-2 border rounded bg-white">
                            <strong>{{ $label }}</strong><br>
                            <span class="text-success">✓ {{ $porTipo[$key]['importados'] ?? 0 }} importados</span>
                            @if(($porTipo[$key]['omitidos'] ?? 0) > 0)
                            <br><span class="text-warning">⚠ {{ $porTipo[$key]['omitidos'] ?? 0 }} omitidos</span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<!-- Detalle de Omitidos -->
@if(count($detalles) > 0)
<div class="card border-warning mb-4 shadow-sm">
    <div class="card-header bg-warning text-dark">
        <i class="fas fa-info-circle me-2"></i>Registros Omitidos ({{ count($detalles) }})
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Los siguientes registros no fueron importados:</p>
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 15%">Tipo</th>
                        <th style="width: 10%">Hoja</th>
                        <th style="width: 10%">Fila</th>
                        <th style="width: 25%">Razón</th>
                        <th style="width: 40%">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detalles as $detalle)
                    <tr>
                        <td class="small">
                            <span class="badge bg-secondary">
                                {{ match($detalle['tipo']) {
                                    'anexo_13' => 'ITSE 13',
                                    'anexo_14' => 'ITSE 14',
                                    'evento_publico' => 'ECSE',
                                    default => $detalle['tipo']
                                } }}
                            </span>
                        </td>
                        <td class="small">{{ $detalle['hoja'] }}</td>
                        <td class="small"><strong>{{ $detalle['fila'] }}</strong></td>
                        <td class="small font-monospace">
                            @if($detalle['razon'] === 'Duplicado (ya existe)')
                                <span class="text-danger">🔄 Duplicado</span>
                            @elseif($detalle['razon'] === 'Fila vacía')
                                <span class="text-muted">⊘ Fila vacía</span>
                            @else
                                <span class="text-warning">⚠ {{ $detalle['razon'] }}</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $detalle['datos'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Errores encontrados -->
@if(count($errores) > 0)
<div class="card border-danger mb-4 shadow-sm">
    <div class="card-header bg-danger text-white">
        <i class="fas fa-exclamation-circle me-2"></i>Errores de Importación ({{ count($errores) }})
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush small">
            @foreach($errores as $error)
            <li class="list-group-item">
                <code class="text-danger">{{ $error }}</code>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@endif
