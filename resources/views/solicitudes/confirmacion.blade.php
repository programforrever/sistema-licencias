<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Enviada - Municipalidad</title>
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

        .confirm-card {
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            background: var(--surface);
        }

        .codigo-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
            border: 2px dashed var(--green-main);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin: 1.5rem 0;
        }

        .codigo-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .codigo-text {
            font-size: 32px;
            font-weight: 700;
            color: var(--green-main);
            letter-spacing: 0.05em;
            font-family: 'SF Mono', 'Fira Code', monospace;
        }

        .codigo-nota {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        .resumen-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.25rem;
            margin: 1.25rem 0;
        }

        .resumen-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .resumen-row {
            display: flex;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
        }

        .resumen-row:last-child { border-bottom: none; }

        .resumen-label {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            width: 120px;
            flex-shrink: 0;
        }

        .resumen-value {
            color: var(--text-main);
            font-size: 0.85rem;
        }

        .badge-tipo {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.28rem 0.65rem;
            border-radius: 999px;
        }

        .badge-evt  { background: #eff6ff; color: #1d4ed8; }
        .badge-a13  { background: #fffbeb; color: #b45309; }
        .badge-a14  { background: #fff1f2; color: #be123c; }

        .alert-custom {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 2px solid #fca5a5;
            border-left: 6px solid #dc2626;
            color: #7f1d1d;
            border-radius: 10px;
            padding: 1.5rem;
            font-size: 0.9rem;
            margin: 1.5rem 0;
            line-height: 1.6;
        }

        .alert-custom strong {
            color: #991b1b;
            font-weight: 700;
        }

        .alert-custom ul {
            margin: 0.75rem 0 0 1.5rem;
            padding: 0;
        }

        .alert-custom li {
            margin-bottom: 0.5rem;
        }

        .alert-icon {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-primary-custom {
            background: var(--brand);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.7rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-primary-custom:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary-custom {
            background: var(--bg);
            color: var(--text-muted);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 0.7rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-secondary-custom:hover {
            border-color: var(--text-main);
            color: var(--text-main);
            background: var(--surface);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--success-light) 0%, #d1fae5 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
        }

        .success-icon i {
            color: var(--success-main);
            font-size: 2.2rem;
        }

        .confirm-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--success-main);
            margin-bottom: 0.5rem;
        }

        .confirm-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .ticket-section {
            background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 2rem;
            margin: 1.5rem 0;
        }

        .ticket-header-text {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ticket-header-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .ticket-preview-box {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;
            margin-bottom: 1rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .ticket-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .btn-ticket {
            background: var(--success-main);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-ticket:hover {
            background: var(--green-main);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(18, 150, 29, 0.3);
            color: white;
        }

        .btn-ticket-secondary {
            background: var(--bg);
            color: var(--text-main);
            border: 1.5px solid var(--green-main);
        }

        .btn-ticket-secondary:hover {
            background: var(--success-light);
            border-color: var(--green-main);
        }

        .ticket-info-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: var(--success-light);
            color: var(--green-main);
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .action-buttons {
                grid-template-columns: 1fr;
            }
            .ticket-actions {
                grid-template-columns: 1fr;
            }
            .codigo-text {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="confirm-card p-4">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>

                <h3 class="confirm-title text-center">
                    <i class="fas fa-envelope-circle-check me-2"></i>¡Solicitud Enviada!
                </h3>
                <p class="confirm-subtitle text-center">
                    Tu solicitud ha sido recibida correctamente. Guarda tu código de seguimiento:
                </p>

                <div class="codigo-box">
                    <div class="codigo-label">Código de Seguimiento</div>
                    <div class="codigo-text">{{ $solicitud->codigo_seguimiento }}</div>
                    <div class="codigo-nota">
                        <i class="fas fa-lightbulb me-1"></i>
                        Úsalo para consultar el estado de tu trámite
                    </div>
                </div>

                <div class="resumen-card">
                    <div class="resumen-title">
                        <i class="fas fa-file-alt"></i> Resumen de tu Solicitud
                    </div>

                    <div class="resumen-row">
                        <div class="resumen-label">Tipo</div>
                        <div class="resumen-value">
                            <span class="badge-tipo
                                @if($solicitud->tipo_certificado == 'evento_publico') badge-evt
                                @elseif($solicitud->tipo_certificado == 'anexo_13') badge-a13
                                @else badge-a14 @endif">
                                @if($solicitud->tipo_certificado == 'anexo_14')
                                    Anexo 14 — Alto Riesgo
                                @elseif($solicitud->tipo_certificado == 'anexo_13')
                                    Anexo 13 — Bajo/Medio Riesgo
                                @else
                                    Evento Público
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="resumen-row">
                        <div class="resumen-label">Solicitante</div>
                        <div class="resumen-value">{{ $solicitud->nombres_solicitante }}</div>
                    </div>

                    <div class="resumen-row">
                        <div class="resumen-label">DNI/RUC</div>
                        <div class="resumen-value">{{ $solicitud->dni_ruc }}</div>
                    </div>

                    <div class="resumen-row">
                        <div class="resumen-label">WhatsApp</div>
                        <div class="resumen-value">
                            <i class="fab fa-whatsapp" style="color: #25d366;"></i>
                            {{ $solicitud->telefono_whatsapp }}
                        </div>
                    </div>

                    <div class="resumen-row">
                        <div class="resumen-label">Estado</div>
                        <div class="resumen-value">
                            <span style="display: inline-block; background: #dbeafe; color: #0c4a6e; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700;">
                                ENVIADA
                            </span>
                        </div>
                    </div>
                </div>

                <div class="alert-custom">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div><strong>⚠️ AVISO IMPORTANTE - PAGO PRESENCIAL</strong></div>
                    <p style="margin: 0.75rem 0 0.5rem 0;">
                        El pago del trámite se realiza <strong>presencialmente en la Municipalidad</strong>.
                    </p>
                    <ul style="margin: 0.5rem 0;">
                        <li>✓ Completa tu solicitud online (ya está hecha)</li>
                        <li>✓ Guarda este ticket con el QR de pago</li>
                        <li>✓ Acude a la Municipalidad presencialmente con este ticket</li>
                        <li>✓ Realiza el pago del monto indicado en este ticket</li>
                        <li>✓ La Municipalidad confirmará tu pago</li>
                    </ul>
                </div>

                <!-- SECCIÓN DE TICKET DE PAGO -->
                <div class="ticket-section">
                    <div class="ticket-header-text">
                        <i class="fas fa-receipt"></i> Tu Ticket de Pago
                    </div>
                    <div class="ticket-header-subtitle">
                        Presenta este ticket en la Municipalidad para realizar el pago. El QR contiene tu código de seguimiento.
                    </div>

                    <div class="ticket-info-badge">
                        <i class="fas fa-info-circle"></i>
                        <strong>El monto se paga en caja.</strong> Guarda el recibo como comprobante.
                    </div>

                    <!-- Preview del Ticket -->
                    <div class="ticket-preview-box" id="ticket-preview">
                        @include('solicitudes.ticket-mini', ['solicitud' => $solicitud])
                    </div>

                    <!-- Botones de Acción -->
                    <div class="ticket-actions">
                        <button onclick="imprimirTicket()" class="btn-ticket">
                            <i class="fas fa-print"></i> Imprimir Ticket
                        </button>
                        <button onclick="copiarCodigo()" class="btn-ticket btn-ticket-secondary">
                            <i class="fas fa-copy"></i> Copiar Código
                        </button>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('solicitudes.seguimiento') }}?codigo={{ $solicitud->codigo_seguimiento }}" class="btn-primary-custom" style="justify-self: start;">
                        <i class="fas fa-search"></i> Ver Estado
                    </a>
                    <a href="{{ route('solicitudes.formulario') }}" class="btn-secondary-custom" style="justify-self: end;">
                        <i class="fas fa-plus"></i> Nueva Solicitud
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Función para imprimir el ticket
    function imprimirTicket() {
        const contenido = document.getElementById('ticket-preview').innerHTML;
        const ventana = window.open('', '', 'height=800,width=900');
        
        const html = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Ticket de Pago - {{ $solicitud->codigo_seguimiento }}</title>
                <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
                <style>
                    body {
                        margin: 0;
                        padding: 20px;
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        background: #f5f5f5;
                    }
                    @media print {
                        body {
                            background: white;
                            padding: 0;
                        }
                    }
                </style>
            </head>
            <body>
                ${contenido}
            </body>
            </html>
        `;
        
        ventana.document.write(html);
        ventana.document.close();
        setTimeout(() => ventana.print(), 500);
    }

    // Función para copiar el código de seguimiento
    function copiarCodigo() {
        const codigo = '{{ $solicitud->codigo_seguimiento }}';
        const mensajeExito = String.fromCharCode(10003) + ' Código copiado al portapapeles';
        const mensajeError = String.fromCharCode(10005) + ' No se pudo copiar el código';
        
        // Usar la API de Clipboard si está disponible
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(codigo).then(() => {
                mostrarToast(mensajeExito, 'success');
            }).catch(err => {
                fallbackCopy(codigo, mensajeExito, mensajeError);
            });
        } else {
            // Fallback para navegadores antiguos
            fallbackCopy(codigo, mensajeExito, mensajeError);
        }
    }

    // Fallback para copiar texto
    function fallbackCopy(texto, mensajeExito, mensajeError) {
        const textarea = document.createElement('textarea');
        textarea.value = texto;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            document.execCommand('copy');
            mostrarToast(mensajeExito, 'success');
        } catch (err) {
            mostrarToast(mensajeError, 'error');
        }
        
        document.body.removeChild(textarea);
    }

    // Función para mostrar notificaciones toast
    function mostrarToast(mensaje, tipo = 'info') {
        const bgColor = tipo === 'success' ? '#10b981' : tipo === 'error' ? '#ef4444' : '#3b82f6';
        const toast = document.createElement('div');
        toast.style.cssText = 'position: fixed; top: 20px; right: 20px; background: ' + bgColor + '; color: white; padding: 12px 20px; border-radius: 8px; font-weight: 600; z-index: 9999; animation: slideInRight 0.3s ease-out; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        
        toast.textContent = mensaje;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    // Agregar animaciones
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>

</body>
</html>