<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Pública - Municipalidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(180deg, #12961dff 0%, #0f2812ff 100%); min-height: 100vh; }
        .hero { padding: 60px 0 40px; text-align: center; color: white; }
        .hero h1 { font-size: 28px; font-weight: 700; }
        .hero p { font-size: 15px; color: #adc8ff; }
        .search-card { border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-aprobado { background-color: #198754; }
        .badge-rechazado { background-color: #dc3545; }
        .badge-suspendido { background-color: #6c757d; }
        .result-card { border-radius: 10px; border: none; box-shadow: 0 3px 15px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .result-card:hover { transform: translateY(-3px); }
        .footer-public { text-align: center; color: #5a7aaa; font-size: 12px; padding: 20px; }
    </style>
</head>
<body>

<div class="hero">
    <i class="fas fa-landmark fa-3x mb-3" style="color:#4d9fff"></i>
    <h1>Municipalidad Distrital de<br>Andrés Avelino Cáceres Dorregaray</h1>
    <p><i class="fas fa-search me-2"></i>Consulta el estado de tu Licencia de Funcionamiento</p>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card search-card p-4 mb-4">
                <form action="{{ route('consulta.buscar') }}" method="POST">
                    @csrf
                    <label class="form-label fw-bold">
                        <i class="fas fa-search me-2 text-primary"></i>
                        Buscar por N° Licencia, DNI, RUC o Nombre
                    </label>
                    <div class="input-group">
                        <input type="text"
                            name="busqueda"
                            class="form-control form-control-lg @error('busqueda') is-invalid @enderror"
                            placeholder="Ej: LIC-2026-00001 o 12345678"
                            value="{{ $busqueda ?? '' }}"
                            required>
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                    @error('busqueda')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </form>
            </div>

            @isset($licencias)
                @if($licencias->count() > 0)
                    <p class="text-white mb-3">
                        <i class="fas fa-check-circle me-2 text-success"></i>
                        Se encontraron <strong>{{ $licencias->count() }}</strong> resultado(s)
                    </p>
                    @foreach($licencias as $licencia)
                    <div class="card result-card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-bold text-primary mb-1">
                                        {{ $licencia->numero_licencia }}
                                    </h5>
                                    <p class="mb-1">
                                        <i class="fas fa-store me-2 text-muted"></i>
                                        <strong>{{ $licencia->nombre_comercial }}</strong>
                                    </p>
                                    <p class="mb-1 text-muted small">
                                        <i class="fas fa-user me-2"></i>
                                        {{ $licencia->contribuyente->nombres_razon_social }}
                                    </p>
                                    <p class="mb-1 text-muted small">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        {{ $licencia->direccion_establecimiento }}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="badge badge-{{ $licencia->estado }} fs-6 mb-2">
                                        {{ strtoupper($licencia->estado) }}
                                    </span>
                                    <br>
                                    <a href="{{ route('consulta.detalle', $licencia) }}" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-eye me-1"></i>Ver detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="card search-card p-4 text-center">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron resultados</h5>
                        <p class="text-muted small">Verifica el número de licencia, DNI o RUC ingresado</p>
                    </div>
                @endif
            @endisset
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('solicitudes.seguimiento') }}" class="btn btn-lg btn-outline-light" style="border-width: 2px;">
                    <i class="fas fa-redo me-2"></i>Realizar nueva gestión
                </a>
                <a href="{{ route('solicitudes.formulario') }}" class="btn btn-lg btn-success">
                    <i class="fas fa-plus me-2"></i>Nueva solicitud
                </a>
                <a href="/" class="btn btn-lg btn-outline-light" style="border-width: 2px;">
                    <i class="fas fa-home me-2"></i>Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>

<div class="footer-public">
    <p>© {{ date('Y') }} Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
