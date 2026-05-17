@extends('layouts.app')

@section('content')
<style>
    body.dark-mode {
        --bg-primary: #0f172a;
        --bg-secondary: #1a2540;
        --text-primary: #f0f4f8;
        --text-secondary: #a0aac0;
        --border-color: #2a3a55;
        --card-bg: #1a2540;
    }

    .firma-container {
        background: var(--bg-primary);
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    .firma-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .firma-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .firma-grid {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* PDF Preview Panel */
    .pdf-panel {
        background: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
    }

    .pdf-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .pdf-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pdf-viewer {
        flex: 1;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f0f4f8 0%, #e5e9f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 650px;
        position: relative;
        overflow: auto;
    }

    body.dark-mode .pdf-viewer {
        background: linear-gradient(135deg, #1e2d47 0%, #1a2540 100%);
    }

    #pdf-canvas {
        background: white;
        border-radius: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        cursor: grab;
        max-width: 100%;
        height: auto;
    }

    .pdf-hint {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(59, 130, 246, 0.1);
        border-left: 4px solid #3b82f6;
        border-radius: 6px;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Controls Panel */
    .controls-panel {
        background: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        height: fit-content;
        position: sticky;
        top: 1rem;
    }

    .controls-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .controls-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .controls-body {
        padding: 1.5rem;
        flex: 1;
        overflow-y: auto;
        max-height: 600px;
    }

    /* Firma Preview */
    .firma-preview-box {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border: 2px solid #86efac;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .firma-preview-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #166534;
        margin: 0 0 0.75rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    #firma-img {
        width: 100%;
        height: 120px;
        object-fit: contain;
        background: white;
        border-radius: 8px;
        border: 1px solid #d1fae5;
        padding: 0.75rem;
        display: block;
    }

    /* Control Inputs */
    .control-group {
        margin-bottom: 1rem;
    }

    .control-group:last-child {
        margin-bottom: 0;
    }

    .control-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .control-input {
        width: 100%;
        padding: 0.75rem;
        border: 1.5px solid var(--border-color);
        border-radius: 8px;
        background: rgba(0, 0, 0, 0.2);
        color: var(--text-primary);
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .control-input:focus {
        outline: none;
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Buttons */
    .buttons-group {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .btn-firma {
        padding: 0.875rem;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-success:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        font-size: 0.85rem;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(107, 114, 128, 0.4);
        background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
    }

    .adjustment-hint {
        display: none;
        padding: 0.75rem;
        background: rgba(59, 130, 246, 0.1);
        border-left: 3px solid #3b82f6;
        border-radius: 6px;
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
    }

    .adjustment-hint.show {
        display: block;
    }

    /* Mensajes */
    .mensaje-container {
        margin-top: 1rem;
    }

    .mensaje {
        padding: 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        display: none;
        animation: slideIn 0.3s ease-out;
    }

    .mensaje.show {
        display: block;
    }

    .mensaje.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
        border: 1px solid #86efac;
        color: #047857;
    }

    .mensaje.error {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
        border: 1px solid #fca5a5;
        color: #dc2626;
    }

    .mensaje.info {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(59, 130, 246, 0.1));
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Barra de Acciones Flotante */
    .action-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #1a2540 0%, #0f172a 100%);
        border-top: 2px solid #3b82f6;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        padding: 1rem 1.5rem;
    }

    .action-bar-content {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .action-bar .btn-firma {
        padding: 0.875rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        white-space: nowrap;
        flex: 0 1 auto;
    }

    /* Ajustar contenedor para que no quede bajo la barra */
    .firma-container {
        padding-bottom: 120px;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .firma-grid {
            grid-template-columns: 1fr;
        }

        .controls-panel {
            position: static;
        }

        .pdf-viewer {
            min-height: 500px;
        }

        .action-bar {
            padding: 0.875rem 1rem;
        }

        .action-bar-content {
            gap: 0.75rem;
        }

        .action-bar .btn-firma {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            flex: 1 1 calc(50% - 0.375rem);
        }

        .firma-container {
            padding-bottom: 160px;
        }
    }

    @media (max-width: 640px) {
        .action-bar .btn-firma {
            flex: 1 1 100%;
        }

        .firma-container {
            padding-bottom: 200px;
        }

        .action-bar-content {
            flex-direction: column;
        }
    }
</style>

<div class="firma-container">
    <div class="firma-header">
        <h1>
            @if($licencia->signature_status === 'firmado')
                ✏️ Editar Firma
            @else
                🖊️ Firmar Certificado
            @endif
            <span style="font-size: 1.5rem; color: var(--text-secondary);">#{{ $licencia->numero_licencia }}</span>
        </h1>
    </div>

    <div class="firma-grid">
        <!-- Panel PDF -->
        <div class="pdf-panel">
            <div class="pdf-header">
                <h2>
                    <i class="fas fa-file-pdf"></i>
                    Vista Previa del Certificado
                </h2>
            </div>
            <div class="pdf-viewer">
                <canvas id="pdf-canvas"></canvas>
            </div>
            <div class="pdf-hint">
                <i class="fas fa-info-circle me-2"></i>
                Haz clic en "Colocar Firma en PDF" para mostrar tu firma. Luego arrastra para ajustar la posición.
            </div>
        </div>

        <!-- Panel Controles -->
        <div class="controls-panel">
            <div class="controls-header">
                <h2>
                    <i class="fas fa-sliders-h"></i>
                    Controles
                </h2>
            </div>
            <div class="controls-body">
                <!-- Preview Firma -->
                <div class="firma-preview-box">
                    <p class="firma-preview-title">
                        <i class="fas fa-signature"></i>
                        Tu Firma
                    </p>
                    <img id="firma-img" src="" alt="Tu firma">
                </div>

                <!-- Inputs de Posición -->
                <div class="control-group">
                    <label class="control-label">📍 X (izquierda)</label>
                    <input type="number" id="posX" value="150" min="0" class="control-input">
                </div>

                <div class="control-group">
                    <label class="control-label">📍 Y (arriba)</label>
                    <input type="number" id="posY" value="400" min="0" class="control-input">
                </div>

                <div class="control-group">
                    <label class="control-label">📏 Ancho</label>
                    <input type="number" id="ancho" value="80" min="1" max="200" class="control-input">
                </div>

                <div class="control-group" style="margin-bottom: 1.5rem;">
                    <label class="control-label">📐 Alto</label>
                    <input type="number" id="alto" value="40" min="1" max="200" class="control-input">
                </div>

                <!-- Solo info aquí -->
                <div id="controles-ajuste" style="display: none;">
                    <div class="adjustment-hint show">
                        <i class="fas fa-arrow-pointer me-1"></i>
                        Arrastra la firma para mover o ajusta los valores
                    </div>
                </div>

                <!-- Mensaje -->
                <div id="estado-mensaje" class="mensaje-container">
                    <div id="mensaje" class="mensaje"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BARRA DE ACCIONES FLOTANTE -->
<div class="action-bar">
    <div class="action-bar-content">
        <button id="btn-colocar-firma" class="btn-firma btn-primary">
            <i class="fas fa-check"></i>
            Colocar Firma en PDF
        </button>

        <button id="btn-previsualizar" class="btn-firma btn-warning" style="display: none;">
            <i class="fas fa-sync-alt"></i>
            Actualizar Vista
        </button>

        <button id="btn-firmar" class="btn-firma btn-success" disabled>
            <i class="fas fa-save"></i>
            Guardar y Confirmar
        </button>

        <a href="{{ route('licencias.show', $licencia) }}" class="btn-firma btn-secondary" style="text-decoration: none;">
            <i class="fas fa-times"></i>
            Cancelar
        </a>
    </div>
</div>

<!-- Cargar PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
    // Configurar PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    let pdfDoc = null;
    let canvas = document.getElementById('pdf-canvas');
    let ctx = canvas.getContext('2d');
    let pdfUrl = null;
    let firmaUrl = null;
    let draggingFirma = false;
    let offsetX = 0;
    let offsetY = 0;
    let firmaColocada = false;

    // Obtener datos del PDF y firma
    async function cargarDatos() {
        try {
            mostrarMensaje('⏳ Cargando certificado...', 'info');
            const response = await fetch('{{ route("licencias.preview-firma", $licencia) }}');
            const data = await response.json();
            
            if (!data.success) {
                mostrarMensaje('✗ ' + (data.error || 'Error al cargar datos'), 'error');
                return;
            }

            pdfUrl = data.pdfUrl;
            firmaUrl = data.firmaUrl;

            document.getElementById('firma-img').src = firmaUrl;

            await cargarPDF(pdfUrl);
            mostrarMensaje('✓ Certificado cargado. Haz clic en "Colocar Firma en PDF" para comenzar', 'success');
        } catch (error) {
            mostrarMensaje('✗ Error al cargar datos: ' + error.message, 'error');
        }
    }

    async function cargarPDF(url) {
        try {
            pdfDoc = await pdfjsLib.getDocument(url).promise;
            renderPage(1);
        } catch (error) {
            mostrarMensaje('Error al cargar PDF: ' + error.message, 'error');
        }
    }

    async function renderPage(pageNum) {
        const page = await pdfDoc.getPage(pageNum);
        const scale = 1.5;
        const viewport = page.getViewport({ scale: scale });

        canvas.width = viewport.width;
        canvas.height = viewport.height;

        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };

        await page.render(renderContext).promise;
    }

    // Eventos del canvas para arrastrar firma
    canvas.addEventListener('mousedown', (e) => {
        if (!firmaColocada) return; // No permitir arrastrar si no está colocada
        
        const rect = canvas.getBoundingClientRect();
        const x = (e.clientX - rect.left) / (rect.width / canvas.width);
        const y = (e.clientY - rect.top) / (rect.height / canvas.height);

        const posX = parseInt(document.getElementById('posX').value);
        const posY = parseInt(document.getElementById('posY').value);
        const ancho = parseInt(document.getElementById('ancho').value);
        const alto = parseInt(document.getElementById('alto').value);

        if (x > posX && x < (posX + ancho) && y > posY && y < (posY + alto)) {
            draggingFirma = true;
            offsetX = x - posX;
            offsetY = y - posY;
            canvas.style.cursor = 'grabbing';
        }
    });

    canvas.addEventListener('mousemove', (e) => {
        if (draggingFirma) {
            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX - rect.left) / (rect.width / canvas.width) - offsetX;
            const y = (e.clientY - rect.top) / (rect.height / canvas.height) - offsetY;

            document.getElementById('posX').value = Math.max(0, Math.round(x));
            document.getElementById('posY').value = Math.max(0, Math.round(y));

            // Llamar previsualizar sin esperar para fluidez
            previsualizar();
        } else if (firmaColocada) {
            canvas.style.cursor = 'grab';
        }
    });

    canvas.addEventListener('mouseup', () => {
        draggingFirma = false;
        if (firmaColocada) canvas.style.cursor = 'grab';
    });

    // Previsualizar firma en tiempo real
    async function previsualizar() {
        try {
            // Primero renderizar el PDF
            await renderPage(1);

            // Luego dibujar la firma
            const posX = parseInt(document.getElementById('posX').value);
            const posY = parseInt(document.getElementById('posY').value);
            const ancho = parseInt(document.getElementById('ancho').value);
            const alto = parseInt(document.getElementById('alto').value);

            // Crear una promesa para asegurar que la imagen se cargue
            return new Promise((resolve) => {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                img.onload = () => {
                    ctx.drawImage(img, posX, posY, ancho, alto);
                    // Dibujar borde azul alrededor de la firma
                    ctx.strokeStyle = '#3b82f6';
                    ctx.lineWidth = 2;
                    ctx.strokeRect(posX, posY, ancho, alto);
                    resolve(true);
                };
                img.onerror = () => {
                    console.error('Error al cargar imagen de firma:', firmaUrl);
                    mostrarMensaje('Error al cargar la firma', 'error');
                    resolve(false);
                };
                img.src = firmaUrl;
            });
        } catch (error) {
            console.error('Error en previsualizar:', error);
            mostrarMensaje('Error al actualizar vista: ' + error.message, 'error');
        }
    }

    // Botón: Colocar Firma (nuevo flujo)
    document.getElementById('btn-colocar-firma').addEventListener('click', async () => {
        const btn = document.getElementById('btn-colocar-firma');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando firma...';
        
        try {
            // Mostrar la firma en el PDF
            await previsualizar();
            firmaColocada = true;
            
            // Mostrar controles de ajuste info
            document.getElementById('controles-ajuste').style.display = 'block';
            
            // Mostrar botón de actualizar vista
            document.getElementById('btn-previsualizar').style.display = 'flex';
            
            // Habilitar botón de confirmar
            document.getElementById('btn-firmar').disabled = false;
            
            // Cambiar apariencia del botón
            btn.innerHTML = '<i class="fas fa-check"></i> Firma Colocada - Puedes Ajustar';
            btn.disabled = true;
            btn.style.opacity = '0.65';
            
            mostrarMensaje('✓ Firma colocada correctamente. Arrastra para mover o cambia los valores para ajustar. Cuando estés listo, haz clic en "Guardar y Confirmar"', 'success');
        } catch (error) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Colocar Firma en PDF';
            btn.style.opacity = '1';
            mostrarMensaje('Error al colocar firma: ' + error.message, 'error');
        }
    });

    // Eventos de botones
    document.getElementById('btn-previsualizar').addEventListener('click', async () => {
        await previsualizar();
    });

    // Agregar listeners a los inputs para actualización en tiempo real
    ['posX', 'posY', 'ancho', 'alto'].forEach(inputId => {
        document.getElementById(inputId).addEventListener('change', async () => {
            if (firmaColocada) {
                await previsualizar();
            }
        });
    });

    document.getElementById('btn-firmar').addEventListener('click', async () => {
        await firmarDocumento();
    });

    async function firmarDocumento() {
        if (!firmaColocada) {
            mostrarMensaje('Primero debes colocar la firma en el PDF', 'error');
            return;
        }

        const posX = parseInt(document.getElementById('posX').value);
        const posY = parseInt(document.getElementById('posY').value);
        const ancho = parseInt(document.getElementById('ancho').value);
        const alto = parseInt(document.getElementById('alto').value);

        const btn = document.getElementById('btn-firmar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando firma...';

        try {
            // Enviar las dimensiones del canvas para mejor conversión de coordenadas
            const response = await fetch('{{ route("licencias.firmar.procesar", $licencia) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    posX: posX,
                    posY: posY,
                    ancho: ancho,
                    alto: alto,
                    canvasWidth: canvas.width,   // Dimensión real del canvas
                    canvasHeight: canvas.height  // Dimensión real del canvas
                })
            });

            const data = await response.json();

            if (data.success) {
                @if($licencia->signature_status === 'firmado')
                    mostrarMensaje('✓ ¡Firma actualizada exitosamente! Redirigiendo...', 'success');
                @else
                    mostrarMensaje('✓ ¡Certificado firmado exitosamente! Redirigiendo...', 'success');
                @endif
                setTimeout(() => {
                    window.location.href = '{{ route("licencias.show", $licencia) }}';
                }, 2000);
            } else {
                mostrarMensaje('✗ Error: ' + (data.error || 'Error desconocido'), 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Guardar y Confirmar';
            }
        } catch (error) {
            mostrarMensaje('✗ Error: ' + error.message, 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar y Confirmar';
        }
    }

    function mostrarMensaje(mensaje, tipo) {
        const msgElement = document.getElementById('mensaje');
        const container = document.getElementById('estado-mensaje');
        
        msgElement.textContent = mensaje;
        msgElement.className = 'mensaje show ' + tipo;
        
        // Auto cerrar mensaje después de 5 segundos si es success
        if (tipo === 'success') {
            setTimeout(() => {
                msgElement.classList.remove('show');
            }, 3000);
        }
    }

    // Cargar datos al iniciar
    cargarDatos();
</script>

<style>
    #pdf-canvas {
        cursor: grab;
        display: block;
        margin: 0 auto;
    }
</style>
@endsection
