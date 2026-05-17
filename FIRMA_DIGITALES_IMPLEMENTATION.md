# 📝 Implementación: Módulo de Firmas Digitales

**Fecha:** May 12, 2026  
**Estado:** Planificación  
**Responsable:** Sistema de Licencias

---

## 🎯 Objetivo

Permitir que usuarios firmen certificados digitalmente. Admins suben una firma con foto, usuarios seleccionan posición y tamaño en el PDF, se incrusta la firma y se guarda como PDF firmado.

---

## 📋 Estructura de Base de Datos

### Tabla: `user_signatures` (NUEVA)

```sql
CREATE TABLE user_signatures (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNIQUE NOT NULL,
    firma_path VARCHAR(255) NOT NULL,           -- storage/signatures/user_1/firma.png
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Modificaciones: Tabla `licencias` (O tabla de certificados equivalente)

Agregar estos campos:

```sql
ALTER TABLE licencias ADD COLUMN signature_status ENUM('pendiente_firma', 'firmado') DEFAULT 'pendiente_firma';
ALTER TABLE licencias ADD COLUMN pdf_path VARCHAR(255) NULL;
ALTER TABLE licencias ADD COLUMN pdf_firmado_path VARCHAR(255) NULL;
ALTER TABLE licencias ADD COLUMN signed_by_user_id BIGINT NULL;
ALTER TABLE licencias ADD COLUMN signed_at TIMESTAMP NULL;

ALTER TABLE licencias ADD FOREIGN KEY (signed_by_user_id) REFERENCES users(id) ON DELETE SET NULL;
```

### Campos Explicación

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `signature_status` | ENUM | `pendiente_firma`: necesita firma, `firmado`: ya tiene firma |
| `pdf_path` | VARCHAR | Ruta PDF original sin firmar |
| `pdf_firmado_path` | VARCHAR | Ruta PDF con firma incrustada |
| `signed_by_user_id` | BIGINT | ID del usuario que realizó la firma |
| `signed_at` | TIMESTAMP | Fecha/hora de cuando se firmó |

---

## 📁 Estructura de Carpetas (Storage)

```
storage/
├── app/
│   ├── signatures/                 [NUEVO] Firmas de usuarios
│   │   ├── 1/
│   │   │   └── firma.png           (Admin sube: firma de usuario 1)
│   │   ├── 2/
│   │   │   └── firma.png           (Admin sube: firma de usuario 2)
│   │   └── ...
│   │
│   ├── certificados/               [YA EXISTE]
│   │   ├── original/
│   │   │   ├── licencia_1.pdf      (PDF original sin firmar)
│   │   │   ├── licencia_2.pdf
│   │   │   └── ...
│   │   └── firmados/               [NUEVO]
│   │       ├── licencia_1_signed.pdf    (PDF con firma incrustada)
│   │       ├── licencia_2_signed.pdf
│   │       └── ...
```

---

## 🔄 Flujos de Negocio

### 1️⃣ MÓDULO ADMIN: Subir Firma de Usuario

**Ruta:** `GET /admin/gestionar-firma`  
**Método:** GET/POST  
**Rol:** Solo administradores

**Especificaciones:**
- Form para subir imagen de firma
- Formatos: JPG, PNG, hasta 5MB
- Guardar en: `storage/app/signatures/{user_id}/firma.png`
- Si ya existe: reemplazar
- Almacenar registro en `user_signatures`

**Validaciones:**
- ✅ Solo admin puede acceder
- ✅ Archivo debe ser imagen válida
- ✅ Tamaño máximo 5MB
- ✅ Solo 1 firma por usuario

---

### 2️⃣ LISTA DE CERTIFICADOS: Ver Estado Firma

**Ruta:** `GET /licencias` (o la ruta actual)  
**Cambios en la vista:**

Para cada certificado en el listado:

```html
@if($licencia->signature_status === 'pendiente_firma')
    <span class="badge badge-warning">Sin Firmar</span>
    <button class="btn btn-primary btn-sm" @click="abrirModalFirma({{ $licencia->id }})">
        Firmar
    </button>
@elseif($licencia->signature_status === 'firmado')
    <span class="badge badge-success">Firmado</span>
    <p class="text-sm text-gray-600">
        Firmado por: {{ $licencia->signedByUser->name }}
        <br>
        {{ $licencia->signed_at->format('d/m/Y H:i') }}
    </p>
    <a href="/licencias/{{ $licencia->id }}/descargar" class="btn btn-success btn-sm">
        Descargar PDF
    </a>
@endif
```

---

### 3️⃣ MODAL: Firmador Interactivo

**Ruta:** `GET /licencias/{id}/preview-firma`  
**Carga:** PDF mediante JavaScript (pdf.js)

**Elementos:**
1. **Izquierda:** Preview del PDF (canvas)
2. **Derecha:** 
   - Imagen de firma (arrastrable)
   - Inputs: X, Y, Ancho, Alto
   - Botón: "Previsualizar"
   - Botón: "Confirmar y Firmar"

**Flujo Usuario:**
1. Se abre modal → Carga PDF original
2. Ve su firma disponible
3. Arrastra firma sobre PDF → Captura posición
4. Ajusta tamaño si necesario
5. Click "Confirmar Firma"
6. Backend incrusta y genera PDF firmado
7. Se descarga automáticamente
8. Modal se cierra, lista se actualiza

---

### 4️⃣ BACKEND: Procesar Firma

**Ruta:** `POST /licencias/{id}/firmar`  
**Parámetros:**
```json
{
    "posX": 150,
    "posY": 400,
    "ancho": 80,
    "alto": 40
}
```

**Proceso:**
1. ✅ Validar que el usuario es propietario del certificado
2. ✅ Cargar firma del usuario: `storage/app/signatures/{user_id}/firma.png`
3. ✅ Cargar PDF original: `{pdf_path}`
4. ✅ Usar librería mPDF para insertar imagen
5. ✅ Guardar PDF firmado: `storage/app/certificados/firmados/licencia_{id}_signed.pdf`
6. ✅ Actualizar registro:
   - `signature_status = 'firmado'`
   - `pdf_firmado_path = 'certificados/firmados/licencia_{id}_signed.pdf'`
   - `signed_by_user_id = auth()->id()`
   - `signed_at = now()`
7. ✅ Eliminar PDF anterior (reemplazar)
8. ✅ Retornar download del PDF firmado

**Código Pseudo:**
```php
// En controller
$licencia = Licencia::findOrFail($id);

