<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function generarLicencia(Licencia $licencia)
    {
        // ✅ Si está firmado, descargar el PDF firmado
        if ($licencia->signature_status === 'firmado' && $licencia->pdf_firmado_path) {
            if (Storage::disk('public')->exists($licencia->pdf_firmado_path)) {
                return Storage::disk('public')->download(
                    $licencia->pdf_firmado_path,
                    'certificado-' . $licencia->numero_licencia . '-firmado.pdf'
                );
            }
        }

        // ✅ Si tiene PDF almacenado, descargarlo
        if ($licencia->pdf_path && Storage::disk('public')->exists($licencia->pdf_path)) {
            return Storage::disk('public')->download(
                $licencia->pdf_path,
                'certificado-' . $licencia->numero_licencia . '.pdf'
            );
        }

        // ✅ Si no existe, generarlo en memoria
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

        // ✅ Detecta el tipo y carga la vista correcta
        $vista = match($licencia->tipo_certificado) {
            'evento_publico' => 'pdf.evento_publico',
            default          => 'pdf.licencia',
        };

        $pdf = Pdf::loadView($vista, compact('licencia', 'qr', 'mimeType'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('certificado-' . $licencia->numero_licencia . '.pdf');
    }

    public function verificar($numero)
    {
        $licencia = Licencia::with('contribuyente', 'actividadEconomica')
            ->where('numero_licencia', $numero)
            ->firstOrFail();

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

        $vista = match($licencia->tipo_certificado) {
            'evento_publico' => 'pdf.evento_publico',
            default          => 'pdf.licencia',
        };

        $pdf = Pdf::loadView($vista, compact('licencia', 'qr', 'mimeType'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('certificado-' . $licencia->numero_licencia . '.pdf');
    }

    public function generarFUT()
    {
        return view('pdf.fut_prellenado', [
            'logo' => null,
        ]);
    }
}