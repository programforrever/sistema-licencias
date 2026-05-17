<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #495057;
        }
        .details-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
        }
        .details-table td:first-child {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
            width: 40%;
        }
        .button {
            display: inline-block;
            background-color: #10b981;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .button:hover {
            background-color: #059669;
        }
        .next-steps {
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 3px;
        }
        .next-steps h3 {
            margin-top: 0;
            color: #059669;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
        .separator {
            border-top: 2px solid #dee2e6;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✅ ¡Certificado ITSE Aprobado!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <p>Estimado(a) <strong>{{ $titular }}</strong>,</p>
                <p>Le comunicamos con entusiasmo que su solicitud ha sido validada y aprobada exitosamente.</p>
            </div>

            <!-- Success Message -->
            <div class="success-message">
                🎉 Su Certificado ITSE ha sido aprobado
            </div>

            <!-- Certificate Details -->
            <h2 style="color: #333; border-bottom: 2px solid #10b981; padding-bottom: 10px;">Detalles del Certificado</h2>
            
            <table class="details-table">
                <tr>
                    <td>Número de Certificado</td>
                    <td><strong>{{ $numero }}</strong></td>
                </tr>
                <tr>
                    <td>Tipo</td>
                    <td>{{ $tipo }}</td>
                </tr>
                <tr>
                    <td>Estado</td>
                    <td><span style="color: #10b981; font-weight: bold;">✅ Aprobado</span></td>
                </tr>
                <tr>
                    <td>Fecha de Emisión</td>
                    <td>{{ $licencia->fecha_emision ? \Carbon\Carbon::parse($licencia->fecha_emision)->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <td>{{ $es_evento ? 'Fecha del Evento' : 'Vigencia' }}</td>
                    <td>{{ $es_evento ? $fecha_evento : ($licencia->vigencia ?? '2 años') }}</td>
                </tr>
            </table>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>📋 Próximos Pasos</h3>
                <ul>
                    <li>Su <strong>Certificado ITSE</strong> ha sido generado y se adjunta a este correo en formato PDF</li>
                    <li>Conserve este documento para sus registros</li>
                    @if($es_evento)
                        <li>Certificado válido para el evento de fecha <strong>{{ $fecha_evento }}</strong></li>
                    @else
                        <li>El certificado tiene una vigencia de <strong>{{ $licencia->vigencia ?? '2 años' }}</strong></li>
                    @endif
                    <li>En caso de consultas, contáctenos a través de nuestros canales de atención</li>
                </ul>
            </div>

            <!-- Main CTA Button -->
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}" class="button">Ir al Sistema</a>
            </div>

            <!-- Additional Info -->
            <div class="separator"></div>
            
            @if($licencia->observaciones)
                <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 3px;">
                    <p><strong>📝 Observaciones:</strong></p>
                    <p>{{ $licencia->observaciones }}</p>
                </div>
            @endif

            <p style="color: #6c757d; font-size: 14px;">
                Su trámite en línea ha sido completado exitosamente. El certificado adjunto es válido y oficial.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                <strong>Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</strong>
            </p>
            <p style="margin: 0;">
                Oficina de ITSE - Sistema de Licencias en Línea<br>
                © {{ date('Y') }} - Todos los derechos reservados
            </p>
        </div>
    </div>
</body>
</html>
