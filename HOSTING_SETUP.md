# 🚀 Guía de Instalación - Sistema ITSE

## 📋 Checklist Rápido

- [ ] Archivos subidos al hosting
- [ ] Acceder a `/diagnostico.php` para verificar
- [ ] Ejecutar `/install.php`
- [ ] Eliminar `install.php` y archivos de diagnóstico

---

## 1️⃣ Estructura de Carpetas en el Hosting

```
Tu Hosting / Panel de Control
│
└── Dominio: itse.cristhiancode.io
    └── public_html/ (o www/)
        └── licencia/               ← Tu proyecto aquí
            ├── public/             ← ⭐ DocumentRoot debe apuntar aquí
            │   ├── index.php
            │   ├── install.php     ← Instalador
            │   ├── diagnostico.php
            │   └── .htaccess
            │
            ├── app/
            ├── bootstrap/
            ├── config/
            ├── resources/
            ├── routes/
            ├── storage/
            ├── vendor/
            ├── artisan
            ├── composer.json
            ├── .env                ← Se crea automáticamente
            ├── .htaccess           ← Se crea automáticamente
            └── diagnostico.php     ← Para diagnosticar
```

---

## 2️⃣ Pasos de Subida

### Opción A: Con cPanel (Hosting compartido)

1. **File Manager** → Navega a `public_html/`
2. Crea carpeta `licencia`
3. Sube el contenido del proyecto dentro
4. Ve a **Addon Domains** o **Subdomains**
5. Configura `itse.cristhiancode.io` →  Apunta a `public_html/licencia/public`
6. Asegúrate de que `mod_rewrite` esté habilitado

### Opción B: Con FTP

```bash
# Conéctate con tu cliente FTP (FileZilla, WinSCP, etc.)
# Sube todo el contenido a: /public_html/licencia/

# O en terminal (SSH):
cd /home/tu_usuario/public_html/
mkdir licencia
cd licencia
# Sube archivos aquí
chmod -R 755 .
chmod -R 777 storage bootstrap
```

---

## 3️⃣ Verificación

### Paso 1: Acceder al Diagnóstico

```
https://itse.cristhiancode.io/diagnostico.php
```

**Deberías ver:**
- ✅ PHP Version
- ✅ DOCUMENT_ROOT correcto
- ✅ Archivos encontrados
- ✅ Permisos correctos

**Si ves 404:** El DocumentRoot no está bien configurado

**Si ves la página de raíz:** El DocumentRoot apunta a `/licencia` en lugar de `/licencia/public`

### Paso 2: Ejecutar Instalador

```
https://itse.cristhiancode.io/install.php
```

**Sigue los 3 pasos:**
1. ✓ Validación de requisitos
2. 📝 Ingresa configuración (BD, Email, WhatsApp)
3. ⚙️ Instalación automática

### Paso 3: Acceder a la Aplicación

```
https://itse.cristhiancode.io/login
```

---

## 4️⃣ Solución de Problemas

### Error 404 en /install.php

**Causa:** DocumentRoot no está apuntando a `/licencia/public`

**Solución:**
1. Accede a cPanel → **Addon Domains** o **Subdomains**
2. Busca `itse.cristhiancode.io`
3. Cambia la ruta a: `/licencia/public`
4. Guarda cambios
5. Espera 5 minutos para que se propaguen

### Error 403 Forbidden

**Causa:** Permisos insuficientes en las carpetas

**Solución (vía SSH):**
```bash
cd licencia
chmod -R 755 .
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

**O (vía cPanel File Manager):**
1. Click derecho en carpeta
2. "Change Permissions"
3. `777` para `storage` y `bootstrap/cache`
4. `755` para el resto

### Error 500 en formularios

**Causa:** `.htaccess` no está siendo procesado

**Solución:** Pide al soporte que habilite `mod_rewrite`:

> "Por favor, habilita mod_rewrite en Apache para mi dominio itse.cristhiancode.io"

---

## 5️⃣ Después de Instalar

### ✅ Limpieza por Seguridad

Elimina estos archivos:
```bash
rm public/install.php
rm public/diagnostico.php
rm diagnostico.php
```

O vía FTP elíminalos manualmente

### ✅ Verificar Funcionamiento

- Accede a: `https://itse.cristhiancode.io/login`
- Prueba crear una solicitud: `https://itse.cristhiancode.io/tramite`
- Verifica seguimiento: `https://itse.cristhiancode.io/tramite/seguimiento`

---

## 📝 Comandos útiles (si tienes SSH)

```bash
# Entrar al proyecto
cd /path/to/licencia

# Ver estructura
ls -la

# Verificar permisos
ls -la storage/
ls -la bootstrap/cache/

# Limpiar cache (si es necesario)
php artisan cache:clear
php artisan config:clear

# Ver logs de errores
tail -100 storage/logs/laravel.log

# Recrear symlink de storage
php artisan storage:link
```

---

## 🆘 En Caso de Problemas

1. **Accede a:** `https://itse.cristhiancode.io/diagnostico.php`
2. **Copia toda la información que ves**
3. **Envía al soporte del hosting:**
   - DOCUMENT_ROOT
   - Errores mostrados
   - Solicita:
     - Habilitar `mod_rewrite`
     - Configurar DocumentRoot a `/licencia/public`

---

## ✨ ¡Listo!

Una vez completada la instalación, el sistema estará operativo en:

```
https://itse.cristhiancode.io/
```

¡Disfruta del Sistema de Certificados ITSE! 🎉
