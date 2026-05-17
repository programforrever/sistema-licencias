# 📝 Guía de Uso: Firma Digital de Solicitudes

**Implementación Completada:** 14 de Mayo de 2026

---

## 🚀 Cómo Usar el Sistema de Firmas Digitales

### **Para Administradores: Cargar Firmas de Usuarios**

1. **Acceder al Panel de Gestión**
   - En el menú lateral, haz click en "**Firmas Digitales**"
   - Se abrirá la lista de todos los usuarios del sistema

2. **Cargar Firma de un Usuario**
   - Haz click en el botón "**Cargar**" o "**Actualizar**" en la tarjeta del usuario
   - Se abrirá un formulario para subir la imagen de firma
   - Selecciona una imagen (JPG o PNG, máximo 5MB)
   - Recomendación: usa PNG con fondo transparente para mejor resultado
   - Haz click en "**Cargar Firma**" para finalizar

3. **Gestionar Firmas Existentes**
   - Si un usuario ya tiene firma registrada, se mostrará una vista previa
   - Puedes actualizar la firma subiendo una nueva imagen
   - Puedes eliminar la firma haciendo click en "**Eliminar**"

---

### **Para Usuarios/Admin: Firmar Documentos de Solicitudes**

#### **Paso 1: Acceder a la Solicitud**
```
1. Ve a Solicitudes en el menú
2. Abre la solicitud que deseas firmar
3. Busca la sección "Estado de Firma Digital"
```

#### **Paso 2: Ver el Estado**
```
- Si dice "PENDIENTE": Necesitas firmar el documento
- Si dice "FIRMADO": Ya fue firmado (puedes descargar el PDF)
```

#### **Paso 3: Firmar (si está PENDIENTE)**
```
1. Haz click en el botón "Firmar Documento"
2. Se abrirá una vista interactiva con:
   - PDF a la izquierda (tu documento)
   - Controles de firma a la derecha
3. Verás tu firma disponible en la sección de controles
```

#### **Paso 4: Posicionar la Firma en el PDF**

**Opción A: Arrastrar (Drag & Drop)**
```
1. Haz click sobre tu firma
2. Arrastrala hasta donde deseas colocarla en el PDF
3. Suelta el ratón para posicionarla
```

**Opción B: Ajustar Manualmente**
```
1. Completa los campos:
   - Posición X (píxeles desde la izquierda)
   - Posición Y (píxeles desde arriba)
   - Ancho (tamaño horizontal)
   - Alto (tamaño vertical)
2. Los valores se actualizarán en tiempo real
```

#### **Paso 5: Previsualizar y Confirmar**
```
1. Haz click en "Previsualizar" para ver cómo quedará
2. Ve el PDF con un rectángulo azul mostrando dónde se colocará
3. Si está bien, haz click en "Confirmar y Firmar"
4. Si necesitas ajustar, cambia los valores y vuelve a previsualizar
```

#### **Paso 6: Finalizar**
```
1. El sistema procesará la firma (puede tardar unos segundos)
2. Se descargará automáticamente el PDF firmado
3. La página se actualizará mostrando el estado "FIRMADO"
```

---

## 📂 Archivos Generados

### **Ubicación en el Servidor**
```
storage/
├── app/
│   ├── signatures/              # Firmas cargadas por admin
│   │   ├── 1/
│   │   │   └── firma.png
│   │   ├── 2/
│   │   │   └── firma.png
│   │   └── ...
│   │
│   └── certificados/
│       ├── original/            # PDFs sin firmar
│       │   └── solicitud_1.pdf
│       └── firmados/            # PDFs firmados
│           ├── solicitud_1_1234567890.pdf
│           ├── solicitud_2_1234567890.pdf
│           └── ...
```

---

## ⚙️ Especificaciones Técnicas

### **Requisitos**
- ✅ mPDF v8.3.1 (para procesar PDFs)
- ✅ pdf.js (para visualizar PDFs en navegador)
- ✅ Laravel 10+

### **Formatos Soportados**
- **Firmas:** JPG, PNG (máximo 5MB)
- **PDFs:** Todos los tipos estándar (PDF)
- **Imagen que se incrusta:** PNG transparente o JPG

### **Resolución Recomendada**
- Ancho mínimo: 200px
- Altura mínima: 100px
- Mejor con fondo transparente (PNG)

---

## 🔐 Seguridad y Permisos

### **¿Quién puede hacer qué?**

| Acción | Admin | Usuario |
|--------|-------|---------|
| Cargar firmas de usuarios | ✅ | ❌ |
| Ver sus propias firmas | ✅ | ✅ |
| Firmar sus solicitudes | ✅ | ✅ |
| Firmar solicitudes de otros | ✅ | ❌ |
| Descargar PDFs | ✅ | ✅ |

---

## 📋 Rutas y URLs

| Función | Ruta |
|---------|------|
| Panel de gestión de firmas | `/admin/gestionar-firmas` |
| Editar firma de usuario | `/admin/gestionar-firmas/{user}/editar` |
| Firmar solicitud | `/solicitudes/{solicitud}/firmar` |
| Descargar PDF firmado | `/solicitudes/{solicitud}/descargar` |

---

## 🐛 Solución de Problemas

### **"No tienes firma registrada"**
- Necesitas que un admin te cargue una firma primero
- Contacta al administrador para que suba tu firma

### **"PDF no encontrado"**
- El documento debe tener un PDF asociado
- Contacta al administrador para que genere el PDF

### **La firma no se ve en la previsualización**
- Verifica que hayas movido la firma o ingresado coordenadas
- Asegúrate de que los valores X e Y no sean negativos
- Verifica que el ancho y alto sean mayores a 1

### **El PDF firmado no se descarga**
- Verifica tu conexión a internet
- Intenta nuevamente
- Si persiste, contacta al administrador

---

## 📞 Soporte

Para reportar problemas o sugerencias:
1. Contacta con el equipo de desarrolladores
2. Menciona el navegador que usas
3. Proporciona captura de pantalla si es posible

---

**Última actualización:** 14 de Mayo de 2026
