<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Contribuyente;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
{
    $anio = request('anio', date('Y'));

    // Certificados emitidos
    $totalCertificados   = Solicitud::count();
    $totalAprobados      = Licencia::where('estado', 'aprobado')->count();
    $totalRechazados     = Licencia::where('estado', 'rechazado')->count()
                         + Solicitud::where('estado', 'rechazado')->count();
    $totalPendientes     = Solicitud::whereIn('estado', ['recibido', 'en_revision'])->count();
    $totalContribuyentes = Contribuyente::count();

    // Por tipo de certificado
    $porTipo = [
        'anexo_14'       => Licencia::where('tipo_certificado', 'anexo_14')->count(),
        'anexo_13'       => Licencia::where('tipo_certificado', 'anexo_13')->count(),
        'evento_publico' => Licencia::where('tipo_certificado', 'evento_publico')->count(),
    ];

    // Por mes del año seleccionado
    $porMes = [];
    for ($mes = 1; $mes <= 12; $mes++) {
        $porMes[] = Licencia::whereYear('fecha_emision', $anio)
            ->whereMonth('fecha_emision', $mes)
            ->count();
    }

    // Próximos a vencer (30 días)
    $proximosVencer = Licencia::where('estado', 'aprobado')
        ->whereBetween('fecha_vencimiento', [now(), now()->addDays(30)])
        ->with('contribuyente')
        ->get();

    // Últimos 10 certificados
    $ultimos = Licencia::with('contribuyente')
        ->latest()
        ->take(10)
        ->get();

    // Años disponibles para filtro
    $anios = Licencia::selectRaw('YEAR(fecha_emision) as anio')
        ->whereNotNull('fecha_emision')
        ->distinct()
        ->orderBy('anio', 'desc')
        ->pluck('anio');

    return view('reportes.index', compact(
        'totalCertificados', 'totalAprobados', 'totalPendientes',
        'totalRechazados', 'totalContribuyentes', 'porTipo',
        'porMes', 'proximosVencer', 'ultimos', 'anio', 'anios'
    ));
}
}