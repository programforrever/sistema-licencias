@component('mail::message')
# Actualización de tu Trámite

Hola {{ $solicitud->nombres_solicitante }},

Tu solicitud de certificado ha sido **{{ strtolower($estado_legible) }}**.

## 📋 Detalles de tu Trámite

- **Código de Seguimiento**: {{ $solicitud->codigo_seguimiento }}
- **Tipo de Certificado**: {{ $tipo_certificado }}
- **Estado Actual**: {{ $estado_legible }}
- **Fecha de Actualización**: {{ $solicitud->updated_at->format('d/m/Y H:i') }}

@if($solicitud->tipo_certificado !== 'evento_publico')
- **Nombre Comercial**: {{ $solicitud->nombre_comercial ?? 'N/A' }}
- **Dirección**: {{ $solicitud->direccion ?? 'N/A' }}
@else
- **Nombre del Evento**: {{ $solicitud->nombre_evento ?? 'N/A' }}
- **Fecha del Evento**: {{ $solicitud->fecha_evento?->format('d/m/Y') ?? 'N/A' }}
- **Organizador**: {{ $solicitud->organizador_nombre ?? 'N/A' }}
@endif

@if($solicitud->observaciones)
## 📌 Observaciones

{{ $solicitud->observaciones }}
@endif

## Próximos Pasos

@if($solicitud->estado === 'recibido')
Tu trámite ha sido recibido correctamente. Pronto será revisado por nuestro equipo.
@elseif($solicitud->estado === 'en_revision')
Tu trámite se encuentra en revisión. Si necesitamos información adicional, nos pondremos en contacto contigo.
@elseif($solicitud->estado === 'aprobado')
¡Felicidades! Tu trámite ha sido aprobado. Puedes recoger tu certificado en nuestras oficinas.
**Horario de atención**: Lunes a viernes, 8:00 AM - 4:00 PM
@elseif($solicitud->estado === 'rechazado')
Lamentablemente tu trámite fue rechazado. Por favor, revisa las observaciones y contacta con nuestras oficinas para más detalles.
**Teléfono**: +51 (66) 3128080
@endif

@component('mail::button', ['url' => route('solicitudes.seguimiento', []) . '?codigo=' . $solicitud->codigo_seguimiento, 'color' => 'primary'])
Ver Detalles de mi Trámite
@endcomponent

---

Si tienes dudas, responde este correo o contacta con nuestras oficinas.

**Municipalidad Distrital de Andrés Avelino Cáceres**  
Ayacucho, Perú

@endcomponent
