<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfController extends Controller
{
    public function generarLicencia(Licencia $licencia)
    {
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

    public function generarFUT($id = null)
    {
        $qrContent = 'FUT-' . date('YmdHis');
        
        $svgContent = QrCode::format('svg')
            ->size(120)
            ->margin(2)
            ->generate($qrContent);

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

        $pdf = Pdf::loadView('pdf.fut_pdf', compact('qr', 'mimeType'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('FUT_PDF-' . date('YmdHis') . '.pdf');
    }
}