// Validar propietario
$this->authorize('update', $licencia);

// Cargar firma del usuario
$firmaPath = storage_path("app/signatures/{$licencia->user_id}/firma.png");

// Cargar PDF original
$pdfPath = storage_path("app/{$licencia->pdf_path}");

// Usar mPDF para insertar
$pdf = new \Mpdf\Mpdf();
$pdf->SetSourceFile($pdfPath);
$page = $pdf->ImportPage(1); // Si es multipágina, iterar
$pdf->AddPage();
$pdf->UseTemplate($page);

// Insertar firma en coordenadas
$pdf->Image($firmaPath, $posX, $posY, $ancho, $alto);

// Guardar
$newPath = "storage/app/certificados/firmados/licencia_{$id}_signed.pdf";
$pdf->Output($newPath, 'F');

// Actualizar BD
$licencia->update([
    'signature_status' => 'firmado',
    'pdf_firmado_path' => 'certificados/firmados/licencia_{id}_signed.pdf',
    'signed_by_user_id' => auth()->id(),
    'signed_at' => now(),
]);

// Eliminar PDF anterior si existe
if ($licencia->pdf_path) {
    \Storage::delete($licencia->pdf_path);
}

return response()->download($newPath);
```

---

## 📋 Tareas de Implementación

- [ ] **1. Migración:** Crear campos en tabla licencias
- [ ] **2. Migración:** Crear tabla user_signatures
- [ ] **3. Modelo:** UserSignature (nueva clase)
- [ ] **4. Modelo:** Agregar relación a User y Licencia
- [ ] **5. Composer:** Instalar librería mPDF si no existe
- [ ] **6. Controller:** Admin/SignatureController (subir firma)
- [ ] **7. Controller:** LicenciaController (agregar métodos de firma)
- [ ] **8. Rutas:** POST /admin/upload-firma, GET /licencias/{id}/preview-firma, POST /licencias/{id}/firmar
- [ ] **9. Vista:** Modal interactivo (Blade + JavaScript + pdf.js)
- [ ] **10. Frontend:** Canvas para posicionar firma
- [ ] **11. Testing:** Pruebas unitarias e integración
- [ ] **12. Validaciones:** Permisos, formatos, tamaños

---

## 🎨 Requerimientos UI/UX

### Modal Firmador

```
┌─────────────────────────────────────────────┐
│  Firmar Certificado #123                    │
├────────────────────┬────────────────────────┤
│                    │  Firma del Usuario     │
│                    │  ┌───────────────┐    │
│  PDF Preview       │  │[Firma Image]  │    │
│  (Canvas)          │  └───────────────┘    │
│                    │                        │
│  Arrastra firma    │  X: ___  Y: ___       │
│  sobre el PDF      │  Ancho: ___           │
│                    │  Alto: ___            │
│                    │                        │
│                    │  [Previsualizar]      │
│                    │  [Confirmar Firma]    │
│                    │  [Cancelar]           │
└────────────────────┴────────────────────────┘
```

### Listado Licencias (cambios)

```
Licencia #123 | Contribuyente: XYZ | Estado: Pendiente de Firma
┌─────────────────────────────────────────────────────────┐
│ [Firmar]  Firmado por: --  Fecha: --                    │
│ [Ver PDF Original]                                      │
└─────────────────────────────────────────────────────────┘
```

---

## 📦 Dependencias Requeridas

```bash
# Si no está instalado
composer require mpdf/mpdf

# Frontend: pdf.js (en resources/js/)
npm install pdfjs-dist
```

---

## 🔐 Seguridad & Validaciones

✅ **Permisos:**
- Solo admins pueden subir firmas
- Solo el dueño del certificado puede firmarlo
- Solo ver certificados que son del usuario (o admin ve todos)

✅ **Validaciones:**
- Imagen de firma: JPG/PNG, máx 5MB
- Posición firma: X, Y, ancho, alto deben ser números válidos
- PDF original debe existir
- Certificado no debe estar ya firmado
- Usuario debe tener firma registrada antes de firmar

✅ **Manejo de Archivos:**
- Guardar con path único: `{timestamp}_{random}.png`
- Eliminar archivos antiguos al reemplazar
- Validar que archivos existan antes de usar

---

## 📝 Notas Importantes

1. **Versionado:** Se genera NUEVA versión del PDF (no se sobrescribe el original, pero se elimina el anterior firmado)
2. **PDF Multipágina:** Si hay múltiples páginas, aplicar firma a la primera (o a una específica)
3. **Transparencia:** Considerar fondo transparente en imagen de firma
4. **Escalas:** Asegurar que coordenadas se convierten correctamente de pantalla a PDF
5. **Auditoría:** Registrar en logs quién firmó, cuándo, desde dónde

---

## 🚀 Siguiente Paso

Cuando esté listo, comenzamos con:
1. Crear migraciones
2. Crear modelos y relaciones
3. Crear controladores
4. Implementar rutas
5. Escribir vistas y JavaScript

**Hecho:** 12 de Mayo de 2026
