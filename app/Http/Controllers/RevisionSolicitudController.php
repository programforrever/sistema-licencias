<?php

namespace App\Http\Controllers;

use App\Models\RevisorSolicitud;
use App\Models\RevisionSolicitud;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RevisionSolicitudController extends Controller
{
    /**
     * Mostrar formulario de revisión
     */
    public function formulario($token)
    {
        $revisor = RevisorSolicitud::where('token_revisor', $token)
            ->firstOrFail();
        
        $solicitud = $revisor->solicitud;
        
        // Verificar si ya revisó
        $revision_existente = RevisionSolicitud::where('revisor_solicitud_id', $revisor->id)->first();

        return view('revisiones.formulario', [
            'revisor' => $revisor,
            'solicitud' => $solicitud,
            'revision_existente' => $revision_existente,
        ]);
    }

    /**
     * Guardar revisión del documento
     */
    public function guardar(Request $request, $token)
    {
        $revisor = RevisorSolicitud::where('token_revisor', $token)
            ->firstOrFail();
        
        $solicitud = $revisor->solicitud;

        $request->validate([
            'resultado_revision' => 'required|in:aprobado,requiere_cambios,rechazado',
            'notas' => 'required|string|min:5',
            'documento_revision' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        try {
            // Eliminar revisión anterior si existe
            RevisionSolicitud::where('revisor_solicitud_id', $revisor->id)->delete();

            // Procesar documento si se cargó
            $documento_path = null;
            if ($request->hasFile('documento_revision')) {
                $documento_path = $request->file('documento_revision')
                    ->store('revisiones', 'public');
            }

            // Guardar revisión
            $revision = RevisionSolicitud::create([
                'solicitud_id' => $solicitud->id,
                'revisor_solicitud_id' => $revisor->id,
                'resultado_revision' => $request->resultado_revision,
                'notas' => $request->notas,
                'documento_revision' => $documento_path,
                'entregado_at' => now(),
            ]);

            // Actualizar estado del revisor
            $revisor->update([
                'estado_revision' => 'revisado',
                'revisado_at' => now(),
            ]);

            // Verificar si todos los revisores ya han enviado sus revisiones
            $revisor_pendientes = $solicitud->revisores()
                ->where('estado_revision', 'pendiente')
                ->count();

            // Si no hay revisores pendientes, cambiar estado a aceptado
            if ($revisor_pendientes === 0) {
                $solicitud->update(['estado' => 'aceptado']);
                Log::info("Solicitud {$solicitud->id} cambió a aceptado - todas las revisiones recibidas");
            }

            return redirect()->back()
                ->with('success', '✅ Revisión guardada correctamente. Gracias por tu aporte.');

        } catch (\Exception $e) {
            Log::error("Error guardarRevision: " . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Error al guardar la revisión: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalles de la solicitud con acceso público (revisor)
     */
    public function detallesFrPublico($token)
    {
        $revisor = RevisorSolicitud::where('token_revisor', $token)
            ->firstOrFail();
        
        $solicitud = $revisor->solicitud;

        return view('revisiones.detalles', [
            'solicitud' => $solicitud,
            'revisor' => $revisor,
        ]);
    }
}
