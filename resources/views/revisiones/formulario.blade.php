<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Revisión - {{ $solicitud->codigo_seguimiento }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #136d2e 0%, #03420f 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container-main {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            margin: 20px auto;
            max-width: 800px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #009100;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            color: #7f8c8d;
            font-size: 14px;
        }
        .solicitud-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #029f17;
        }
        .solicitud-info h5 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 10px;
        }
        .info-item {
            padding: 8px 0;
        }
        .info-item strong {
            color: #2c3e50;
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            color: #05970f;
            margin-bottom: 4px;
        }
        .info-item span {
            color: #555;
            font-size: 15px;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section h5 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        .form-group label {
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }
        .form-control, .form-select {
            border: 1px solid #ddd;
            padding: 10px 12px;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #008e00;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .resultado-btns {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .resultado-btn {
            padding: 12px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
            font-weight: 500;
        }
        .resultado-btn:hover {
            border-color: #0b8a3e;
        }
        .resultado-btn.selected {
            border-color: #008b10;
            background: #008514;
            color: white;
        }
        .resultado-btn.aprobado.selected {
            background: #27ae60;
            border-color: #27ae60;
        }
        .resultado-btn.requiere.selected {
            background: #f39c12;
            border-color: #f39c12;
        }
        .resultado-btn.rechazado.selected {
            background: #e74c3c;
            border-color: #e74c3c;
        }
        .documento-upload {
            border: 2px dashed #006813;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .documento-upload:hover {
            background: #f0f0ff;
        }
        .documento-upload input[type="file"] {
            display: none;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .btn-enviar {
            flex: 1;
            background: #27ae60;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-enviar:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .revisada {
            background: #e8f5e9;
            border: 2px solid #27ae60;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        .revisada h5 {
            color: #27ae60;
            margin-bottom: 10px;
        }
        .revisada p {
            color: #555;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="header">
            <h1>📋 Formulario de Revisión</h1>
            <p>Solicitud: <strong>{{ $solicitud->codigo_seguimiento }}</strong></p>
        </div>

        <!-- Alerta si ya fue revisada -->
        @if($revision_existente)
            <div class="revisada">
                <h5>✅ Revisión Registrada</h5>
                <p>Tu revisión fue registrada el {{ $revision_existente->created_at->format('d/m/Y H:i') }}</p>
                <p>Resultado: <strong>{{ ucfirst(str_replace('_', ' ', $revision_existente->resultado_revision)) }}</strong></p>
            </div>
        @endif

        <!-- Mostrar mensajes de validación -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <h5>⚠️ Errores en el formulario</h5>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Información de la solicitud -->
        <div class="solicitud-info">
            <h5>Información de la Solicitud</h5>
            <div class="info-row">
                <div class="info-item">
                    <strong>Solicitante</strong>
                    <span>{{ $solicitud->nombres_solicitante }}</span>
                </div>
                <div class="info-item">
                    <strong>Tipo de Certificado</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $solicitud->tipo_certificado)) }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <strong>Email</strong>
                    <span>{{ $solicitud->email }}</span>
                </div>
                <div class="info-item">
                    <strong>Teléfono</strong>
                    <span>{{ $solicitud->telefono_whatsapp }}</span>
                </div>
            </div>
            @if($solicitud->nombre_comercial)
                <div class="info-row">
                    <div class="info-item">
                        <strong>Nombre Comercial</strong>
                        <span>{{ $solicitud->nombre_comercial }}</span>
                    </div>
                </div>
            @endif
            <div class="info-row">
                <div class="info-item">
                    <strong>Dirección</strong>
                    <span>{{ $solicitud->direccion }}</span>
                </div>
                <div class="info-item">
                    <strong>Actividad</strong>
                    <span>{{ $solicitud->actividad ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Formulario de revisión -->
        <form action="{{ route('revision.guardar', $revisor->token_revisor) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Resultado de revisión -->
            <div class="form-section">
                <h5>1. Resultado de la Revisión</h5>
                <label>Selecciona el resultado:</label>
                <div class="resultado-btns">
                    <label class="resultado-btn aprobado" onclick="selectResultado(this, 'aprobado')">
                        <input type="radio" name="resultado_revision" value="aprobado" style="display:none;">
                        <span>✅ Aprobado</span>
                    </label>
                    <label class="resultado-btn requiere" onclick="selectResultado(this, 'requiere_cambios')">
                        <input type="radio" name="resultado_revision" value="requiere_cambios" style="display:none;">
                        <span>⚠️ Requiere Cambios</span>
                    </label>
                    <label class="resultado-btn rechazado" onclick="selectResultado(this, 'rechazado')">
                        <input type="radio" name="resultado_revision" value="rechazado" style="display:none;">
                        <span>❌ Rechazado</span>
                    </label>
                </div>
            </div>

            <!-- Notas -->
            <div class="form-section">
                <h5>2. Notas de la Revisión</h5>
                <label for="notas">Escriba sus observaciones, comentarios o recomendaciones (mínimo 5 caracteres):</label>
                <textarea class="form-control" name="notas" id="notas" rows="6" placeholder="Indique aquí sus notas, observaciones o cambios requeridos..." required></textarea>
                <small class="text-muted">Este campo es obligatorio</small>
            </div>

            <!-- Documentos -->
            <div class="form-section">
                <h5>3. Documentos de Respaldo (Opcional)</h5>
                <label for="documento_revision">Adjunte documento de revisión o evidencia:</label>
                <div class="documento-upload" onclick="document.getElementById('documento_revision').click()">
                    <input type="file" name="documento_revision" id="documento_revision" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <div>
                        <p style="margin: 0; color: #667eea; font-weight: 600;">📎 Haga clic o arrastre un archivo</p>
                        <p style="margin: 5px 0 0 0; color: #999; font-size: 12px;">PDF, DOC, DOCX, JPG, PNG (máx 10MB)</p>
                    </div>
                </div>
                <small id="fileName" class="text-muted d-block mt-2"></small>
            </div>

            <!-- Botones -->
            <div class="button-group">
                <button type="submit" class="btn-enviar">✅ Enviar Revisión</button>
            </div>

            <p style="text-align: center; color: #999; margin-top: 20px; font-size: 12px;">
                Al enviar este formulario, sus datos serán guardados en el sistema.
            </p>
        </form>
    </div>

    <script>
        function selectResultado(element, value) {
            // Remover selected de todos los botones
            document.querySelectorAll('.resultado-btn').forEach(btn => {
                btn.classList.remove('selected');
            });
            // Agregar selected al clickeado
            element.classList.add('selected');
            // Marcar el radio button
            element.querySelector('input[type="radio"]').checked = true;
        }

        // Mostrar nombre del archivo
        document.getElementById('documento_revision').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('fileName').textContent = fileName ? '✓ ' + fileName : '';
        });

        // Drag and drop
        const uploadArea = document.querySelector('.documento-upload');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.style.background = '#f0f0ff';
        }

        function unhighlight(e) {
            uploadArea.style.background = 'white';
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('documento_revision').files = files;
            const fileName = files[0]?.name || '';
            document.getElementById('fileName').textContent = fileName ? '✓ ' + fileName : '';
        }

        // Restaurar antes seleccionado si existe
        window.addEventListener('DOMContentLoaded', function() {
            const selectedValue = document.querySelector('input[name="resultado_revision"]:checked')?.value;
            if (selectedValue) {
                const button = document.querySelector(`input[value="${selectedValue}"]`).parentElement;
                button.classList.add('selected');
            }
        });
    </script>
</body>
</html>
