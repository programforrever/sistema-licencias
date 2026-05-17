# Implementación: Ticket de Pago con QR

## 📋 Resumen
Se ha implementado un hermoso sistema de tickets de pago para las solicitudes online. Cuando un usuario completa su solicitud en el tramite online y se registra exitosamente, recibe una confirmación con un ticket profesional de 80mm que incluye:

- ✅ Código de seguimiento único
- ✅ Código QR escaneable con enlace de seguimiento 
- ✅ Monto pagado formateado
- ✅ Información de la solicitud
- ✅ Funciones de impresión y copia

## 🏗️ Cambios Realizados

### 1. **Base de Datos**
   - **Nueva Migración**: `2026_05_13_000000_add_monto_pago_to_solicitudes.php`
     - Agrega campo `monto_pago` (decimal 10,2) a la tabla `solicitudes`
     - Campo nullable para solicitudes without pago (si aplica)

### 2. **Modelos**
   - **Actualización**: `app/Models/Solicitud.php`
     - Agregado `monto_pago` a la propiedad `fillable`
     - Permite guardar y recuperar el monto pagado

### 3. **Vistas**
   - **Nueva**: `resources/views/solicitudes/ticket.blade.php`
     - Ticket completo de 80mm con QR full-size
     - Estilos CSS para impresión profesional
     - Estructura HTML semántica y responsive
   
   - **Nueva**: `resources/views/solicitudes/ticket-mini.blade.php`
     - Versión mini del ticket para previsualización
     - Optimizado para mostrar en la confirmación
     - Diseño compacto pero visualmente atractivo
   
   - **Actualizada**: `resources/views/solicitudes/confirmacion.blade.php`
     - Agregada nueva sección "Tu Ticket de Pago"
     - Integrada previsualización del ticket
     - Agregados botones de impresión y copia
     - Nuevos estilos CSS para el ticket
     - Scripts JavaScript para funcionalidades

### 4. **Funcionalidades JavaScript**
   
   #### `imprimirTicket()`
   ```javascript
   // Abre una ventana de impresión con el ticket
   // Permite al usuario imprimir directamente desde su navegador
   // Optimizado para papel thermal de 80mm
   ```
   
   #### `copiarCodigo()`
   ```javascript
   // Copia el código de seguimiento al portapapeles
   // Usa API Clipboard si está disponible
   // Fallback para navegadores antiguos
   // Muestra confirmación visual
   ```
   
   #### `mostrarToast(mensaje, tipo)`
   ```javascript
   // Notificación visual flotante
   // Tipos: success, error, info
   // Auto-desaparece después de 3 segundos
   ```

## 📐 Especificaciones del Ticket

### Dimensiones
- **Ancho**: 80mm (estándar thermal)
- **Alto**: Variable (~150mm)
- **Resolución**: 96 DPI

### Elementos del Ticket
1. **Header**
   - Logo/Nombre de municipalidad
   - Gradiente verde (#12961d a #0f2812)
   - Línea dorada (#f1c40f) separadora

2. **Código de Seguimiento**
   - Fuente monoespaciada
   - Tamaño grande (18px en mini, variable en full)
   - Color verde principal

3. **QR**
   - Generado con `SimpleSoftwareIO\QRCode`
   - Tamaño: 80px (mini), 100px (full)
   - Enlace: `route('solicitudes.seguimiento') + ?codigo=XXX`
   - Borde: 2px verde

4. **Monto Pagado**
   - Fondo amarillo (#fffacd)
   - Borde dorado (#f1c40f)
   - Typography: Fuerte y destacada

5. **Información Adicional**
   - Tipo de certificado
   - Estado de solicitud
   - Fecha y hora de registro

6. **Footer**
   - Background gris
   - Texto motivacional
   - Ícono de check verde

## 🎨 Estilos y Diseño

### Colores
- **Verde Principal**: #12961d (municipalidad)
- **Verde Oscuro**: #0f2812 (header)
- **Dorado Acento**: #f1c40f (separadores)
- **Fondo Claro**: #f0fdf4 (secciones)
- **Amarillo Pago**: #fffacd (monto)

### Tipografía
- **Principal**: Arial, sans-serif
- **Monoespaciada**: 'Courier New' (código)
- **Peso**: Bold para títulos, Regular para contenido

### Responsividad
- ✅ Adaptado para mobile
- ✅ Pantalla tablet
- ✅ Viewport desktop
- ✅ Optimizado para impresión

## 🖨️ Funciones de Impresión

### Print View
- Abre en nueva ventana
- Limpia estilos innecesarios
- Mantiene aspecto visual
- Listo para thermal printer (80mm)

### Opciones de Usuario
1. **Imprimir**: Envía a impresora directamente
2. **Copiar Código**: Copia al portapapeles
3. **Ver Estado**: Enlace a seguimiento

## 📱 Flujo de Usuario

```
1. Usuario completa solicitud
   ↓
2. Sube comprobantes y documentos
   ↓
3. Confirma envío
   ↓
4. ✅ Recibe página de confirmación
   ├── Código de seguimiento grande
   ├── Resumen de solicitud
   ├── [NUEVO] Ticket de pago con QR
   │   ├── Previsualización
   │   ├── Botón "Imprimir Ticket"
   │   └── Botón "Copiar Código"
   ├── Enlace a seguimiento
   └── Opción nueva solicitud
```

## 🔧 Dependencias Usadas

- **SimpleSoftwareIO QRCode**: Generación de QR
- **Bootstrap 5.3**: Grid y componentes
- **FontAwesome 6.4**: Iconografía
- **Google Fonts**: Plus Jakarta Sans

## 📝 Ejemplo de Uso

### En el Controlador
```php
$solicitud = Solicitud::create([
    'codigo_seguimiento' => 'SOL-2026-XXXXX',
    'monto_pago' => 150.00,
    // ... otros campos
]);

return redirect()->route('solicitudes.confirmacion', $solicitud->codigo_seguimiento);
```

### En la Confirmación
```blade
@include('solicitudes.ticket-mini', ['solicitud' => $solicitud])
```

## 🧪 Test & Validación

### Checklist de Pruebas
- [ ] Migración ejecutada sin errores
- [ ] Campo `monto_pago` agregado a tabla
- [ ] Ticket renderiza correctamente
- [ ] QR genera enlace válido
- [ ] Botón Imprimir abre ventana
- [ ] Botón Copiar funciona
- [ ] Responsive en mobile
- [ ] Toast de notificaciones funciona
- [ ] Estilos de impresión correctos

## 🚀 Mejoras Futuras

1. **Descarga PDF**
   - Integrar html2canvas + jsPDF
   - Generar PDF descargable

2. **Email con Ticket**
   - Adjuntar ticket al correo de confirmación
   - Versión HTML inline

3. **Código de Barras**
   - Agregar barcode 128 además de QR
   - Para sistemas de seguimiento manual

4. **Multi-idioma**
   - Traducir ticket a inglés
   - Soporte para otros idiomas

5. **Personalización**
   - Logo de municipalidad en ticket
   - Colores configurables

6. **Analytics**
   - Rastrear descargas/impresiones
   - Estadísticas de uso

## 📞 Soporte

Para dudas sobre la implementación:
- Revisar estilos CSS en `confirmacion.blade.php`
- Verificar funciones JS en el mismo archivo
- Adaptar colores en variables `:root`
- Consultar estructura HTML en `ticket-mini.blade.php`
