@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6" id="firma-container">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Firmar Documento - Solicitud #{{ $solicitud->codigo_seguimiento }}</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel Izquierdo: Preview del PDF -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Vista Previa del Documento</h2>
                <div id="pdf-container" class="bg-gray-100 rounded p-4 overflow-auto" style="height: 600px; position: relative;">
                    <canvas id="pdf-canvas" class="w-full"></canvas>
                </div>
                <p class="text-gray-600 text-sm mt-4">
                    Arrastra la firma sobre el documento para posicionarla. Usa los controles de la derecha para ajustar.
                </p>
            </div>
        </div>

        <!-- Panel Derecho: Controles de Firma -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Controles de Firma</h2>

                <div id="firma-preview" class="bg-gray-50 rounded p-4 mb-4 border-2 border-dashed border-gray-300">
                    <p class="text-gray-600 text-sm mb-2">Tu Firma:</p>
                    <img id="firma-img" src="" alt="Tu firma" class="h-24 object-contain w-full">
                </div>

                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Posición X (px)</label>
                        <input type="number" id="posX" value="150" min="0" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Posición Y (px)</label>
                        <input type="number" id="posY" value="400" min="0" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Ancho (px)</label>
                        <input type="number" id="ancho" value="80" min="1" max="200" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Alto (px)</label>
                        <input type="number" id="alto" value="40" min="1" max="200" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>

                <div class="space-y-3">
                    <button id="btn-previsualizar" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Previsualizar
                    </button>
                    <button id="btn-firmar" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" disabled>
                        Confirmar y Firmar
                    </button>
                    <a href="{{ route('solicitudes.show', $solicitud) }}" class="block text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                </div>

                <div id="estado-mensaje" class="mt-4 p-3 rounded text-sm hidden"></div>
            </div>
        </div>
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

    // Obtener datos du PDF y firma
    async function cargarDatos() {
        try {
            const response = await fetch('{{ route("solicitudes.preview-firma", $solicitud) }}');
            const data = await response.json();
            
            if (!data.success) {
                mostrarMensaje(data.error, 'error');
                return;
            }

            pdfUrl = data.pdfUrl;
            firmaUrl = data.firmaUrl;

            document.getElementById('firma-img').src = firmaUrl;

            await cargarPDF(pdfUrl);
        } catch (error) {
            mostrarMensaje('Error al cargar datos: ' + error.message, 'error');
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

    // Eventos del canvas para arrastrar forma
    canvas.addEventListener('mousedown', (e) => {
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

            previsualizar();
        } else {
            canvas.style.cursor = 'grab';
        }
    });

    canvas.addEventListener('mouseup', () => {
        draggingFirma = false;
        canvas.style.cursor = 'grab';
    });

    // Previsualizar firma en tiempo real
    function previsualizar() {
        renderPage(1);

        const posX = parseInt(document.getElementById('posX').value);
        const posY = parseInt(document.getElementById('posY').value);
        const ancho = parseInt(document.getElementById('ancho').value);
        const alto = parseInt(document.getElementById('alto').value);

        const img = new Image();
        img.src = firmaUrl;
        img.onload = () => {
            ctx.drawImage(img, posX, posY, ancho, alto);
            ctx.strokeStyle = '#3b82f6';
            ctx.lineWidth = 2;
            ctx.strokeRect(posX, posY, ancho, alto);
        };
    }

    // Eventos de botones
    document.getElementById('btn-previsualizar').addEventListener('click', previsualizar);

    document.getElementById('btn-firmar').addEventListener('click', async () => {
        await firmarDocumento();
    });

    async function firmarDocumento() {
        const posX = parseInt(document.getElementById('posX').value);
        const posY = parseInt(document.getElementById('posY').value);
        const ancho = parseInt(document.getElementById('ancho').value);
        const alto = parseInt(document.getElementById('alto').value);

        const btn = document.getElementById('btn-firmar');
        btn.disabled = true;
        btn.textContent = 'Firmando...';

        try {
            const response = await fetch('{{ route("solicitudes.firmar.procesar", $solicitud) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    posX: posX,
                    posY: posY,
                    ancho: ancho,
                    alto: alto
                })
            });

            const data = await response.json();

            if (data.success) {
                mostrarMensaje('¡Documento firmado exitosamente!', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("solicitudes.show", $solicitud) }}';
                }, 2000);
            } else {
                mostrarMensaje(data.error, 'error');
                btn.disabled = false;
                btn.textContent = 'Confirmar y Firmar';
            }
        } catch (error) {
            mostrarMensaje('Error: ' + error.message, 'error');
            btn.disabled = false;
            btn.textContent = 'Confirmar y Firmar';
        }
    }

    function mostrarMensaje(mensaje, tipo) {
        const div = document.getElementById('estado-mensaje');
        div.textContent = mensaje;
        div.classList.remove('hidden');
        div.className = 'mt-4 p-3 rounded text-sm ' + 
            (tipo === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700');
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
