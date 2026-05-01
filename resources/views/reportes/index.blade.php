@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-bar me-2"></i>Reportes y Estadísticas</h2>
    <form action="{{ route('reportes.index') }}" method="GET" class="d-flex gap-2">
        <select name="anio" class="form-select form-select-sm" style="width:120px;">
            @foreach($anios as $a)
                <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>{{ $a }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter me-1"></i>Filtrar
        </button>
    </form>
</div>

{{-- CARDS RESUMEN --}}
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card shadow-sm border-0 text-center" style="background:linear-gradient(135deg,#1a3c6e,#0d2244);">
            <div class="card-body text-white py-3">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <h3 class="mb-0">{{ $totalCertificados }}</h3>
                <small>Total General</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm border-0 text-center" style="background:linear-gradient(135deg,#198754,#126a40);">
            <div class="card-body text-white py-3">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <h3 class="mb-0">{{ $totalAprobados }}</h3>
                <small>Aprobados</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm border-0 text-center" style="background:linear-gradient(135deg,#ffc107,#e0a800); color:#000;">
            <div class="card-body py-3">
                <i class="fas fa-clock fa-2x mb-2"></i>
                <h3 class="mb-0">{{ $totalPendientes }}</h3>
                <small>Pendientes</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm border-0 text-center" style="background:linear-gradient(135deg,#dc3545,#b02a37);">
            <div class="card-body text-white py-3">
                <i class="fas fa-times-circle fa-2x mb-2"></i>
                <h3 class="mb-0">{{ $totalRechazados }}</h3>
                <small>Rechazados</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm border-0 text-center" style="background:linear-gradient(135deg,#0dcaf0,#0aa2c0);">
            <div class="card-body text-white py-3">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h3 class="mb-0">{{ $totalContribuyentes }}</h3>
                <small>Contribuyentes</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm border-0 text-center" style="background:linear-gradient(135deg,#fd7e14,#dc6502);">
            <div class="card-body text-white py-3">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <h3 class="mb-0">{{ $proximosVencer->count() }}</h3>
                <small>Por Vencer</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- GRÁFICO POR MES --}}
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-chart-line me-2"></i>Certificados por Mes — {{ $anio }}
            </div>
            <div class="card-body">
                <canvas id="graficaMes" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- GRÁFICO POR TIPO --}}
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-chart-pie me-2"></i>Por Tipo de Certificado
            </div>
            <div class="card-body">
                <canvas id="graficaTipo" height="200"></canvas>
                <div class="mt-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span><i class="fas fa-circle text-danger me-1"></i>Anexo 14 (Alto Riesgo)</span>
                        <strong>{{ $porTipo['anexo_14'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span><i class="fas fa-circle text-warning me-1"></i>Anexo 13 (Bajo/Medio)</span>
                        <strong>{{ $porTipo['anexo_13'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span><i class="fas fa-circle text-primary me-1"></i>Evento Público</span>
                        <strong>{{ $porTipo['evento_publico'] }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- PRÓXIMOS A VENCER --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Próximos a Vencer (30 días)</strong>
                </div>
                <a href="{{ route('reportes.vencer-pdf') }}" target="_blank" class="btn btn-sm btn-dark">
                    <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                </a>
            </div>
            <div class="card-body p-0">
                @if($proximosVencer->count() > 0)
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>N° Certificado</th>
                            <th>Titular</th>
                            <th>Vence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proximosVencer as $lic)
                        <tr>
                            <td><small>{{ $lic->numero_licencia }}</small></td>
                            <td><small>{{ $lic->contribuyente->nombres_razon_social }}</small></td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ \Carbon\Carbon::parse($lic->fecha_vencimiento)->format('d/m/Y') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-2x mb-2 d-block text-success"></i>
                    No hay certificados próximos a vencer
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ÚLTIMOS CERTIFICADOS --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-history me-2"></i>Últimos 10 Certificados
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>N° Certificado</th>
                            <th>Titular</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimos as $lic)
                        <tr>
                            <td><small>{{ $lic->numero_licencia }}</small></td>
                            <td><small>{{ $lic->contribuyente->nombres_razon_social }}</small></td>
                            <td>
                                <span class="badge badge-{{ $lic->estado }}" style="font-size:10px;">
                                    {{ strtoupper($lic->estado) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxMes = document.getElementById('graficaMes').getContext('2d');
new Chart(ctxMes, {
    type: 'bar',
    data: {
        labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        datasets: [{
            label: 'Certificados emitidos',
            data: {{ json_encode($porMes) }},
            backgroundColor: 'rgba(26, 60, 110, 0.7)',
            borderColor: '#1a3c6e',
            borderWidth: 1,
            borderRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

const ctxTipo = document.getElementById('graficaTipo').getContext('2d');
new Chart(ctxTipo, {
    type: 'doughnut',
    data: {
        labels: ['Anexo 14', 'Anexo 13', 'Evento Público'],
        datasets: [{
            data: [{{ $porTipo['anexo_14'] }}, {{ $porTipo['anexo_13'] }}, {{ $porTipo['evento_publico'] }}],
            backgroundColor: ['#dc3545', '#ffc107', '#0d6efd'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection