<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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

        // Generar PDF si no existe
        if (!$licencia->pdf_path || !Storage::disk('public')->exists($licencia->pdf_path)) {
            try {
                $this->generatePdfForLicencia($licencia);
                $licencia->refresh(); // Recargar para obtener la nueva ruta
            } catch (\Exception $e) {
                return redirect()->route('licencias.show', $licencia)
                    ->with('error', 'No se pudo generar el PDF del certificado: ' . $e->getMessage());
            }
        }

        // Verificar que el usuario tenga firma registrada
        if (!auth()->user()->signature) {
            return redirect()->route('licencias.show', $licencia)
                ->with('error', 'Necesitas registrar una firma antes de firmar documentos');
        }

        return view('licencias.firmar', compact('licencia'));
    }

    /**
     * Genera y almacena el PDF de la licencia
     */
    private function generatePdfForLicencia(Licencia $licencia)
    {
        // Generar QR
        $urlVerificacion = route('licencias.verificar', $licencia->numero_licencia);
        $svgContent = QrCode::format('svg')
            ->size(120)
            ->margin(2)
            ->generate($urlVerificacion);

        if (extension_loaded('imagick')) {
            $imagick = new \Imagick();
            $imagick->readImageBlob($svgContent);
            $imagick->setImageFormat('png');
            $qr = base64_encode($imagick->getImageBlob());
            $mimeType = 'image/png';
        } else {
            $qr = base64_encode($svgContent);
            $mimeType = 'image/svg+xml';
        }

        // Detecta el tipo y carga la vista correcta
        $vista = match($licencia->tipo_certificado) {
            'evento_publico' => 'pdf.evento_publico',
            default          => 'pdf.licencia',
        };

        // Generar PDF
        $pdf = Pdf::loadView($vista, compact('licencia', 'qr', 'mimeType'))
            ->setPaper('a4', 'portrait');

        // Crear directorio si no existe
        Storage::disk('public')->makeDirectory('certificados', 0755, true);

        // Guardar el PDF usando el número de certificado como nombre
        $fileName = 'certificados/' . $licencia->numero_licencia . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        // Actualizar la licencia con la ruta del PDF
        $licencia->update(['pdf_path' => $fileName]);
    }

    /**
     * Muestra el preview del PDF para firmar
     */
    public function previewFirma(Licencia $licencia)
    {
        // Verificar que el usuario tenga permisos para ver/firmar
        $this->authorize('sign', $licencia);

        // Generar PDF si no existe
        if (!$licencia->pdf_path || !Storage::disk('public')->exists($licencia->pdf_path)) {
            try {
                $this->generatePdfForLicencia($licencia);
            } catch (\Exception $e) {
                return response()->json(['error' => 'No se pudo generar el PDF: ' . $e->getMessage()], 500);
            }
        }

        // Obtener la firma del usuario
        $signature = auth()->user()->signature;
        if (!$signature || !Storage::disk('public')->exists($signature->firma_path)) {
            return response()->json(['error' => 'No tienes firma registrada'], 403);
        }

        // Obtener URLs públicas - usar ruta personalizada /storage/
        $pdfUrl = url('storage/' . $licencia->pdf_path);
        $firmaUrl = url('storage/' . $signature->firma_path);

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
            'canvasWidth' => 'nullable|numeric|min:1',
            'canvasHeight' => 'nullable|numeric|min:1',
        ]);

        try {
            // Generar PDF si no existe
            if (!$licencia->pdf_path || !Storage::disk('public')->exists($licencia->pdf_path)) {
                try {
                    $this->generatePdfForLicencia($licencia);
                    // Recargar la licencia para obtener la nueva ruta
                    $licencia->refresh();
                } catch (\Exception $e) {
                    return response()->json(['error' => 'No se pudo generar el PDF: ' . $e->getMessage()], 500);
                }
            }

            // Validar que el certificado tenga PDF
            if (!$licencia->pdf_path) {
                return response()->json(['error' => 'No se pudo asignar la ruta del PDF'], 500);
            }

            if (!Storage::disk('public')->exists($licencia->pdf_path)) {
                return response()->json(['error' => 'El archivo PDF no existe en el servidor'], 500);
            }

            // Verificar que el usuario tenga firma
            $signature = auth()->user()->signature;
            if (!$signature) {
                return response()->json(['error' => 'No tienes una firma registrada. Contacta al administrador.'], 403);
            }

            if (!Storage::disk('public')->exists($signature->firma_path)) {
                return response()->json(['error' => 'Tu firma no se encontró en el servidor'], 403);
            }

            // Obtener rutas
            $pdfPath = Storage::disk('public')->path($licencia->pdf_path);
            $firmaPath = Storage::disk('public')->path($signature->firma_path);

            // Crear directorio para PDFs firmados si no existe
            Storage::disk('public')->makeDirectory('certificados/firmados', 0755, true);

            // Generar nombre único para el PDF firmado
            $newFileName = 'certificados/firmados/' . $licencia->numero_licencia . '_firmado_' . time() . '.pdf';
            $newPdfPath = Storage::disk('public')->path($newFileName);

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
                    // Obtener dimensiones de la página actual (en mm)
                    $pdfWidth_mm = $mpdf->w;
                    $pdfHeight_mm = $mpdf->h;
                    
                    // Los valores vienen del canvas renderizado a escala 1.5
                    $scale = 1.5;
                    
                    // Obtener dimensiones del canvas escalado (proporcionadas por JavaScript)
                    $canvasWidthEscalado = $validated['canvasWidth'] ?? 892;      // anchura del canvas ESCALADO 1.5x
                    $canvasHeightEscalado = $validated['canvasHeight'] ?? 1262;   // altura del canvas ESCALADO 1.5x
                    
                    // Calcular las dimensiones del canvas SIN escala (tamaño real del PDF en píxeles)
                    $canvasWidthReal = $canvasWidthEscalado / $scale;
                    $canvasHeightReal = $canvasHeightEscalado / $scale;
                    
                    // Los valores posX, posY, ancho, alto ya están en píxeles del canvas ESCALADO
                    // Convertirlos a píxeles del PDF original (sin escala)
                    $pdfPixelX = $validated['posX'] / $scale;
                    $pdfPixelY = $validated['posY'] / $scale;
                    $pdfPixelAncho = $validated['ancho'] / $scale;
                    $pdfPixelAlto = $validated['alto'] / $scale;
                    
                    // Rata de conversión: mm_por_pixel = dimension_pdf_mm / tamaño_real_en_pixeles
                    $mmPerPixelX = $pdfWidth_mm / $canvasWidthReal;
                    $mmPerPixelY = $pdfHeight_mm / $canvasHeightReal;
                    
                    $posX_mm = $pdfPixelX * $mmPerPixelX;
                    $posY_mm = $pdfPixelY * $mmPerPixelY;
                    $ancho_mm = $pdfPixelAncho * $mmPerPixelX;
                    $alto_mm = $pdfPixelAlto * $mmPerPixelY;

                    // Debug log
                    \Log::info('Inserting signature', [
                        'posX_mm' => $posX_mm,
                        'posY_mm' => $posY_mm,
                        'ancho_mm' => $ancho_mm,
                        'alto_mm' => $alto_mm,
                        'pdfWidth_mm' => $pdfWidth_mm,
                        'pdfHeight_mm' => $pdfHeight_mm,
                        'canvasWidthEscalado' => $canvasWidthEscalado,
                        'canvasHeightEscalado' => $canvasHeightEscalado,
                        'canvasWidthReal' => $canvasWidthReal,
                        'canvasHeightReal' => $canvasHeightReal,
                    ]);

                    try {
                        // Insertar la signature en el PDF (paint = true por defecto)
                        $mpdf->Image($firmaPath, $posX_mm, $posY_mm, $ancho_mm, $alto_mm);
                        \Log::info('Signature inserted successfully');
                    } catch (\Exception $imgError) {
                        \Log::error('Error inserting image', ['error' => $imgError->getMessage()]);
                        throw $imgError;
                    }
                }
            }

            // Guardar el PDF firmado
            try {
                $mpdf->Output($newPdfPath, 'F');
                \Log::info('Signed PDF saved', ['path' => $newPdfPath]);
            } catch (\Exception $saveError) {
                \Log::error('Error saving PDF', ['error' => $saveError->getMessage()]);
                throw $saveError;
            }

            // Eliminar PDF anterior si existe
            if ($licencia->pdf_firmado_path && Storage::disk('public')->exists($licencia->pdf_firmado_path)) {
                Storage::disk('public')->delete($licencia->pdf_firmado_path);
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
                'downloadUrl' => Storage::disk('public')->url($newFileName),
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
     * Adjunta un PDF ya firmado (mediante drag-drop)
     */
    public function adjuntarPdfFirmado(Request $request, Licencia $licencia)
    {
        // Verificar permisos
        $this->authorize('sign', $licencia);

        // Validar que sea un PDF
        $validated = $request->validate([
            'pdf_adjunto' => 'required|file|mimes:pdf|max:10240', // máximo 10MB
        ], [
            'pdf_adjunto.required' => 'Debes seleccionar un archivo PDF',
            'pdf_adjunto.mimes' => 'El archivo debe ser un PDF válido',
            'pdf_adjunto.max' => 'El archivo no puede exceder 10MB',
        ]);

        try {
            // Crear directorio para PDFs adjuntos si no existe
            Storage::disk('public')->makeDirectory('certificados/adjuntos', 0755, true);

            // Generar nombre único para el PDF adjunto
            $fileName = 'certificados/adjuntos/' . $licencia->numero_licencia . '_adjunto_' . time() . '.pdf';
            
            // Guardar el archivo
            $path = $request->file('pdf_adjunto')->storeAs(
                'certificados/adjuntos',
                $licencia->numero_licencia . '_adjunto_' . time() . '.pdf',
                'public'
            );

            // Eliminar PDF adjunto anterior si existe
            if ($licencia->pdf_adjunto_firmado_path && Storage::disk('public')->exists($licencia->pdf_adjunto_firmado_path)) {
                Storage::disk('public')->delete($licencia->pdf_adjunto_firmado_path);
            }

            // Actualizar registro con el nuevo estado "firmado_adjunto"
            $licencia->update([
                'signature_status' => 'firmado_adjunto',
                'pdf_adjunto_firmado_path' => $path,
                'firmado_adjunto_at' => now(),
                'signed_by_user_id' => auth()->id(),
                'signed_at' => now(),
            ]);

            \Log::info('PDF adjunto firmado cargado', [
                'licencia_id' => $licencia->id,
                'user_id' => auth()->id(),
                'path' => $path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'PDF firmado adjuntado exitosamente. Estado: Firmado Adjunto',
                'downloadUrl' => Storage::disk('public')->url($path),
                'signature_status' => 'firmado_adjunto',
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al adjuntar PDF firmado', [
                'licencia_id' => $licencia->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Error al adjuntar el PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Descarga el PDF firmado (sea mediante firma digital o adjunto)
     */
    public function descargar(Licencia $licencia)
    {
        // Verificar permisos
        $this->authorize('view', $licencia);

        // Priorizar PDF adjunto si existe
        $pdfPath = $licencia->pdf_adjunto_firmado_path ?? $licencia->pdf_firmado_path;
        
        if (!$pdfPath || !Storage::disk('public')->exists($pdfPath)) {
            return response()->json(['error' => 'PDF firmado no encontrado'], 404);
        }

        return Storage::disk('public')->download(
            $pdfPath,
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

        if (!$licencia->pdf_path || !Storage::disk('public')->exists($licencia->pdf_path)) {
            return response()->json(['error' => 'PDF no encontrado'], 404);
        }

        return Storage::disk('public')->download(
            $licencia->pdf_path,
            "licencia_{$licencia->numero_licencia}.pdf"
        );
    }
}
