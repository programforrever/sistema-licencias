@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-upload me-2"></i>Importar Licencias Históricas</h2>
    <a href="{{ route('licencias-historicas.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-file-excel me-2"></i>Seleccionar Archivo Excel
            </div>
            <div class="card-body">
                <form id="formImportacion">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Archivo Excel (.xlsx, .xls)</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="archivo" name="archivo" 
                                accept=".xlsx,.xls,.csv" required>
                            <button class="btn btn-primary" type="button" id="btnSubir">
                                <i class="fas fa-search me-2"></i>Previsualizar
                            </button>
                            <a href="{{ route('licencias-historicas.descargar-ejemplo') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-download me-2"></i>Descargar Ejemplo
                            </a>
                        </div>
                        <small class="text-muted d-block mt-2">
                            ℹ️ Formato esperado: Nº | ANEXO | FECHA* | INFORME Nº | EXPEDIENTE | ACTIVIDAD | NOMBRE | SOLICITANTE | UBICACIÓN<br/>
                            <small>*FECHA: DD/MM/YYYY, NNN - YYYY, o solo YYYY</small>
                        </small>
                    </div>

                    <!-- Indicador de carga -->
                    <div id="indicadorCarga" style="display:none;">
                        <div class="alert alert-info">
                            <i class="fas fa-spinner fa-spin"></i> Analizando archivo...
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Información</h6>
                <ul class="small mb-0">
                    <li>✅ Soporta hojas: ANEXO 13, ANEXO 14, ECSE</li>
                    <li>✅ Valida datos antes de importar</li>
                    <li>✅ Previsualiza los cambios</li>
                    <li>✅ Todo o nada (transacción segura)</li>
                    <li>⚠️ No importa duplicados</li>                    <li style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #ccc;">
                        <strong>Formatos de fecha (Col. C):</strong><br/>
                        • DD/MM/YYYY (ej: 15/03/2024)<br/>
                        • NNN - YYYY (ej: 008 - 2024)<br/>
                        • Solo YYYY (ej: 2024)
                    </li>                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Preview de Datos -->
<div id="seccionPreview" style="display:none; margin-top: 30px;">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <i class="fas fa-eye me-2"></i>Previsualización de Datos
        </div>
        <div class="card-body">
            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                        <h4 class="text-primary mb-1" id="totalRegistros">0</h4>
                        <small>Total a importar</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                        <h4 class="text-success mb-1" id="registrosValidos">0</h4>
                        <small>Válidos</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                        <h4 class="text-warning mb-1" id="registrosOmitidos">0</h4>
                        <small>Omitidos</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                        <h4 class="text-danger mb-1" id="registrosErrores">0</h4>
                        <small>Errores</small>
                    </div>
                </div>
            </div>

            <!-- Errores -->
            <div id="seccionErrores" style="display:none; margin-bottom: 20px;">
                <div class="alert alert-danger">
                    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Problemas Detectados</h6>
                    <ul id="listaErrores" class="mb-0"></ul>
                </div>
            </div>

            <!-- Tabla Preview -->
            <div class="table-responsive mb-4">
                <table class="table table-sm table-hover" id="tablaPreview">
                    <thead>
                        <tr>
                            <th>Nº Licencia</th>
                            <th>Tipo</th>
                            <th>Solicitante</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTabla">
                    </tbody>
                </table>
            </div>

            <!-- Botones de acción -->
            <div class="d-flex gap-2">
                <button class="btn btn-success" id="btnConfirmar">
                    <i class="fas fa-check me-2"></i>Confirmar Importación
                </button>
                <button class="btn btn-secondary" id="btnCancelar">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Import -->
<div id="seccionImportando" style="display:none; margin-top: 30px;">
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Importando...</span>
            </div>
            <h5>Importando datos...</h5>
            <small class="text-muted">No cierres esta ventana</small>
        </div>
    </div>
</div>

<script>
let archivoTemporal = null;

// Helper para obtener CSRF token de forma segura
function obtenerCSRFToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) {
        console.error('❌ Token CSRF no encontrado en el HTML');
        throw new Error('Token CSRF no disponible en esta sesión');
    }
    return meta.content || meta.getAttribute('content');
}

