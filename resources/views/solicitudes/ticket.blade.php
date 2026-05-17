<!-- Ticket de 80mm (303px a 96 DPI) -->
<div id="ticket-completo" class="ticket-container">
    <style>
        .ticket-wrapper {
            width: 80mm;
            background: white;
            color: #000;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .ticket-header {
            background: linear-gradient(135deg, #12961d 0%, #0f2812 100%);
            color: white;
            padding: 12px;
            text-align: center;
            border-bottom: 3px solid #f1c40f;
        }

        .ticket-header-title {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .ticket-divider {
            border: none;
            border-top: 1px dashed #ddd;
            margin: 8px 0;
        }

        .ticket-section {
            padding: 10px 12px;
        }

        .ticket-label {
            font-size: 8px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
        }

        .ticket-value {
            font-size: 13px;
            font-weight: bold;
            color: #12961d;
            word-break: break-all;
            margin-bottom: 8px;
            font-family: 'Courier New', monospace;
        }

        .ticket-qr {
            text-align: center;
            padding: 10px 0;
        }

        .ticket-qr img {
            width: 80px;
            height: 80px;
            border: 2px solid #12961d;
            padding: 4px;
            background: white;
        }

        .ticket-info-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin-bottom: 4px;
            padding: 0 2px;
        }

        .ticket-info-label {
            color: #666;
            font-weight: bold;
        }

        .ticket-info-value {
            color: #333;
            text-align: right;
        }

        .ticket-amount {
            background: #fffacd;
            border: 2px solid #f1c40f;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
            margin: 8px 0;
        }

        .ticket-amount-label {
            font-size: 8px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
        }

        .ticket-amount-value {
            font-size: 16px;
            font-weight: bold;
            color: #f1c40f;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .ticket-footer {
            background: #f5f5f5;
            padding: 8px 12px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px dashed #ddd;
        }

        .ticket-footer-text {
            line-height: 1.3;
            margin: 0;
        }

        /* Estilos para impresión */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .ticket-container {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .no-print {
                display: none !important;
            }
        }

        /* Estilos para previsualización */
        .ticket-preview-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #eee;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .ticket-wrapper {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            overflow: hidden;
        }
    </style>

    <div class="ticket-preview-wrapper">
        <div class="ticket-wrapper">
            <!-- Encabezado -->
            <div class="ticket-header">
                <h3 class="ticket-header-title">
                    <i class="fas fa-building"></i> MUNICIPALIDAD
                </h3>
                <p style="font-size: 9px; margin: 2px 0; opacity: 0.9;">Sistema de Licencias</p>
            </div>

            <hr class="ticket-divider">

            <!-- Código de Seguimiento -->
            <div class="ticket-section">
                <div class="ticket-label">Código de Seguimiento</div>
                <div class="ticket-value">{{ $solicitud->codigo_seguimiento }}</div>
            </div>

            <hr class="ticket-divider">

            <!-- QR -->
            <div class="ticket-section ticket-qr">
                {!! QrCode::size(80)
                        ->margin(0)
                        ->generate(route('solicitudes.seguimiento', [], false) . '?codigo=' . $solicitud->codigo_seguimiento) !!}
            </div>

            <hr class="ticket-divider">

            <!-- Monto Pagado -->
            <div class="ticket-section">
                <div class="ticket-amount">
                    <div class="ticket-amount-label">Monto Pagado</div>
                    <div class="ticket-amount-value">
                        S/. {{ number_format($solicitud->getMontoPagoCalculado(), 2, '.', '') }}
                    </div>
                </div>
            </div>

            <!-- Información de Solicitud -->
            <div class="ticket-section">
                <div class="ticket-info-row">
                    <span class="ticket-info-label">Tipo:</span>
                    <span class="ticket-info-value">
                        @if($solicitud->tipo_certificado == 'evento_publico')
                            Evento Público
                        @elseif($solicitud->tipo_certificado == 'anexo_13')
                            Anexo 13
                        @else
                            Anexo 14
                        @endif
                    </span>
                </div>

                <div class="ticket-info-row">
                    <span class="ticket-info-label">Estado:</span>
                    <span class="ticket-info-value">{{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</span>
                </div>

                <div class="ticket-info-row">
                    <span class="ticket-info-label">Fecha:</span>
                    <span class="ticket-info-value">{{ $solicitud->created_at->format('d/m/Y') }}</span>
                </div>

                <div class="ticket-info-row">
                    <span class="ticket-info-label">Hora:</span>
                    <span class="ticket-info-value">{{ $solicitud->created_at->format('H:i') }}</span>
                </div>
            </div>

            <hr class="ticket-divider">

            <!-- Footer -->
            <div class="ticket-footer">
                <p class="ticket-footer-text">
                    <i class="fas fa-check-circle"></i><br>
                    Comprobante de Pago<br>
                    Guarda este ticket como referencia
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para funcionalidades -->
<script>
    function imprimirTicket() {
        const contenido = document.getElementById('ticket-completo').innerHTML;
        const ventana = window.open('', '', 'height=600,width=800');
        ventana.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Ticket de Pago</title>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
            </head>
            <body style="margin: 0; padding: 10px; text-align: center;">
                ${contenido}
            </body>
            </html>
        `);
        ventana.document.close();
        ventana.print();
    }

    function descargarTicket() {
        // Requiere html2canvas y jsPDF (opcionales)
        alert('Funcionalidad de descarga disponible con configuración adicional');
    }
</script>
