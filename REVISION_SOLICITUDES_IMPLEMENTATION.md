# 📋 Implementación: Flujo de Revisión de Solicitudes Online

**Fecha:** Mayo 12, 2026  
**Estado:** ✅ Completado y Migraciones Ejecutadas

---

## 📌 Resumen de Implementación

Se implementó un flujo completo de revisión de solicitudes online con **5 estados**, **enlaces públicos para revisores**, **documentos adjuntos automáticos** y **panel de seguimiento para clientes**.

### **Nuevos Estados de Solicitud**

| Estado | Descripción |
|--------|-----------|
| `registrado` | Estado inicial cuando llega la solicitud |
| `aceptado` | Después de aceptar la solicitud (pre-revisión) |
| `enviado_a_revision` | Enviada a los 4 revisores (con documentos por email) |
| `aprobado` | Después de recibir todas las revisiones |
| `rechazado` | Si es rechazada en cualquier etapa |

---

## 🔄 Flujo Completo del Usuario

### **Paso 1: Cliente envía solicitud**
- Nueva solicitud llega con estado `registrado`
- Se genera código único: `SOL-YYYY-XXXXXX`
- Cliente recibe confirmación

### **Paso 2: Funcionario Acepta Solicitud**
1. Funcionario abre detalle de solicitud
2. En panel derecho, selecciona estado "Aceptar"
3. Solicitud cambia a estado `aceptado`

### **Paso 3: Funcionario Envía a Revisión (4 revisores)**
1. Una vez "aceptado", aparece botón: **"Enviar a 4 Revisores"**
2. Se abre modal para seleccionar:
   - Nombre de revisor 1, 2, 3, 4
   - Email de cada revisor
3. Al enviar:
   - ✅ Se crean 4 registros en `revisores_solicitud` (cada uno con token único)
   - ✅ Se envía email a cada revisor con:
     - Información completa de la solicitud
     - **Documentos adjuntos** (Solicitud, Plano, Otros)
     - Enlace único para diligenciar revisión
   - ✅ Solicitud cambia a `enviado_a_revision`

### **Paso 4: Revisor Recibe Email y Adjunta Revisión**
1. Revisor recibe email con documentos y enlace único
2. Haz clic en: **"Acceder al Formulario de Revisión"**
3. Página PÚBLICA (sin login) donde puede ver:
   - Todos los detalles de la solicitud
   - Link para ver documentos (plano, etc)
4. Completa formulario con:
   - Resultado: ✅ Aprobado / ⚠️ Requiere Cambios / ❌ Rechazado
   - Notas: sus observaciones (mín 5 caracteres)
   - Documento (opcional): puede adjuntar evidencia/documento
5. Envía revisión

### **Paso 5: Sistema Verifica si Todos Revisaron**
- Cada revisor adjunto de la revisión:
  - Se actualiza `revisores_solicitud.estado_revision = 'revisado'`
  - Se crea registro en `revisiones_solicitud` (con notas, documento)
- Si **todos los 4 revisores** han enviado:
  - Solicitud cambia automáticamente a `aceptado`

### **Paso 6: Funcionario Cambia Estado Final**
1. Abre detalle de solicitud
2. Ve en sección **"Revisiones Recibidas"** todas las revisiones con:
   - Nombre del revisor
   - Resultado (Aprobado/Requiere Cambios/Rechazado)
   - Notas de cada revisor
   - Documento adjuntado (si existe)
3. Actualiza estado a:
   - **Aprobar** (genera licencia)
   - **Rechazar** (con observaciones)
4. También puede actualizar **Estado de Pago**:
   - ✅ Pago Validado
   - ❌ Pago Rechazado
   - Pendiente

### **Paso 7: Cliente Ve Seguimiento**
- Cliente busca su solicitud en **"Seguimiento"** (sin login)
- Ve:
  - Estado actual (Registrado → Aceptado → Enviado a Revisión → Aprobado)
  - Timeline visual
  - **Estado de Pago** si está configurado

---

## 🗄️ Base de Datos - Tablas Nuevas

### **Tabla: `solicitudes` (Actualizada)**

Nuevos campos agregados:

```sql
- estado_pago ENUM('pago_pendiente', 'pago_validado', 'pago_rechazado') DEFAULT NULL
- token_revision VARCHAR(60) UNIQUE -- Para compatibilidad futura
- Enums de estado actualizados: 'registrado', 'aceptado', 'enviado_a_revision'
```

### **Tabla: `revisores_solicitud` (Nueva)**

```sql
- id
- solicitud_id (FK → solicitudes)
- email VARCHAR(255)
- nombre_revisor VARCHAR(255)
- estado_revision ENUM('pendiente', 'revisado', 'rechazado') DEFAULT 'pendiente'
- token_revisor VARCHAR(60) UNIQUE -- Enlace público sin login
- enviado_at TIMESTAMP NULL
- revisado_at TIMESTAMP NULL
- created_at, updated_at
- UNIQUE(solicitud_id, email) -- No repetir revisores por solicitud
```

### **Tabla: `revisiones_solicitud` (Nueva)**