document.getElementById('btnSubir').addEventListener('click', async function() {
    const archivo = document.getElementById('archivo').files[0];
    
    if (!archivo) {
        alert('Por favor selecciona un archivo');
        return;
    }

    const formData = new FormData();
    formData.append('archivo', archivo);

    document.getElementById('indicadorCarga').style.display = 'block';

    try {
        const csrfToken = obtenerCSRFToken();
        
        const response = await fetch('{{ route("licencias-historicas.previsualizar") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        const data = await response.json();

        if (!data.success) {
            let mensajeError = data.mensaje || 'Error desconocido';
            
            // Si hay información de debug, mostrarla
            if (data.debug) {
                mensajeError += '\n\nDetalles: ' + data.debug;
            }
            
            // Si hay errores específicos, mostrarlos
            if (data.errores && data.errores.length > 0) {
                mensajeError += '\n\nProblemas encontrados:\n' + data.errores.map(e => '• ' + e).join('\n');
            }
            
            alert('❌ ' + mensajeError);
            document.getElementById('indicadorCarga').style.display = 'none';
            return;
        }

        // Validar que hay datos para importar
        if (data.estadisticas.total === 0) {
            let detalles = 'No se encontraron datos válidos para importar.';
            if (data.errores && data.errores.length > 0) {
                detalles += '\n\nProblemas:\n' + data.errores.map(e => '• ' + e).join('\n');
            }
            alert('⚠️ ' + detalles);
            document.getElementById('indicadorCarga').style.display = 'none';
            return;
        }

        // Actualizar estadísticas
        document.getElementById('totalRegistros').textContent = data.estadisticas.total;
        document.getElementById('registrosValidos').textContent = data.estadisticas.aImportar;
        document.getElementById('registrosOmitidos').textContent = data.estadisticas.omitidos;
        document.getElementById('registrosErrores').textContent = data.errores.length;

        // Mostrar errores si los hay
        if (data.errores.length > 0) {
            document.getElementById('seccionErrores').style.display = 'block';
            const listaErrores = document.getElementById('listaErrores');
            listaErrores.innerHTML = data.errores.map(err => `<li>${err}</li>`).join('');
        } else {
            document.getElementById('seccionErrores').style.display = 'none';
        }

        // Llenar tabla preview
        const cuerpo = document.getElementById('cuerpoTabla');
        cuerpo.innerHTML = data.preview.map(item => `
            <tr>
                <td><strong>${item.numero_licencia}</strong></td>
                <td><span class="badge bg-info">${item.tipo_nombre ? item.tipo_nombre.substring(0, 12) : item.tipo_certificado}</span></td>
                <td>${item.solicitante}</td>
                <td><small>${item.ubicacion || '-'}</small></td>
                <td>${new Date(item.fecha_emision).toLocaleDateString('es-ES')}</td>
                <td>
                    <span class="badge ${item.estado === 'vigente' ? 'bg-success' : (item.estado === 'vencido' ? 'bg-danger' : 'bg-secondary')}">
                        ${item.estado}
                    </span>
                </td>
            </tr>
        `).join('');

        archivoTemporal = data.archivo_temporal;
        document.getElementById('seccionPreview').style.display = 'block';
        document.getElementById('indicadorCarga').style.display = 'none';

    } catch (error) {
        console.error('❌ Error:', error);
        alert('❌ Error al procesar el archivo:\n' + error.message);
        document.getElementById('indicadorCarga').style.display = 'none';
    }
});

document.getElementById('btnConfirmar').addEventListener('click', async function() {
    if (!archivoTemporal) {
        alert('Error: archivo temporal no disponible');
        return;
    }

    document.getElementById('seccionPreview').style.display = 'none';
    document.getElementById('seccionImportando').style.display = 'block';

    try {
        const csrfToken = obtenerCSRFToken();
        
        const response = await fetch('{{ route("licencias-historicas.confirmar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                archivo_temporal: archivoTemporal
            })
        });

        const data = await response.json();

        if (data.success) {
            alert(`✅ ${data.mensaje}`);
            setTimeout(() => {
                window.location.href = '{{ route("licencias-historicas.index") }}';
            }, 1000);
        } else {
            alert(`❌ ${data.mensaje}`);
            document.getElementById('seccionImportando').style.display = 'none';
            document.getElementById('seccionPreview').style.display = 'block';
        }
    } catch (error) {
        console.error('❌ Error:', error);
        alert('❌ Error durante la importación:\n' + error.message);
        document.getElementById('seccionImportando').style.display = 'none';
        document.getElementById('seccionPreview').style.display = 'block';
    }
});

document.getElementById('btnCancelar').addEventListener('click', function() {
    document.getElementById('seccionPreview').style.display = 'none';
    document.getElementById('archivo').value = '';
    archivoTemporal = null;
});
</script>
@endsection
