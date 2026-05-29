<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #12961d; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .code-box { background: #fff; border: 2px solid #12961d; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; }
        .code-box h2 { font-size: 32px; font-weight: bold; color: #12961d; margin: 0; letter-spacing: 2px; }
        .footer { background: #f0f0f0; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 8px 8px; }
        .steps { padding: 15px 0; }
        .steps ol { padding-left: 20px; }
        .steps li { margin: 10px 0; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recuperación de Contraseña</h1>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $userName }}</strong>,</p>

            <p>Hemos recibido una solicitud para recuperar tu contraseña. Usa el siguiente código para restablecerla:</p>

            <div class="code-box">
                <h2>{{ $code }}</h2>
                <p style="color: #666; margin: 10px 0 0 0;">Código de 6 dígitos</p>
            </div>

            <div style="background: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px; margin: 15px 0;">
                <strong>⏱️ Validez del Código:</strong>
                <p style="margin: 5px 0;">Este código es válido por <strong>15 minutos</strong> desde el momento en que recibiste este correo.</p>
            </div>

            <div class="steps">
                <strong>📋 Pasos para recuperar tu contraseña:</strong>
                <ol>
                    <li>Dirígete a la página de recuperación de contraseña</li>
                    <li>Ingresa este código de 6 dígitos: <strong>{{ $code }}</strong></li>
                    <li>Establece tu nueva contraseña</li>
                    <li>Confirma tu nueva contraseña</li>
                </ol>
            </div>

            <div class="warning">
                <strong>🔒 Medidas de Seguridad:</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <li>Nunca compartas este código con terceros</li>
                    <li>Si no solicitaste recuperar tu contraseña, ignora este correo</li>
                    <li>Este código solo funciona desde tu correo registrado</li>
                </ul>
            </div>

            <p style="color: #666; font-size: 12px; margin-top: 20px;">Si tienes problemas o deseas otro código, contacta a nuestras oficinas.</p>
        </div>

        <div class="footer">
            <p style="margin: 0;">Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</p>
            <p style="margin: 5px 0 0 0;">Ayacucho, Perú</p>
        </div>
    </div>
</body>
</html>