```sql
- id
- solicitud_id (FK → solicitudes)
- revisor_solicitud_id (FK → revisores_solicitud)
- notas TEXT -- Observaciones del revisor
- documento_revision VARCHAR(255) -- Path del documento uploadado
- resultado_revision ENUM('aprobado', 'requiere_cambios', 'rechazado') NULL
- entregado_at TIMESTAMP NULL
- created_at, updated_at
```

---

## 📧 Email Enviado a Revisores

**Archivo:** `resources/views/emails/solicitud-envio-revision.blade.php`

El email contiene:

```
1. ENCABEZADO
   - Título: "Solicitud Pendiente de Revisión"
   - Mensaje: Estimado revisor

2. INFORMACIÓN DE LA SOLICITUD
   - Código de seguimiento
   - Datos del solicitante
   - Tipo de certificado
   - Actividad, dirección, etc.

3. DOCUMENTOS ADJUNTOS
   - Automáticamente se adjuntan:
     * Formulario de Solicitud (si existe)
     * Plano/Anteproyecto (si existe)
     * Documentos Adicionales (si existe)

4. BOTÓN PRINCIPAL
   - "Acceder al Formulario de Revisión"
   - Enlace único por revisor con token

5. INSTRUCCIONES
   - Paso a paso de qué hacer

6. NOTA IMPORTANTE
   - Advertencia: enlace es único y personal
```

---

## 🌐 Vistas/Formularios Públicos

### **1. Formulario de Revisión** (`revisiones/formulario.blade.php`)

**URL:** `/revision/{token}`

- ✅ Acceso público (sin login)
- Muestra:
  - Información completa de solicitud
  - 3 botones para seleccionar resultado
  - Textarea para notas
  - Upload de documento (PDF, DOC, JPG, PNG - máx 10MB)
- Si ya revisó:
  - Muestra mensaje de "Revisión Registrada"
  - Puede actualizar su revisión

### **2. Detalles de Solicitud** (`revisiones/detalles.blade.php`)

**URL:** `/revision/{token}/detalles`

- ✅ Acceso público (sin login)
- Muestra todos los datos de la solicitud
- Links para descargar documentos
- Botón para volver al formulario

---

## 🎛️ Panel de Funcionario - Nuevas Secciones

### **En Vista `solicitudes/show.blade.php`:**

#### **Sección 1: Tabla de Información Ampliada**
```
- Estado: Muestra todos los 5 estados con colores y iconos
- Estado de Pago: Muestra si está pendiente, validado o rechazado
```

#### **Sección 2: Revisiones Recibidas**
```
(Solo si hay revisiones)
- Tarjeta por cada revisión con:
  * Nombre y email del revisor
  * Resultado (Aprobado/Requiere Cambios/Rechazado)
  * Notas completas del revisor
  * Link para descargar documento (si adjuntó)
  * Color de borde según resultado
```

#### **Sección 3: Panel de Cambio de Estado** (Actualizado)
```
- Select: Nuevo estado (con opciones válidas según estado actual)
- Select: Estado de Pago (Pendiente/Validado/Rechazado)
- Textarea: Observaciones
- Botones: Email / WhatsApp / Ambos / Solo Guardar
```

#### **Sección 4: Enviar a Revisión** (Nuevo - solo si estado = aceptado)
```
- Botón: "Enviar a 4 Revisores"
- Modal con 4 campos (Nombre + Email de cada revisor)
```

#### **Sección 5: Estado de Revisores** (Nuevo - si hay revisores)
```
- Tabla con:
  * Nombre del revisor
  * Badge de estado: PENDIENTE / ✓ REVISADO / RECHAZADO
  * Email
```

---

## 🔐 Rutas Nuevas

### **Públicas (sin login):**

```php
GET  /revision/{token}                  // Formulario de revisión
GET  /revision/{token}/detalles         // Ver detalles de solicitud
POST /revision/{token}/guardar          // Guardar revisión
```

### **Privadas (con login de funcionario):**

```php
POST /solicitudes/{solicitud}/enviar-revision    // Enviar a revisores
POST /solicitudes/{solicitud}/actualizar-pago    // Cambiar estado de pago
POST /solicitudes/{solicitud}/procesar           // Cambiar estado (actualizado)
```

---

## 📦 Modelos Actualizados/Nuevos

### **Relaciones en Solicitud.php:**

```php
public function revisores()        // HasMany RevisorSolicitud
public function revisiones()       // HasMany RevisionSolicitud
public function licencia()         // BelongsTo Licencia
```

### **Nuevo: RevisorSolicitud.php**

```php
public function solicitud()        // BelongsTo Solicitud
public function revisiones()       // HasMany RevisionSolicitud
```

### **Nuevo: RevisionSolicitud.php**

```php
public function solicitud()        // BelongsTo Solicitud
public function revisor()          // BelongsTo RevisorSolicitud
```

---

## 📬 Mailable Nuevo

### **SolicitudEnviadoARevisionMail.php**

```php
// Construcción
new SolicitudEnviadoARevisionMail($solicitud, $revisor)

// Automáticamente adjunta documentos:
- doc_solicitud
- doc_plano
- doc_otros
```

