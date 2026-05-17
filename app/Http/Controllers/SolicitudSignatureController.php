<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class LicenciaSignatureController extends Controller
{
    /**
     * Muestra la vista para firmar un certificado
     */
    public function show(Licencia $licencia)
    {
        // Verificar que el usuario tenga permisos para ver/firmar
        $this->authorize('sign', $licencia);

        // Validar que el certificado tenga PDF y no esté firmado
        if (!$licencia->pdf_path || !Storage::exists($licencia->pdf_path)) {
            return redirect()->route('licencias.show', $licencia)
                ->with('error', 'PDF no encontrado');
        }

        if ($licencia->signature_status === 'firmado') {
            return redirect()->route('licencias.show', $licencia)
                ->with('info', 'El certificado ya está firmado');
        }

        // Verificar que el usuario tenga firma registrada
        if (!auth()->user()->signature) {
            return redirect()->route('licencias.show', $licencia)
                ->with('error', 'Necesitas registrar una firma antes de firmar documentos');
        }

        return view('licencias.firmar', compact('licencia'));
    }

    /**
     * Muestra el preview del PDF para firmar
     */
    public function previewFirma(Licencia $licencia)
    {
        // Verificar que el usuario tenga permisos para ver/firmar
        $this->authorize('sign', $licencia);

        // Validar que el certificado tenga PDF
        if (!$licencia->pdf_path || !Storage::exists($licencia->pdf_path)) {
            return response()->json(['error' => 'PDF no encontrado'], 404);
        }

        // Obtener la firma del usuario
        $signature = auth()->user()->signature;
        if (!$signature || !Storage::exists($signature->firma_path)) {
            return response()->json(['error' => 'No tienes firma registrada'], 403);
        }

        // Obtener URLs públicas
        $pdfUrl = Storage::url($licencia->pdf_path);
        $firmaUrl = Storage::url($signature->firma_path);

        return response()->json([
            'success' => true,
            'pdfUrl' => $pdfUrl,
            'firmaUrl' => $firmaUrl,
            'licencia_id' => $licencia->id,
        ]);
    }

    /**
     * Procesa la firma del certificado
     */
    public function firmar(Request $request, Licencia $licencia)
    {
        // Verificar permisos
        $this->authorize('sign', $licencia);

        // Validar entrada
        $validated = $request->validate([
            'posX' => 'required|numeric|min:0',
            'posY' => 'required|numeric|min:0',
            'ancho' => 'required|numeric|min:1|max:200',
            'alto' => 'required|numeric|min:1|max:200',
        ]);

        try {
            // Validar que el certificado tenga PDF
            if (!$licencia->pdf_path || !Storage::exists($licencia->pdf_path)) {
                return response()->json(['error' => 'PDF no encontrado'], 404);
            }

            // Verificar que el usuario tenga firma
            $signature = auth()->user()->signature;
            if (!$signature || !Storage::exists($signature->firma_path)) {
                return response()->json(['error' => 'No tienes firma registrada'], 403);
            }

            // Validar que no esté ya firmado
            if ($licencia->signature_status === 'firmado') {
                return response()->json(['error' => 'El certificado ya está firmado'], 403);
            }

            // Obtener rutas
            $pdfPath = Storage::path($licencia->pdf_path);
            $firmaPath = Storage::path($signature->firma_path);

            // Crear directorio para PDFs firmados si no existe
            Storage::makeDirectory('certificados/firmados', 0755, true);

            // Generar nombre único para el PDF firmado
            $newFileName = 'certificados/firmados/licencia_' . $licencia->id . '_' . time() . '.pdf';
            $newPdfPath = Storage::path($newFileName);

            // Crear instancia de mPDF
            $mpdf = new Mpdf();

            // Importar el PDF original
            $pageCount = $mpdf->setSourceFile($pdfPath);
            
            // Procesar todas las páginas
            for ($pageNum = 1; $pageNum <= $pageCount; $pageNum++) {
                $mpdf->AddPage();
                $templateId = $mpdf->importPage($pageNum);
                $mpdf->useTemplate($templateId);
                
                // Agregar firma solo en la primera página
                if ($pageNum === 1) {
                    // Convertir posiciones (están en píxeles, necesitan ajustarse a mm)
                    // Aproximadamente: 1mm = 2.834645669 píxeles
                    $posX = $validated['posX'] / 2.834645669;
                    $posY = $validated['posY'] / 2.834645669;
                    $ancho = $validated['ancho'] / 2.834645669;
                    $alto = $validated['alto'] / 2.834645669;

                    $mpdf->Image($firmaPath, $posX, $posY, $ancho, $alto, '', '', false, false, 0, 'C');
                }
            }

            // Guardar el PDF firmado
            $mpdf->Output($newPdfPath, 'F');

            // Eliminar PDF anterior si existe
            if ($licencia->pdf_firmado_path && Storage::exists($licencia->pdf_firmado_path)) {
                Storage::delete($licencia->pdf_firmado_path);
            }

            // Actualizar registro
            $licencia->update([
                'signature_status' => 'firmado',
                'pdf_firmado_path' => $newFileName,
                'signed_by_user_id' => auth()->id(),
                'signed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Certificado firmado exitosamente',
                'downloadUrl' => Storage::url($newFileName),
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al firmar certificado', [
                'licencia_id' => $licencia->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Error al procesar la firma: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Descarga el PDF firmado
     */
    public function descargar(Licencia $licencia)
    {
        // Verificar permisos
        $this->authorize('view', $licencia);

        if (!$licencia->pdf_firmado_path || !Storage::exists($licencia->pdf_firmado_path)) {
            return response()->json(['error' => 'PDF firmado no encontrado'], 404);
        }

        return Storage::download(
            $licencia->pdf_firmado_path,
            "licencia_{$licencia->numero_licencia}_firmada.pdf"
        );
    }

    /**
     * Descarga el PDF original sin firmar
     */
    public function descargarOriginal(Licencia $licencia)
    {
        // Verificar permisos
        $this->authorize('view', $licencia);

        if (!$licencia->pdf_path || !Storage::exists($licencia->pdf_path)) {
            return response()->json(['error' => 'PDF no encontrado'], 404);
        }

        return Storage::download(
            $licencia->pdf_path,
            "licencia_{$licencia->numero_licencia}.pdf"
        );
    }
}
