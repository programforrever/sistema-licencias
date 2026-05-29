<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Subir Comprobante de Pago - Municipalidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .fa, .fas, .far, .fab, .fa-brands, .fa-classic, .fa-regular, .fa-solid, .fa-sharp { font-family: var(--fa-style-family,"Font Awesome 6 Free") !important; }

        :root {
            --brand:        #2563eb;
            --brand-light:  #eff6ff;
            --brand-dark:   #1e40af;
            --surface:      #ffffff;
            --bg:           #f8fafc;
            --border:       #e2e8f0;
            --text-main:    #0f172a;
            --text-muted:   #64748b;
            --green-main:   #12961d;
            --green-dark:   #0f2812;
            --success-light: #f0fdf4;
            --success-main: #16a34a;
        }

        body {
            background: linear-gradient(180deg, var(--green-main) 0%, var(--green-dark) 100%);
            min-height: 100vh;
            padding: 30px 0;
        }

        .upload-card {
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            background: var(--surface);
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--success-main);
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .info-box {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.25rem;
            margin: 1.25rem 0;
        }

        .info-row {
            display: flex;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-child { border-bottom: none; }

        .info-label {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            width: 120px;
            flex-shrink: 0;
        }

        .info-value {
            color: var(--text-main);
            font-size: 0.85rem;
        }

        .drop-zone {
            border: 2px dashed var(--green-main);
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
            cursor: pointer;
            transition: all 0.3s;
            margin: 1.5rem 0;
        }

        .drop-zone:hover {
            border-color: var(--green-dark);
            background: linear-gradient(135deg, #e8f5e9 0%, #d1fae5 100%);
        }

        .drop-zone.dragover {
            border-color: var(--green-dark);
            background: linear-gradient(135deg, #d1fae5 0%, #c1fae5 100%);
        }

        .drop-zone-icon {
            font-size: 3rem;
            color: var(--green-main);
            margin-bottom: 1rem;
        }

        .drop-zone-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .drop-zone-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        #file-input {
            display: none;
        }

        .file-name {
            margin-top: 1rem;
            padding: 0.75rem;
            background: white;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 0.85rem;
            color: var(--text-main);
        }

        .btn-submit {
            background: var(--success-main);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.8rem 1.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.15s;
        }

        .btn-submit:hover {
            background: var(--green-main);
            color: white;
        }

        .btn-submit:disabled {
            background: var(--text-muted);
            cursor: not-allowed;
        }

        .alert-info-pdf {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bae6fd;
            border-left: 4px solid var(--brand);
            color: #0c4a6e;
            border-radius: 10px;
            padding: 1rem;
            font-size: 0.85rem;
            margin: 1.25rem 0;
        }

        .badge-codigo {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: var(--success-light);
            color: var(--green-main);
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .drop-zone {
                padding: 25px;
            }
            .header-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="upload-card p-4">
                {{-- HEADER --}}
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div class="header-title">
                        <i class="fas fa-file-upload me-2"></i>Subir Comprobante de Pago
                    </div>
                    <div class="header-subtitle">
                        Adjunta tu comprobante de pago en PDF para confirmar la transacción
                    </div>
                </div>

                {{-- INFO DE SOLICITUD --}}
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-ticket me-2"></i>Código</span>
                        <span class="info-value">
                            <span class="badge-codigo">{{ $solicitud->codigo_seguimiento }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-file-alt me-2"></i>Tipo</span>
                        <span class="info-value">
                            @if($solicitud->tipo_certificado == 'evento_publico')
                                Evento Público
                            @elseif($solicitud->tipo_certificado == 'anexo_13')
                                ITSE Anexo 13
                            @else
                                ITSE Anexo 14
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-money-bill-wave me-2"></i>Monto</span>
                        <span class="info-value">
                            <strong>S/. {{ number_format($solicitud->getMontoPagoCalculado(), 2, '.', '') }}</strong>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-user me-2"></i>Solicitante</span>
                        <span class="info-value">{{ $solicitud->nombres_solicitante }}</span>
                    </div>
                </div>

                {{-- ALERT INFO PDF --}}
                <div class="alert-info-pdf">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Requisito:</strong> El archivo puede ser PDF o una captura (JPG, PNG) con tamaño máximo de 10 MB
                </div>

                {{-- FORMULARIO --}}
                <form action="{{ route('solicitudes.guardar.comprobante', $solicitud->codigo_seguimiento) }}" 
                      method="POST" enctype="multipart/form-data" id="formComprobante">
                    @csrf

                    {{-- DROP ZONE --}}
                    <div class="drop-zone" id="dropZone" onclick="document.getElementById('file-input').click();">
                        <div class="drop-zone-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="drop-zone-title">Arrastra tu archivo aquí</div>
                        <div class="drop-zone-subtitle">o haz clic para seleccionar</div>
                        <input type="file" id="file-input" name="comprobante_pago" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>

                    {{-- NOMBRE DEL ARCHIVO --}}
                    <div id="file-name-container" style="display: none;">
                        <div class="file-name">
                            <i class="fas fa-file me-2" style="color: #2563eb;"></i>
                            <span id="file-name-text"></span>
                            <button type="button" class="btn btn-sm btn-link float-end p-0" onclick="limpiarArchivo()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ERRORES --}}
                    @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Error:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- BOTONES --}}
                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <a href="{{ route('solicitudes.seguimiento', ['codigo' => $solicitud->codigo_seguimiento]) }}" 
                           class="btn btn-outline-secondary" style="border-radius: 8px; padding: 0.8rem 1.5rem;">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" id="btnSubmit" class="btn btn-submit" disabled>
                            <i class="fas fa-upload me-1"></i>Subir Comprobante
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file-input');
    const fileNameContainer = document.getElementById('file-name-container');
    const fileNameText = document.getElementById('file-name-text');
    const btnSubmit = document.getElementById('btnSubmit');

    // Drag y drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('dragover');
        });
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        mostrarArchivo();
    });

    fileInput.addEventListener('change', () => {
        mostrarArchivo();
    });

    function mostrarArchivo() {
        if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];
            const tiposPermitidos = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            
            // Validar que sea PDF o imagen
            if (!tiposPermitidos.includes(file.type)) {
                alert('❌ Solo se aceptan archivos PDF, JPG o PNG');
                fileInput.value = '';
                btnSubmit.disabled = true;
                fileNameContainer.style.display = 'none';
                return;
            }

            // Validar tamaño (10 MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('❌ El archivo no debe superar 10 MB');
                fileInput.value = '';
                btnSubmit.disabled = true;
                fileNameContainer.style.display = 'none';
                return;
            }

            fileNameText.textContent = file.name;
            fileNameContainer.style.display = 'block';
            btnSubmit.disabled = false;
        }
    }

    function limpiarArchivo() {
        fileInput.value = '';
        fileNameContainer.style.display = 'none';
        btnSubmit.disabled = true;
    }
</script>
</body>
</html>