---

## 🚀 Controladores Actualizados

### **SolicitudController.php:**

**Métodos nuevos:**
- `enviarARevision(Request $request, Solicitud $solicitud)`
  - Valida 4 revisores
  - Crea registros en `revisores_solicitud`
  - Envía email a cada revisor
  - Cambia estado a `enviado_a_revision`

- `actualizarEstadoPago(Request $request, Solicitud $solicitud)`
  - Actualiza campo `estado_pago`

**Métodos actualizados:**
- `procesarEstado()` - Ahora acepta nuevos estados y `estado_pago`

### **RevisionSolicitudController.php (Nuevo):**

- `formulario($token)` - Muestra formulario (público)
- `guardar($token)` - Guarda revisión (público)
- `detallesFrPublico($token)` - Muestra detalles (público)

---

## ✅ Validaciones Implementadas

### **Envío a Revisión:**
- Exactamente 4 revisores
- Email válido para cada uno
- Nombre no vacío

### **Guardado de Revisión:**
- Resultado obligatorio
- Notas mínimo 5 caracteres
- Documento: máx 10MB, formatos: PDF, DOC, DOCX, JPG, PNG

---

## 🔔 Flujos de Notificación

| Evento | Destinatario | Método |
|--------|-------------|--------|
| Revisión recibida | Revisor | Email automático |
| Se reciben todas las revisiones | Sistema | Cambio automático a "aceptado" |
| Estado cambiadaa final | Cliente | Email/WhatsApp (si se selecciona) |
| Estado de pago | Cliente | Visible en seguimiento |

---

## 📊 Ejemplo de Uso

### **Escenario Completo:**

```
1. Cliente envía solicitud
   ↓ Estado: "registrado"

2. Funcionario abre solicitud
   ↓ Selecciona estado "Aceptar"
   ↓ Estado: "aceptado"

3. Funcionario envía a revisión
   ↓ Selecciona:
     • Revisor 1: Juan García (juan@gmail.com)
     • Revisor 2: María López (maria@gmail.com)
     • Revisor 3: Carlos Ruiz (carlos@gmail.com)
     • Revisor 4: Ana Martínez (ana@gmail.com)
   ↓ Estado: "enviado_a_revision"
   ↓ Correos enviados con documentos

4. Revisor 1 recibe email
   ↓ Haz clic en enlace
   ↓ Ve formulario, completa
   ↓ Resultado: "Aprobado"
   ↓ Notas: "Documentos en orden"
   ↓ Envía

5. Revisor 2, 3, 4 hacen lo mismo
   ↓ (Sistema espera a todos)

6. Sistema detecta todas las revisiones
   ↓ Estado automático: "aceptado"

7. Funcionario ve revisiones
   ↓ En panel: 4 revisiones completadas
   ↓ Selecciona: "Aprobar"
   ↓ También: "Pago Validado"
   ↓ Estado: "aprobado"

8. Cliente ve en seguimiento
   ↓ Estado: "Aprobado"
   ↓ Pago: "Validado"
   ↓ Timeline completa
   ↓ Puede acceder a certificado
```

---

## 🐛 Archivos Modificados

### **Migraciones Creadas:**
- `2026_05_12_000000_update_solicitudes_add_estados_pago_y_token.php`
- `2026_05_12_000001_create_revisores_solicitud_table.php`
- `2026_05_12_000002_create_revisiones_solicitud_table.php`

### **Modelos Actualizados:**
- `app/Models/Solicitud.php` (+3 relaciones, +3 campos fillable)
- `app/Models/RevisorSolicitud.php` (nuevo)
- `app/Models/RevisionSolicitud.php` (nuevo)

### **Controladores:**
- `app/Http/Controllers/SolicitudController.php` (+2 métodos)
- `app/Http/Controllers/RevisionSolicitudController.php` (nuevo)

### **Mail:**
- `app/Mail/SolicitudEnviadoARevisionMail.php` (nuevo)

### **Vistas públicas:**
- `resources/views/emails/solicitud-envio-revision.blade.php` (nuevo)
- `resources/views/revisiones/formulario.blade.php` (nuevo)
- `resources/views/revisiones/detalles.blade.php` (nuevo)

### **Vistas admin:**
- `resources/views/solicitudes/show.blade.php` (actualizada)
- `resources/views/solicitudes/seguimiento.blade.php` (actualizada)

### **Rutas:**
- `routes/web.php` (3 rutas públicas + 2 privadas nuevas)

---

## ⚙️ Configuración

No requiere configuración adicional. El sistema usa:
- **Mail Driver:** Ya configurado en `.env`
- **Storage:** Público en `storage/app/public/revisiones`
- **Base de Datos:** MySQL (migraciones ya ejecutadas)

---

## 🎯 Próximos Pasos (Opcional)

1. **Notificaciones WhatsApp a revisores** (opcional)
2. **Dashboard visual del estado de revisiones** (gráficos)
3. **Recordatorios automáticos a revisores pendientes**
4. **Exportar reporte de revisiones en PDF**
5. **Integración con firma digital en revisiones**

---

**Implementación completada al 100%** ✅
