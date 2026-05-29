@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-upload me-2"></i>Importar Licencias Históricas</h2>
    <a href="{{ route('licencias-historicas.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="row">
    <!-- ITSE 13 -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-warning">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-certificate me-2"></i>ITSE 13 (Riesgo Bajo/Medio)
            </div>
            <div class="card-body">
                <form class="formImportacion" data-tipo="itse13">
                    @csrf
                    <input type="hidden" name="tipo" value="itse13">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Archivo Excel (.xlsx, .xls)</label>
                        <div class="input-group">
                            <input type="file" class="form-control archivo" accept=".xlsx,.xls" required>
                            <button class="btn btn-primary btnSubir" type="button">
                                <i class="fas fa-search me-2"></i>Previsualizar
                            </button>
                        </div>
                    </div>
                    
                    <div class="indicadorCarga" style="display:none;">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-spinner fa-spin"></i> Analizando...
                        </div>
                    </div>
                    
                    <div class="seccionPreview" style="display:none;">
                        <div class="alert alert-success mb-2">
                            <h6 class="mb-2"><i class="fas fa-check-circle me-2"></i>Resumen</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Total a importar</small>
                                    <strong class="totalRegistros">0</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Válidos</small>
                                    <strong class="registrosValidos text-success">0</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btnConfirmar" type="button">
                                <i class="fas fa-check me-2"></i>Importar
                            </button>
                            <button class="btn btn-secondary btnCancelar" type="button">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ITSE 14 -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-info">
            <div class="card-header bg-info text-white">
                <i class="fas fa-certificate me-2"></i>ITSE 14 (Riesgo Alto)
            </div>
            <div class="card-body">
                <form class="formImportacion" data-tipo="itse14">
                    @csrf
                    <input type="hidden" name="tipo" value="itse14">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Archivo Excel (.xlsx, .xls)</label>
                        <div class="input-group">
                            <input type="file" class="form-control archivo" accept=".xlsx,.xls" required>
                            <button class="btn btn-primary btnSubir" type="button">
                                <i class="fas fa-search me-2"></i>Previsualizar
                            </button>
                        </div>
                    </div>
                    
                    <div class="indicadorCarga" style="display:none;">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-spinner fa-spin"></i> Analizando...
                        </div>
                    </div>
                    
                    <div class="seccionPreview" style="display:none;">
                        <div class="alert alert-success mb-2">
                            <h6 class="mb-2"><i class="fas fa-check-circle me-2"></i>Resumen</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Total a importar</small>
                                    <strong class="totalRegistros">0</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Válidos</small>
                                    <strong class="registrosValidos text-success">0</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btnConfirmar" type="button">
                                <i class="fas fa-check me-2"></i>Importar
                            </button>
                            <button class="btn btn-secondary btnCancelar" type="button">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ECSE -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-success">
            <div class="card-header bg-success text-white">
                <i class="fas fa-users me-2"></i>ECSE (Eventos Públicos)
            </div>
            <div class="card-body">
                <form class="formImportacion" data-tipo="ecse">
                    @csrf
                    <input type="hidden" name="tipo" value="ecse">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Archivo Excel (.xlsx, .xls)</label>
                        <div class="input-group">
                            <input type="file" class="form-control archivo" accept=".xlsx,.xls" required>
                            <button class="btn btn-primary btnSubir" type="button">
                                <i class="fas fa-search me-2"></i>Previsualizar
                            </button>
                        </div>
                    </div>
                    
                    <div class="indicadorCarga" style="display:none;">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-spinner fa-spin"></i> Analizando...
                        </div>
                    </div>
                    
                    <div class="seccionPreview" style="display:none;">
                        <div class="alert alert-success mb-2">
                            <h6 class="mb-2"><i class="fas fa-check-circle me-2"></i>Resumen</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Total a importar</small>
                                    <strong class="totalRegistros">0</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Válidos</small>
                                    <strong class="registrosValidos text-success">0</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btnConfirmar" type="button">
                                <i class="fas fa-check me-2"></i>Importar
                            </button>
                            <button class="btn btn-secondary btnCancelar" type="button">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.formImportacion').forEach(form => {
    const tipo = form.dataset.tipo;
    const btnSubir = form.querySelector('.btnSubir');
    const btnConfirmar = form.querySelector('.btnConfirmar');
    const btnCancelar = form.querySelector('.btnCancelar');
    const archivo = form.querySelector('.archivo');
    const indicadorCarga = form.querySelector('.indicadorCarga');
    const seccionPreview = form.querySelector('.seccionPreview');
    const totalRegistros = form.querySelector('.totalRegistros');
    const registrosValidos = form.querySelector('.registrosValidos');
    
    let archivoTemporal = null;

    btnSubir.addEventListener('click', async function() {
        if (!archivo.files.length) {
            alert('Por favor selecciona un archivo');
            return;
        }

        indicadorCarga.style.display = 'block';
        seccionPreview.style.display = 'none';

        const formData = new FormData();
        formData.append('archivo', archivo.files[0]);
        formData.append('tipo', tipo);
        formData.append('_token', form.querySelector('[name="_token"]').value);

        try {
            const response = await fetch('{{ route("licencias-historicas.previsualizar") }}', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                alert('Error: ' + data.mensaje);
                indicadorCarga.style.display = 'none';
                return;
            }

            archivoTemporal = data.archivo_temporal;
            totalRegistros.textContent = data.totalRows;
            registrosValidos.textContent = data.totalRows - data.omitidos;

            indicadorCarga.style.display = 'none';
            seccionPreview.style.display = 'block';

        } catch (error) {
            console.error('Error:', error);
            alert('Error en la previsualización');
            indicadorCarga.style.display = 'none';
        }
    });

    btnConfirmar.addEventListener('click', async function() {
        if (!archivoTemporal) return;

        const formData = new FormData();
        formData.append('archivo_temporal', archivoTemporal);
        formData.append('tipo', tipo);
        formData.append('_token', form.querySelector('[name="_token"]').value);

        try {
            const response = await fetch('{{ route("licencias-historicas.confirmar") }}', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                alert('✅ ' + data.mensaje);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert('❌ ' + data.mensaje);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al importar');
        }
    });

    btnCancelar.addEventListener('click', function() {
        seccionPreview.style.display = 'none';
        archivo.value = '';
        archivoTemporal = null;
    });
});
</script>
@endsection
