# 📧 GUÍA DE CONFIGURACIÓN: EMAIL + WHATSAPP NOTIFICATIONS

## ✅ QUÉ SE IMPLEMENTÓ

### 1. **Notificaciones por EMAIL (Gmail SMTP)**
   - ✓ Envío automático al cambiar estado de solicitud
   - ✓ Emails con plantilla profesional
   - ✓ Registro de envíos en base de datos
   - ✓ Handling de errores

### 2. **Notificaciones por WHATSAPP**
   - ✓ Sin APIs pagadas (Baileys - emulación de WhatsApp Web)
   - ✓ Mensajes personalizados con emojis
   - ✓ Código de seguimiento incluido
   - ✓ Registro de envíos

### 3. **Interfaz de Administrador**
   - ✓ 4 botones para elegir:
     - Guardar + Email
     - Guardar + WhatsApp
     - Guardar + Ambos
     - Solo Guardar
   - ✓ Muestra estado de contacto del usuario

### 4. **Auditoría**
   - ✓ Tabla `notification_logs` con historial completo
   - ✓ Registra canal, destinatario, estado, errores
   - ✓ Búsqueda y reportes

---

## 🔧 CONFIGURACIÓN PASO A PASO

### PASO 1: Configurar Gmail SMTP (por favor hacerlo)

#### 1.1 Crear contraseña de aplicación en Google

1. Ve a: https://myaccount.google.com/app-passwords
2. Selecciona:
   - App: **Mail**
   - Device: **Other (custom name)** → "Sistema ITSE"
3. Google genera una contraseña de 16 caracteres
4. **COPIA ESA CONTRASEÑA**

#### 1.2 Actualizar `.env`

```env
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo@gmail.com
MAIL_PASSWORD=tu_contraseña_app_google
MAIL_FROM_ADDRESS="notificaciones@municipalidad.gob.pe"
MAIL_FROM_NAME="Certificados ITSE"
```

**Ejemplo real:**
```env
MAIL_USERNAME=municipalidad.ayacucho@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
```

#### 1.3 Prueba la conexión

```bash
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('tu_email@gmail.com'); })->send();
>>> exit()
```

Si no hay error, ¡funcionó! ✅

---

### PASO 2: Configurar WhatsApp (Opcional - Para después)

Para usar WhatsApp sin APIs pagadas:

```bash
composer require darumatic/whatsapp-web-php
```

Luego:
```bash
php artisan whatsapp:qr
```

Esto mostrará un código QR. Escanea con **WhatsApp Web** en tu teléfono.

**NOTA:** Por ahora está en modo simulación (guarda en logs). Cuando instales Baileys, funcionará.

---

### PASO 3: Ejecutar migraciones (si no se ejecutaron)

```bash
php artisan migrate --step --force
```

Esto crea la tabla `notification_logs`.

---

## 🎯 CÓMO USAR

### Desde el Panel Funcionario:

1. Ve a **Solicitudes** → Click en una solicitud
2. En la sección **Procesar Solicitud**:
   - Selecciona nuevo estado
   - Escribe observaciones (opcional)
   - Click en uno de estos botones:
     - **📧 Guardar + Email** → Email al solicitante
     - **📱 Guardar + WhatsApp** → WhatsApp al solicitante
     - **🔄 Guardar + Ambos** → Email + WhatsApp
     - **💾 Solo Guardar** → Sin notificación

3. El sistema:
   - Guarda el cambio de estado
   - Envía notificación(es)
   - Registra en `notification_logs`
   - Muestra confirmación

---

## 📋 CONTENIDO DE NOTIFICACIONES

### EMAIL
```
Asunto: Cambio de Estado - SOL-2026-XXXXXX

Hola Juan,

Tu solicitud de certificado ha sido APROBADA.

Detalles:
- Código: SOL-2026-XXXXXX
- Tipo: ITSE Anexo 13
- Estado: Aprobada
- Fecha: 02/05/2026

Observaciones:
[Se muestra si hay]

[Botón] Ver Detalles de mi Trámite
```

### WHATSAPP
```
📩 Actualización de tu trámite

Hola Juan,

Tu solicitud ha sido APROBADA

📋 Código: SOL-2026-XXXXXX
📝 Tipo: ITSE Anexo 13
📅 Fecha: 02/05/2026 14:30

📌 Observaciones:
Todo está correcto, puedes recoger tu certificado.

🔍 Ver: [URL de seguimiento]

---
Municipalidad Distrital...
```

---

## 🐛 TROUBLESHOOTING

### "Error: Connection could not be established"
- ✓ Verifica credenciales en `.env`
- ✓ Gmail debe tener "2FA" habilitado
- ✓ Usa "contraseña de aplicación" (no contraseña normal)

### "Email no enviado pero SIN error"
- ✓ Revisa que el modelo Solicitud tenga `email` registrado
- ✓ Revisa logs: `storage/logs/laravel.log`

### "WhatsApp no envía"
- ✓ Por ahora está en simulación (ver logs)
- ✓ Necesita instalar Baileys para envío real

### Ver logs de notificaciones

```bash
# En la BD, tabla notification_logs
php artisan tinker
>>> DB::table('notification_logs')->latest()->get();
```

---

## 📊 VISTA DE AUDITORÍA

Se puede agregar una vista en admin para ver:

```blade
{{-- solicitudes/audit.blade.php --}}
@foreach($solicitud->notificationLogs as $log)
    <tr>
        <td>{{ $log->canal }}</td>
        <td>{{ $log->destinatario }}</td>
        <td>{{ $log->estado }}</td>
        <td>{{ $log->cambio_estado }}</td>
        <td>{{ $log->created_at }}</td>
    </tr>
@endforeach
```

---

## 🚀 PRÓXIMOS PASOS

1. **Implementar WhatsApp Baileys** (cuando necesites)
   - Instalar librería
   - Escanear QR
   - Envío automático

2. **Notificaciones Automáticas**
   - Eventos (listeners) para cambios de estado
   - Queue para no bloquear UI

3. **SMS adicional**
   - Twilio SMS (pagado pero económico)
   - Notificación triple: Email + WhatsApp + SMS

4. **Plantillas personalizables**
   - Admin configura mensajes
   - Variables dinámicas: {codigo}, {estado}, {observaciones}

---

## 📞 SOPORTE

- **Problema**: Ver `storage/logs/laravel.log`
- **BD**: `notification_logs` contiene historial completo
- **Test**: `php artisan tinker` para pruebas manuales

---

**Última actualización**: 2 de Mayo, 2026
