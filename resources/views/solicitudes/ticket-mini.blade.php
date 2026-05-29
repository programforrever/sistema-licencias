<!-- Ticket Mini para Previsualización en Confirmación -->
<div class="ticket-mini-wrapper" style="width: 100%; max-width: 320px; margin: 0 auto;">
    <style>
        .ticket-mini {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            font-family: 'Arial', sans-serif;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .ticket-mini-header {
            background: linear-gradient(135deg, #12961d 0%, #0f2812 100%);
            color: white;
            padding: 16px 12px;
            text-align: center;
            border-bottom: 4px solid #f1c40f;
        }

        .ticket-mini-header h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .ticket-mini-header p {
            margin: 0;
            font-size: 11px;
            opacity: 0.95;
        }

        .ticket-mini-divider {
            border: none;
            border-top: 1px dashed #ddd;
            margin: 0;
        }

        .ticket-mini-content {
            padding: 16px 12px;
        }

        .ticket-mini-code {
            text-align: center;
            margin-bottom: 16px;
        }

        .ticket-mini-code-label {
            font-size: 10px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
            display: block;
        }

        .ticket-mini-code-value {
            font-size: 18px;
            font-weight: bold;
            color: #12961d;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        .ticket-mini-qr {
            text-align: center;
            margin: 12px 0;
        }

        .ticket-mini-qr img {
            width: 100px;
            height: 100px;
            border: 2px solid #12961d;
            padding: 6px;
            background: white;
            border-radius: 4px;
        }

        .ticket-mini-amount {
            background: linear-gradient(135deg, #fffacd 0%, #fffbf0 100%);
            border: 2px solid #f1c40f;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            margin: 12px 0;
        }

        .ticket-mini-amount-label {
            font-size: 9px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .ticket-mini-amount-value {
            font-size: 22px;
            font-weight: bold;
            color: #f1c40f;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .ticket-mini-info {
            font-size: 9px;
            line-height: 1.6;
        }

        .ticket-mini-info-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .ticket-mini-info-row:last-child {
            border-bottom: none;
        }

        .ticket-mini-info-label {
            color: #999;
            font-weight: bold;
        }

        .ticket-mini-info-value {
            color: #333;
            text-align: right;
        }

        .ticket-mini-footer {
            background: #f5f5f5;
            padding: 10px 12px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px dashed #ddd;
        }

        .ticket-mini-footer p {
            margin: 0;
            line-height: 1.4;
        }
    </style>

    <div class="ticket-mini">
        <!-- Header -->
        <div class="ticket-mini-header">
            <h4><i class="fas fa-building"></i> MUNICIPALIDAD</h4>
            <p>Sistema de Licencias Integrado</p>
        </div>

        <hr class="ticket-mini-divider">

        <!-- Contenido -->
        <div class="ticket-mini-content">
            <!-- Código de Seguimiento -->
            <div class="ticket-mini-code">
                <span class="ticket-mini-code-label">Código de Seguimiento</span>
                <div class="ticket-mini-code-value">{{ $solicitud->codigo_seguimiento }}</div>
            </div>

            <!-- QR -->
            <div class="ticket-mini-qr">
                {!! QrCode::size(100)
                        ->margin(0)
                        ->generate(route('solicitudes.seguimiento', [], false) . '?codigo=' . $solicitud->codigo_seguimiento) !!}
            </div>

            <!-- Monto Pagado -->
            <div class="ticket-mini-amount">
                <div class="ticket-mini-amount-label">💳 Monto Pagado</div>
                <div class="ticket-mini-amount-value">S/. {{ number_format($solicitud->getMontoPagoCalculado(), 2, '.', '') }}</div>
            </div>

            <!-- Información -->
            <div class="ticket-mini-info">
                <div class="ticket-mini-info-row">
                    <span class="ticket-mini-info-label">Tipo:</span>
                    <span class="ticket-mini-info-value">
                        @if($solicitud->tipo_certificado == 'evento_publico')
                            Evento Público
                        @elseif($solicitud->tipo_certificado == 'anexo_13')
                            Anexo 13
                        @else
                            Anexo 14
                        @endif
                    </span>
                </div>
                <div class="ticket-mini-info-row">
                    <span class="ticket-mini-info-label">Estado:</span>
                    <span class="ticket-mini-info-value">
                        <span style="background: #dbeafe; color: #0c4a6e; padding: 2px 6px; border-radius: 3px; font-weight: bold;">
                            Enviada
                        </span>
                    </span>
                </div>
                <div class="ticket-mini-info-row">
                    <span class="ticket-mini-info-label">Fecha:</span>
                    <span class="ticket-mini-info-value">{{ $solicitud->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <hr class="ticket-mini-divider">

        <!-- Link Subir Comprobante - Mostrar URL -->
        <div style="padding: 12px; text-align: center; background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);">
            <div style="font-size: 10px; color: #666; margin-bottom: 6px; font-weight: bold;">SUBIR COMPROBANTE</div>
            <div style="word-break: break-all; font-size: 9px; background: white; padding: 6px; border-radius: 4px; border: 1px solid #d1fae5;">
                <a href="{{ route('solicitudes.formulario.comprobante', $solicitud->codigo_seguimiento) }}" target="_blank" 
                   style="color: #12961d; text-decoration: none; font-weight: bold;">
                    {{ route('solicitudes.formulario.comprobante', $solicitud->codigo_seguimiento, [], false) }}
                </a>
            </div>
        </div>

        <hr class="ticket-mini-divider">

        <!-- Footer -->
        <div class="ticket-mini-footer">
            <p>
                <i class="fas fa-check-circle" style="color: #12961d;"></i><br>
                Comprobante de Pago<br>
                <strong>Guarda este ticket como referencia</strong>
            </p>
        </div>
    </div>
</div>
