<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Licencia - Municipalidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(180deg, #12961dff 0%, #0f2812ff 100%); min-height: 100vh; padding: 40px 0; }
        
        .detail-card { border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-aprobado { background-color: #198754; }
        .badge-rechazado { background-color: #dc3545; }
        .badge-suspendido { background-color: #6c757d; }
        .info-row { padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .info-row:last-child { border-bottom: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center text-white mb-4">
                <i class="fas fa-landmark fa-2x mb-2" style="color:#4d9fff"></i>
                <h4>Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</h4>
            </div>

            <div class="card detail-card">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Licencia de Funcionamiento
                    </h5>
                    <h3 class="mb-0 mt-1">{{ $licencia->numero_licencia }}</h3>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="badge badge-{{ $licencia->estado }} fs-5 px-4 py-2">
                            <i class="fas fa-circle me-2"></i>
                            {{ strtoupper($licencia->estado) }}
                        </span>
                    </div>

                    <div class="info-row d-flex justify-content-between">
                        <span class="text-muted"><i class="fas fa-store me-2"></i>Nombre Comercial</span>
                        <strong>{{ $licencia->nombre_comercial }}</strong>
                    </div>
                    <div class="info-row d-flex justify-content-between">
                        <span class="text-muted"><i class="fas fa-user me-2"></i>Titular</span>
                        <strong>{{ $licencia->contribuyente->nombres_razon_social }}</strong>
                    </div>
                    <div class="info-row d-flex justify-content-between">
                        <span class="text-muted"><i class="fas fa-id-card me-2"></i>DNI/RUC</span>
                        <strong>{{ $licencia->contribuyente->dni_ruc }}</strong>
                    </div>
                    <div class="info-row d-flex justify-content-between">
                        <span class="text-muted"><i class="fas fa-briefcase me-2"></i>Actividad</span>
                        <strong>{{ $licencia->actividadEconomica->descripcion }}</strong>
                    </div>
                    <div class="info-row d-flex justify-content-between">
                        <span class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Dirección</span>
                        <strong>{{ $licencia->direccion_establecimiento }}</strong>
                    </div>
                    @if($licencia->fecha_emision)
                    <div class="info-row d-flex justify-content-between">
                        <span class="text-muted"><i class="fas fa-calendar me-2"></i>Fecha Emisión</span>
                        <strong>{{ \Carbon\Carbon::parse($licencia->fecha_emision)->format('d/m/Y') }}</strong>
                    </div>
                    @endif
                    @if($licencia->fecha_vencimiento)
                    <div class="info-row d-flex justify-content-between">
                        <span class="text-muted"><i class="fas fa-calendar-times me-2"></i>Vencimiento</span>
                        <strong>{{ \Carbon\Carbon::parse($licencia->fecha_vencimiento)->format('d/m/Y') }}</strong>
                    </div>
                    @endif
                </div>
                <div class="card-footer text-center py-3">
                    <a href="{{ route('consulta.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="text-center text-muted mt-3" style="font-size:12px; color:#5a7aaa !important">
                <p>© {{ date('Y') }} Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


